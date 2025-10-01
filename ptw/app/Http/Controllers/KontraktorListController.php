<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KontraktorList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KontraktorListController extends Controller
{
    private function checkAccess()
    {
        $user = auth()->user();
        
        // Only allow administrators and Bekaert EHS department
        if (!($user->role === 'administrator' || ($user->role === 'bekaert' && $user->department === 'EHS'))) {
            abort(403, 'Unauthorized access. Only administrators and Bekaert EHS department can manage contractor lists.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAccess();
        $kontraktors = KontraktorList::orderBy('company_name')->paginate(10);
        return view('kontraktor-lists.index', compact('kontraktors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess();
        return view('kontraktor-lists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess();
        
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'required|string|max:50|unique:kontraktor_lists,company_code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        KontraktorList::create($validatedData);

        return redirect()->route('kontraktor-lists.index')
            ->with('success', 'Kontraktor berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KontraktorList $kontraktorList)
    {
        $this->checkAccess();
        return view('kontraktor-lists.show', compact('kontraktorList'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KontraktorList $kontraktorList)
    {
        $this->checkAccess();
        return view('kontraktor-lists.edit', compact('kontraktorList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KontraktorList $kontraktorList)
    {
        $this->checkAccess();
        
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_code' => 'required|string|max:50|unique:kontraktor_lists,company_code,' . $kontraktorList->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        $kontraktorList->update($validatedData);

        return redirect()->route('kontraktor-lists.index')
            ->with('success', 'Kontraktor berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KontraktorList $kontraktorList)
    {
        $this->checkAccess();
        
        // Check if any users are associated with this contractor
        if ($kontraktorList->users()->count() > 0) {
            return redirect()->route('kontraktor-lists.index')
                ->with('error', 'Tidak dapat menghapus kontraktor yang masih memiliki user terkait.');
        }

        $kontraktorList->delete();

        return redirect()->route('kontraktor-lists.index')
            ->with('success', 'Kontraktor berhasil dihapus.');
    }
}
