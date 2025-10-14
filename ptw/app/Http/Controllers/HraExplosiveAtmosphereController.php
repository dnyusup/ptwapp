<?php

namespace App\Http\Controllers;

use App\Models\HraExplosiveAtmosphere;
use App\Models\PermitToWork;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HraExplosiveAtmosphereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermitToWork $permit)
    {
        $hraExplosiveAtmospheres = $permit->hraExplosiveAtmospheres()->orderBy('created_at', 'desc')->get();
        
        return view('hra.explosive-atmospheres.index', compact('permit', 'hraExplosiveAtmospheres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PermitToWork $permit)
    {
        // Check if explosive atmosphere is required for this permit
        if (!$permit->explosive_atmosphere) {
            return redirect()->route('permits.show', $permit)
                ->with('error', 'Explosive Atmosphere is not required for this permit.');
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
        
        return view('hra.explosive-atmospheres.create', compact('permit', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PermitToWork $permit)
    {
        $validated = $request->validate([
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'work_description' => 'required|string',
        ]);
        
        // Combine date and time fields into datetime
        $validated['start_datetime'] = $validated['start_date'] . ' ' . $validated['start_time'];
        $validated['end_datetime'] = $validated['end_date'] . ' ' . $validated['end_time'];
        
        // Remove the separate date and time fields as they're not needed in database
        unset($validated['start_date'], $validated['start_time'], $validated['end_date'], $validated['end_time']);

        // Generate HRA permit number
        $hraPermitNumber = HraExplosiveAtmosphere::generateHraPermitNumber();

        $hraExplosiveAtmosphere = HraExplosiveAtmosphere::create([
            'hra_permit_number' => $hraPermitNumber,
            'permit_to_work_id' => $permit->id,
            'user_id' => Auth::id(),
            'worker_name' => $validated['worker_name'],
            'worker_phone' => $validated['worker_phone'],
            'supervisor_name' => $validated['supervisor_name'],
            'work_location' => $validated['work_location'],
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'],
            'work_description' => $validated['work_description'],
            'status' => 'draft',
        ]);

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA Explosive Atmosphere created successfully with permit number: ' . $hraPermitNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(PermitToWork $permit, HraExplosiveAtmosphere $hraExplosiveAtmosphere)
    {
        // Ensure the HRA belongs to the permit
        if ($hraExplosiveAtmosphere->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        return view('hra.explosive-atmospheres.show', compact('permit', 'hraExplosiveAtmosphere'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PermitToWork $permit, HraExplosiveAtmosphere $hraExplosiveAtmosphere)
    {
        // Ensure the HRA belongs to the permit
        if ($hraExplosiveAtmosphere->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        return view('hra.explosive-atmospheres.edit', compact('permit', 'hraExplosiveAtmosphere'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PermitToWork $permit, HraExplosiveAtmosphere $hraExplosiveAtmosphere)
    {
        // Ensure the HRA belongs to the permit
        if ($hraExplosiveAtmosphere->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        $validated = $request->validate([
            'worker_name' => 'required|string|max:255',
            'worker_phone' => 'nullable|string|max:20',
            'supervisor_name' => 'required|string|max:255',
            'work_location' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'work_description' => 'required|string',
        ]);

        $hraExplosiveAtmosphere->update($validated);

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA Explosive Atmosphere updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermitToWork $permit, HraExplosiveAtmosphere $hraExplosiveAtmosphere)
    {
        // Ensure the HRA belongs to the permit
        if ($hraExplosiveAtmosphere->permit_to_work_id !== $permit->id) {
            abort(404);
        }

        $hraExplosiveAtmosphere->delete();

        return redirect()->route('permits.show', $permit)
                        ->with('success', 'HRA Explosive Atmosphere deleted successfully.');
    }
}
