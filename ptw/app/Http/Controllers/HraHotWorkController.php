<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermitToWork;
use App\Models\HraHotWork;
use App\Models\User;
use Illuminate\Http\Request;

class HraHotWorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hras = $permit->hraHotWorks()->with('user')->orderBy('created_at', 'desc')->get();
        return view('hra.hot-works.index', compact('permit', 'hras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if main permit has hot_work enabled
        if (!$permit->hot_work) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Hot Work is not required for this permit.');
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
        
        return view('hra.hot-works.create', compact('permit', 'users'));
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
            // Hot Work Safety Checklist
            'q1_alternative_considered' => 'nullable|boolean',
            'q2_equipment_checked' => 'nullable|boolean',
            'q3_flammable_moved' => 'nullable|boolean',
            'q3_distance' => 'nullable|integer|min:1',
            'q4_protected_cover' => 'nullable|boolean',
            'q5_debris_cleaned' => 'nullable|boolean',
            'q6_area_inspected' => 'nullable|boolean',
            'q7_flammable_structures' => 'nullable|boolean',
            'q7_actions_taken' => 'nullable|string',
            'q8_fire_blanket' => 'nullable|boolean',
            'q9_valve_drain_covered' => 'nullable|boolean',
            'q10_isolation_ducting' => 'nullable|boolean',
            'q11_holes_sealed' => 'nullable|boolean',
            'q12_ventilation_type' => 'nullable|in:alami,buatan',

            'q12_ventilation_adequate' => 'nullable|boolean',
            'q13_electrical_protected' => 'nullable|boolean',
            'q14_equipment_protected' => 'nullable|boolean',
            'q15_overhead_protection' => 'nullable|boolean',
            'q16_area_marked' => 'nullable|boolean',
            'q17_gas_monitoring' => 'nullable|boolean',
            // Peralatan Pemadam Api
            'apar_air' => 'nullable|boolean',
            'apar_powder' => 'nullable|boolean',
            'apar_co2' => 'nullable|boolean',
            'fire_blanket' => 'nullable|boolean',
            'fire_watch_officer' => 'nullable|boolean',
            'fire_watch_name' => 'nullable|string|max:255',
            'monitoring_sprinkler' => 'nullable|boolean',
            'monitoring_combustible' => 'nullable|boolean',
            'monitoring_distance' => 'nullable|string|max:255',
            'emergency_call' => 'nullable|string|max:255',
            'sprinkler_check' => 'nullable|boolean',
            'detector_shutdown' => 'nullable|boolean',
            'notification_required' => 'nullable|boolean',
            'notification_phone' => 'nullable|string|max:20',
            'notification_name' => 'nullable|string|max:255',
            'insurance_notification' => 'nullable|boolean',
            'detector_confirmed_off' => 'nullable|boolean',
            'gas_monitoring_required' => 'nullable|boolean',
        ]);
        
        // Generate HRA permit number
        $hraPermitNumber = HraHotWork::generateHraPermitNumber($permit->permit_number);
        
        $validated['hra_permit_number'] = $hraPermitNumber;
        $validated['permit_to_work_id'] = $permit->id;
        $validated['permit_number'] = $permit->permit_number;
        $validated['user_id'] = auth()->id(); // Add current user as creator
        
        $hraPermit = HraHotWork::create($validated);
        
        return redirect()->route('hra.hot-works.show', [$permit, $hraPermit])
            ->with('success', 'HRA Hot Work permit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        return view('hra.hot-works.show', compact('permit', 'hraHotWork'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        return view('hra.hot-works.edit', compact('permit', 'hraHotWork'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraHotWork $hraHotWork)
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
            // Hot Work Safety Checklist
            'q1_alternative_considered' => 'nullable|boolean',
            'q2_equipment_checked' => 'nullable|boolean',
            'q3_flammable_moved' => 'nullable|boolean',
            'q3_distance' => 'nullable|integer|min:1',
            'q4_protected_cover' => 'nullable|boolean',
            'q5_debris_cleaned' => 'nullable|boolean',
            'q6_area_inspected' => 'nullable|boolean',
            'q7_flammable_structures' => 'nullable|boolean',
            'q7_actions_taken' => 'nullable|string',
            'q8_fire_blanket' => 'nullable|boolean',
            'q9_valve_drain_covered' => 'nullable|boolean',
            'q10_isolation_ducting' => 'nullable|boolean',
            'q11_holes_sealed' => 'nullable|boolean',
            'q12_ventilation_type' => 'nullable|in:alami,buatan',

            'q12_ventilation_adequate' => 'nullable|boolean',
            'q13_electrical_protected' => 'nullable|boolean',
            'q14_equipment_protected' => 'nullable|boolean',
            'q15_overhead_protection' => 'nullable|boolean',
            'q16_area_marked' => 'nullable|boolean',
            'q17_gas_monitoring' => 'nullable|boolean',
            // Peralatan Pemadam Api
            'apar_air' => 'nullable|boolean',
            'apar_powder' => 'nullable|boolean',
            'apar_co2' => 'nullable|boolean',
            'fire_blanket' => 'nullable|boolean',
            'fire_watch_officer' => 'nullable|boolean',
            'fire_watch_name' => 'nullable|string|max:255',
            'monitoring_sprinkler' => 'nullable|boolean',
            'monitoring_combustible' => 'nullable|boolean',
            'monitoring_distance' => 'nullable|string|max:255',
            'emergency_call' => 'nullable|string|max:255',
            'sprinkler_check' => 'nullable|boolean',
            'detector_shutdown' => 'nullable|boolean',
            'notification_required' => 'nullable|boolean',
            'notification_phone' => 'nullable|string|max:20',
            'notification_name' => 'nullable|string|max:255',
            'insurance_notification' => 'nullable|boolean',
            'detector_confirmed_off' => 'nullable|boolean',
            'gas_monitoring_required' => 'nullable|boolean',
        ]);
        
        $hraHotWork->update($validated);
        
        return redirect()->route('hra.hot-works.show', [$permit, $hraHotWork])
            ->with('success', 'HRA Hot Work permit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraHotWork $hraHotWork)
    {
        $hraHotWork->delete();
        
        return redirect()->route('permits.show', $permit)
            ->with('success', 'HRA Hot Work permit deleted successfully!');
    }
}
