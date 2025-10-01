<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermitToWork;
use App\Models\HraWorkAtHeight;
use App\Models\User;
use Illuminate\Http\Request;

class HraWorkAtHeightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hras = $permit->hraWorkAtHeights()->with('user')->orderBy('created_at', 'desc')->get();
        return view('hra.work-at-heights.index', compact('permit', 'hras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if main permit has work_at_heights enabled
        if (!$permit->work_at_heights) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Work at Heights is not required for this permit.');
        }
        
        // Check if permit status is active
        if ($permit->status !== 'active') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'HRA can only be created when permit status is Active.');
        }
        
        // Load permit with receiver relationship
        $permit->load('receiver');
        
        // Get users from the same company as permit receiver
        $users = collect();
        if ($permit->receiver_company_name) {
            // Get users from the same company as receiver
            $users = User::whereHas('company', function($query) use ($permit) {
                $query->where('company_name', $permit->receiver_company_name);
            })->select('id', 'name', 'email', 'phone')->orderBy('name')->get();
        }
        
        return view('hra.work-at-heights.create', compact('permit', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PermitToWork $permit)
    {
        // Check if permit status is active
        if ($permit->status !== 'active') {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'HRA can only be created when permit status is Active.');
        }
        
        $validated = $request->validate([
            // Basic Information
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'work_description' => 'required|string',
            
            // Fixed Scaffolding
            'fixed_scaffolding_checked' => 'boolean',
            'fixed_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Scaffolding
            'mobile_scaffolding_checked' => 'boolean',
            'mobile_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Elevation Platform
            'mobile_elevation_checked' => 'boolean',
            'mobile_elevation_used_before' => 'boolean',
            'mobile_elevation_training_provided' => 'boolean',
            
            // Ladder
            'ladder_checked' => 'boolean',
            'mobile_elevation_activities_short' => 'boolean',
            'mobile_elevation_location_marked' => 'boolean',
            'ladder_area_barriers' => 'boolean',
            
            // Fall Arrest
            'fall_arrest_used' => 'boolean',
            'safety_personnel_required' => 'boolean',
            
            // Roof Work
            'roof_work_checked' => 'boolean',
            'roof_fragile_areas' => 'boolean',
            'roof_fall_protection' => 'boolean',
            'area_closed_from_below' => 'boolean',
            
            // Work Conditions  
            'area_below_closed' => 'boolean',
            'work_area_disturbances' => 'boolean', 
            'ventilation_hazards' => 'boolean',
            'equipment_protection' => 'boolean',
            'emergency_exit_available' => 'boolean',
            'material_handling' => 'boolean',
            'safety_personnel_needed' => 'boolean',
            'other_conditions_check' => 'boolean',
            'other_conditions_text' => 'nullable|string|max:1000',
            
            // Environmental Conditions (single select)
            'visibility_condition' => 'nullable|string|in:terang,remang-remang,gelap,berkabut',
            'rain_condition' => 'nullable|string|in:tidak,rintik,gerimis,deras',
            'surface_condition' => 'nullable|string|in:kering,basah,licin',
            'wind_condition' => 'nullable|string|in:tidak,kecil,sedang,kuat',
            'chemical_spill_condition' => 'nullable|string|in:Ya,Tidak',
            'environment_other_conditions' => 'nullable|string|max:1000',
            
            // Additional Controls
            'additional_controls' => 'nullable|string|max:2000',
        ]);
        
        // Generate HRA permit number
        $hraPermitNumber = HraWorkAtHeight::generateHraPermitNumber($permit->permit_number);
        
        $validated['hra_permit_number'] = $hraPermitNumber;
        $validated['permit_to_work_id'] = $permit->id;
        $validated['permit_number'] = $permit->permit_number;
        $validated['user_id'] = auth()->id(); // Add current user as creator
        
        $hraPermit = HraWorkAtHeight::create($validated);
        
        return redirect()->route('hra.work-at-heights.show', [$permit, $hraPermit])
            ->with('success', 'HRA Work at Heights permit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        return view('hra.work-at-heights.show', compact('permit', 'hraWorkAtHeight'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        return view('hra.work-at-heights.edit', compact('permit', 'hraWorkAtHeight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        $validated = $request->validate([
            // Basic Information
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'work_description' => 'required|string',
            
            // Fixed Scaffolding
            'fixed_scaffolding_checked' => 'boolean',
            'fixed_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Scaffolding
            'mobile_scaffolding_checked' => 'boolean',
            'mobile_scaffolding_approved_by_she' => 'boolean',
            
            // Mobile Elevation Platform
            'mobile_elevation_checked' => 'boolean',
            'mobile_elevation_used_before' => 'boolean',
            'mobile_elevation_training_provided' => 'boolean',
            
            // Ladder
            'ladder_checked' => 'boolean',
            'mobile_elevation_activities_short' => 'boolean',
            'mobile_elevation_location_marked' => 'boolean',
            'ladder_area_barriers' => 'boolean',
            
            // Fall Arrest
            'fall_arrest_used' => 'boolean',
            'safety_personnel_required' => 'boolean',
            
            // Roof Work
            'roof_work_checked' => 'boolean',
            'roof_fragile_areas' => 'boolean',
            'roof_fall_protection' => 'boolean',
            'area_closed_from_below' => 'boolean',
            
            // Work Conditions  
            'area_below_closed' => 'boolean',
            'work_area_disturbances' => 'boolean',
            'ventilation_hazards' => 'boolean',
            'equipment_protection' => 'boolean',
            'emergency_exit_available' => 'boolean',
            'material_handling' => 'boolean',
            'safety_personnel_needed' => 'boolean',
            'other_conditions_check' => 'boolean',
            'other_conditions_text' => 'nullable|string|max:1000',
            
            // Environmental Conditions (single select)
            'visibility_condition' => 'nullable|string|in:terang,remang-remang,gelap,berkabut',
            'rain_condition' => 'nullable|string|in:tidak,rintik,gerimis,deras',
            'surface_condition' => 'nullable|string|in:kering,basah,licin',
            'wind_condition' => 'nullable|string|in:tidak,kecil,sedang,kuat',
            'chemical_spill_condition' => 'nullable|string|in:Ya,Tidak',
            'environment_other_conditions' => 'nullable|string|max:1000',
            
            // Additional Controls
            'additional_controls' => 'nullable|string|max:2000',
        ]);
        
        $hraWorkAtHeight->update($validated);
        
        return redirect()->route('hra.work-at-heights.show', [$permit, $hraWorkAtHeight])
            ->with('success', 'HRA Work at Heights permit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        $hraWorkAtHeight->delete();
        
        return redirect()->route('permits.show', $permit)
            ->with('success', 'HRA Work at Heights permit deleted successfully!');
    }

    /**
     * Download HRA as PDF
     */
    public function downloadPdf(PermitToWork $permit, HraWorkAtHeight $hraWorkAtHeight)
    {
        // Load relationships
        $permit->load([
            'permitIssuer', 
            'authorizer', 
            'receiver', 
            'methodStatement'
        ]);
        
        $hraWorkAtHeight->load('user');

        // Generate QR Code for HRA
        $qrUrl = url('/permits/' . $permit->id . '/hra/work-at-heights/' . $hraWorkAtHeight->id);
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(80),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrCode = base64_encode($writer->writeString($qrUrl));

        // Generate QR Code for Main Permit
        $permitUrl = route('permits.show', $permit->id);
        $permitRenderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(70),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $permitWriter = new \BaconQrCode\Writer($permitRenderer);
        $permitQrCode = base64_encode($permitWriter->writeString($permitUrl));

        $pdf = \PDF::loadView('hra.work-at-heights.pdf', compact('permit', 'hraWorkAtHeight', 'qrCode', 'permitQrCode'));
        
        return $pdf->download('hra-' . $hraWorkAtHeight->hra_permit_number . '.pdf');
    }
}
