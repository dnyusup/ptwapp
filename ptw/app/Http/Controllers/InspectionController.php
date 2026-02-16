<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\PermitToWork;
use App\Models\User;
use App\Mail\InspectionNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InspectionController extends Controller
{
    public function store(Request $request, $permitNumber)
    {
        try {
            Log::info('Inspection store attempt', [
                'permitNumber' => $permitNumber,
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'inspector_name' => 'required|string|max:255',
                'inspector_email' => 'required|email|max:255', 
                'findings' => 'required|string',
                'inspection_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120'
            ]);

            $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();
            
            Log::info('Permit found', ['permit' => $permit->toArray()]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('inspection_photo')) {
                $photo = $request->file('inspection_photo');
                $filename = 'inspection_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('inspections', $filename, 'public');
            }

            $inspection = Inspection::create([
                'permit_number' => $permit->permit_number,
                'inspector_name' => $request->inspector_name,
                'inspector_email' => $request->inspector_email,
                'findings' => $request->findings,
                'photo_path' => $photoPath
            ]);

            Log::info('Inspection created successfully', ['inspection' => $inspection->toArray()]);

            // Send email notification to EHS team
            try {
                $ehsUsers = \App\Models\User::where('role', 'bekaert')
                    ->where('department', 'EHS')
                    ->get();
                $ehsEmails = $ehsUsers->pluck('email')->filter()->unique()->toArray();
                
                if (count($ehsEmails) > 0) {
                    // Get area/location owner email
                    $ccEmails = [];
                    if ($permit->locationOwner && $permit->locationOwner->email) {
                        $ccEmails[] = $permit->locationOwner->email;
                    }
                    
                    // Add inspector email to CC
                    if ($request->inspector_email) {
                        $ccEmails[] = $request->inspector_email;
                    }
                    
                    // Remove duplicates from CC
                    $ccEmails = array_unique($ccEmails);
                    
                    $mail = \Mail::to($ehsEmails);
                    if (!empty($ccEmails)) {
                        $mail->cc($ccEmails);
                    }
                    $mail->send(new \App\Mail\InspectionNotification($inspection));
                    
                    Log::info('Inspection notification sent', [
                        'ehs_emails' => $ehsEmails,
                        'cc_emails' => $ccEmails,
                        'inspection_id' => $inspection->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send inspection notification', [
                    'error' => $e->getMessage(),
                    'inspection_id' => $inspection->id
                ]);
                // Don't fail the inspection creation if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Inspection berhasil disimpan',
                'inspection' => $inspection
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception in inspection store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'permitNumber' => $permitNumber
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save inspection: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index($permitNumber)
    {
        $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();
        $inspections = $permit->inspections()->orderBy('created_at', 'desc')->get();

        return view('inspections.index', compact('permit', 'inspections'));
    }
}
