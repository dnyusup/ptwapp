<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\User;

class AreaController extends Controller
{
    /**
     * Check if user has access to area settings
     */
    private function checkAccess()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'administrator';
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';
        
        if (!$isAdmin && !$isEHS) {
            abort(403, 'Access denied. Administrator or EHS access required.');
        }
    }

    /**
     * Display a listing of the areas.
     */
    public function index(Request $request)
    {
        $this->checkAccess();
        
        $search = $request->get('search');
        
        $areas = Area::with('responsibles')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('settings.areas.index', compact('areas', 'search'));
    }

    /**
     * Show the form for creating a new area.
     */
    public function create()
    {
        $this->checkAccess();
        
        // Get EHS department users for responsible selection
        $ehsUsers = User::where('role', 'bekaert')
            ->where('department', 'EHS')
            ->orderBy('name')
            ->get();

        return view('settings.areas.create', compact('ehsUsers'));
    }

    /**
     * Store a newly created area in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess();
        
        \Log::info('Area store request', $request->all());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
            'description' => 'nullable|string',
            'responsibles' => 'required|array|min:1',
            'responsibles.*' => 'exists:users,id',
        ]);

        \Log::info('Area validated data', $validated);

        $area = Area::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        \Log::info('Area created', ['id' => $area->id]);

        // Attach responsible users
        if (!empty($validated['responsibles'])) {
            $area->responsibles()->attach($validated['responsibles']);
        }

        return redirect()->route('settings.areas.index')
            ->with('success', 'Area created successfully.');
    }

    /**
     * Show the form for editing the specified area.
     */
    public function edit(Area $area)
    {
        $this->checkAccess();
        
        // Get EHS department users for responsible selection
        $ehsUsers = User::where('role', 'bekaert')
            ->where('department', 'EHS')
            ->orderBy('name')
            ->get();

        $area->load('responsibles');

        return view('settings.areas.edit', compact('area', 'ehsUsers'));
    }

    /**
     * Update the specified area in storage.
     */
    public function update(Request $request, Area $area)
    {
        $this->checkAccess();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'description' => 'nullable|string',
            'responsibles' => 'required|array|min:1',
            'responsibles.*' => 'exists:users,id',
        ]);

        $area->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync responsible users
        $area->responsibles()->sync($validated['responsibles'] ?? []);

        return redirect()->route('settings.areas.index')
            ->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified area from storage.
     */
    public function destroy(Area $area)
    {
        $this->checkAccess();
        
        $area->responsibles()->detach();
        $area->delete();

        return redirect()->route('settings.areas.index')
            ->with('success', 'Area deleted successfully.');
    }
}
