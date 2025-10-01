<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KontraktorList;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Pengecekan akan dilakukan di method individual
    }

    private function checkAdministrator()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'administrator';
        $isEHS = $user->role === 'bekaert' && $user->department === 'EHS';
        
        if (!$isAdmin && !$isEHS) {
            abort(403, 'Access denied. Administrator or EHS access required.');
        }
    }

    private function isEHSUser()
    {
        $user = auth()->user();
        return $user->role === 'bekaert' && $user->department === 'EHS';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAdministrator();
        
        $search = $request->get('search');
        
        $users = User::with('company')->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAdministrator();
        $companies = KontraktorList::where('is_active', true)->orderBy('company_name')->get();
        return view('users.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdministrator();
        
        // EHS users cannot create administrators
        if ($this->isEHSUser() && $request->role === 'administrator') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['role' => 'You do not have permission to create administrator accounts.']);
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:bekaert,contractor',
        ];

        // For administrators, allow creating administrator role
        if (auth()->user()->role === 'administrator') {
            $rules['role'] = 'required|in:bekaert,contractor,administrator';
        }

        // Jika role adalah bekaert, department wajib diisi
        if ($request->role === 'bekaert') {
            $rules['department'] = 'required|string|max:255';
        }

        // Jika role adalah contractor, company_id wajib diisi
        if ($request->role === 'contractor') {
            $rules['company_id'] = 'required|exists:kontraktor_lists,id';
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // Tambahkan department jika role adalah bekaert
        if ($request->role === 'bekaert') {
            $userData['department'] = $request->department;
        }

        // Tambahkan company_id jika role adalah contractor
        if ($request->role === 'contractor') {
            $userData['company_id'] = $request->company_id;
        }

        User::create($userData);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->checkAdministrator();
        
        // Non-administrators cannot view administrator users details
        if (auth()->user()->role !== 'administrator' && $user->role === 'administrator') {
            abort(403, 'Access denied. Only administrators can view administrator account details.');
        }
        
        $user->load('company');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->checkAdministrator();
        
        // Non-administrators cannot edit administrator users
        if (auth()->user()->role !== 'administrator' && $user->role === 'administrator') {
            abort(403, 'Access denied. Only administrators can edit administrator accounts.');
        }
        
        $companies = KontraktorList::where('is_active', true)
                                  ->orderBy('company_name')
                                  ->get();
        return view('users.edit', compact('user', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdministrator();
        
        // Non-administrators cannot edit administrator users
        if (auth()->user()->role !== 'administrator' && $user->role === 'administrator') {
            abort(403, 'Access denied. Only administrators can edit administrator accounts.');
        }
        
        // EHS users cannot change role to administrator
        if ($this->isEHSUser() && $request->role === 'administrator') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['role' => 'You do not have permission to set administrator role.']);
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:bekaert,contractor',
        ];

        // For administrators, allow editing to administrator role
        if (auth()->user()->role === 'administrator') {
            $rules['role'] = 'required|in:bekaert,contractor,administrator';
        }

        // Jika role adalah bekaert, department wajib diisi
        if ($request->role === 'bekaert') {
            $rules['department'] = 'required|string|max:255';
        }

        // Jika role adalah contractor, company_id wajib diisi
        if ($request->role === 'contractor') {
            $rules['company_id'] = 'required|exists:kontraktor_lists,id';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        // Tambahkan department jika role adalah bekaert
        if ($request->role === 'bekaert') {
            $data['department'] = $request->department;
            $data['company_id'] = null; // Reset company_id for bekaert role
        } else {
            // Hapus department jika role bukan bekaert
            $data['department'] = null;
        }

        // Tambahkan company_id jika role adalah contractor
        if ($request->role === 'contractor') {
            $data['company_id'] = $request->company_id;
        } else {
            // Reset company_id jika role bukan contractor
            $data['company_id'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->checkAdministrator();
        
        // Check if current user can delete users
        if (!auth()->user()->canDeleteUsers()) {
            return redirect()->route('users.index')
                ->with('error', 'Access denied. EHS department cannot delete users.');
        }
        
        // Non-administrators cannot delete administrator users
        if (auth()->user()->role !== 'administrator' && $user->role === 'administrator') {
            return redirect()->route('users.index')
                ->with('error', 'Access denied. Only administrators can delete administrator accounts.');
        }
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate a pending user (EHS only)
     */
    public function activate(User $user)
    {
        // Only EHS users (bekaert department EHS) or administrators can activate
        $current = auth()->user();
        $isEHS = $current->role === 'bekaert' && $current->department === 'EHS';
        $isAdmin = $current->role === 'administrator';

        if (! $isEHS && ! $isAdmin) {
            abort(403, 'Access denied. Only EHS or administrators can activate users.');
        }

        if ($user->status !== 'pending') {
            return redirect()->route('users.index')
                ->with('error', 'User is not pending activation.');
        }

        $user->status = 'active';
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'User activated successfully.');
    }
}
