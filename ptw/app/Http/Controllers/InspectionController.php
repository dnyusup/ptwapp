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
use Illuminate\Support\Facades\Storage;

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
                'inspection_category' => 'required|string|max:255',
                'finding_type' => 'required|in:OK,NOK',
                'inspection_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
                'inspection_photo_data' => 'nullable|string'
            ]);

            $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();
            
            Log::info('Permit found', ['permit' => $permit->toArray()]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('inspection_photo')) {
                $photo = $request->file('inspection_photo');
                $filename = 'inspection_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('inspections', $filename, 'public');
                // Resize to save storage
                $this->resizeStoredImage(Storage::disk('public')->path($photoPath));
            } elseif ($request->filled('inspection_photo_data')) {
                // Handle base64 data (fallback for camera capture)
                $base64Data = $request->input('inspection_photo_data');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
                    $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                    $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                    $imageData = base64_decode($base64Data);
                    
                    if ($imageData !== false) {
                        $filename = 'inspection_' . time() . '_' . uniqid() . '.' . $extension;
                        $photoPath = 'inspections/' . $filename;
                        Storage::disk('public')->put($photoPath, $imageData);
                        // Resize to save storage
                        $this->resizeStoredImage(Storage::disk('public')->path($photoPath));
                    }
                }
            }

            $inspection = Inspection::create([
                'permit_number' => $permit->permit_number,
                'inspector_name' => $request->inspector_name,
                'inspector_email' => $request->inspector_email,
                'findings' => $request->findings,
                'inspection_category' => $request->inspection_category,
                'finding_type' => $request->finding_type,
                'photo_path' => $photoPath
            ]);

            Log::info('Inspection created successfully', ['inspection' => $inspection->toArray()]);

            // Send email notification to EHS team
            try {
                // Get EHS users based on permit's area (or all EHS if no area)
                $ehsEmails = \App\Services\EhsEmailService::getEhsEmails($permit->area_id);
                
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

    /**
     * Resize an already-saved image to max 1280×960 using PHP GD.
     * Falls back silently if GD is unavailable or the file cannot be processed.
     */
    private function resizeStoredImage(string $filePath, int $maxWidth = 1280, int $maxHeight = 960): void
    {
        if (!function_exists('imagecreatefromjpeg') || !file_exists($filePath)) {
            return;
        }

        try {
            $info = @getimagesize($filePath);
            if (!$info) return;

            [$origW, $origH, $type] = $info;

            // Skip if already within limits
            if ($origW <= $maxWidth && $origH <= $maxHeight) return;

            $ratio = min($maxWidth / $origW, $maxHeight / $origH);
            $newW  = (int) round($origW * $ratio);
            $newH  = (int) round($origH * $ratio);

            $src = match ($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($filePath),
                IMAGETYPE_PNG  => imagecreatefrompng($filePath),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($filePath) : null,
                default        => null,
            };

            if (!$src) return;

            $dst = imagecreatetruecolor($newW, $newH);
            // Preserve transparency for PNG
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

            match ($type) {
                IMAGETYPE_JPEG => imagejpeg($dst, $filePath, 85),
                IMAGETYPE_PNG  => imagepng($dst, $filePath, 8),
                IMAGETYPE_WEBP => function_exists('imagewebp') ? imagewebp($dst, $filePath, 85) : null,
                default        => null,
            };

            imagedestroy($src);
            imagedestroy($dst);
        } catch (\Throwable $e) {
            Log::warning('Image resize failed', ['file' => $filePath, 'error' => $e->getMessage()]);
        }
    }
}
