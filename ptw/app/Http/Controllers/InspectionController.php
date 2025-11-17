<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\PermitToWork;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request, $permitNumber)
    {
        // Simple test response first
        return response()->json([
            'success' => true,
            'message' => 'Test response working',
            'permitNumber' => $permitNumber,
            'method' => $request->method()
        ]);
        
        try {
            Log::info('Inspection store attempt', [
                'permitNumber' => $permitNumber,
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            $request->validate([
                'inspector_name' => 'required|string|max:255',
                'inspector_email' => 'required|email|max:255', 
                'findings' => 'required|string'
            ]);

            $permit = PermitToWork::where('permit_number', $permitNumber)->firstOrFail();
            
            Log::info('Permit found', ['permit' => $permit->toArray()]);

            $inspection = Inspection::create([
                'permit_number' => $permit->permit_number,
                'inspector_name' => $request->inspector_name,
                'inspector_email' => $request->inspector_email,
                'findings' => $request->findings
            ]);

            Log::info('Inspection created successfully', ['inspection' => $inspection->toArray()]);

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
