<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ContractorUserController extends Controller
{
    /**
     * Check if user has contractor access with company assigned
     */
    private function checkContractorAccess()
    {
        $user = auth()->user();
        
        // Only contractor users can access this controller
        if (!$user || $user->role !== 'contractor') {
            abort(403, 'Access denied. Contractor access required.');
        }
        
        // Contractor must have a company assigned
        if (!$user->company_id) {
            abort(403, 'Access denied. No company assigned to your account.');
        }
        
        return $user;
    }

    /**
     * Display a listing of users from the same company.
     */
    public function index(Request $request)
    {
        $currentUser = $this->checkContractorAccess();
        $search = $request->get('search');
        $statusFilter = $request->get('status');
        
        $users = User::with('company')
            ->where('company_id', $currentUser->company_id)
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $company = $currentUser->company;

        return view('contractor-users.index', compact('users', 'search', 'statusFilter', 'company'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $currentUser = $this->checkContractorAccess();
        $company = $currentUser->company;
        
        return view('contractor-users.create', compact('company'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $currentUser = $this->checkContractorAccess();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'contractor', // Always contractor
            'company_id' => $currentUser->company_id, // Always same company
            'status' => 'active', // Auto-active for contractor-created users
        ]);

        return redirect()->route('contractor-users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $contractor_user)
    {
        $currentUser = $this->checkContractorAccess();
        
        // Can only view users from same company
        if ($contractor_user->company_id !== $currentUser->company_id) {
            abort(403, 'Access denied. You can only view users from your company.');
        }
        
        $contractor_user->load('company');
        $user = $contractor_user;
        $company = $currentUser->company;
        return view('contractor-users.show', compact('user', 'company'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(User $contractor_user)
    {
        $currentUser = $this->checkContractorAccess();
        
        // Can only edit users from same company
        if ($contractor_user->company_id !== $currentUser->company_id) {
            abort(403, 'Access denied. You can only edit users from your company.');
        }
        
        $user = $contractor_user;
        $company = $currentUser->company;
        return view('contractor-users.edit', compact('user', 'company'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $contractor_user)
    {
        $currentUser = $this->checkContractorAccess();
        
        // Can only update users from same company
        if ($contractor_user->company_id !== $currentUser->company_id) {
            abort(403, 'Access denied. You can only edit users from your company.');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 
                        \Illuminate\Validation\Rule::unique('users')->ignore($contractor_user->id)],
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ];

        // Password is optional on update
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            // Role and company_id remain unchanged
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $contractor_user->update($userData);

        return redirect()->route('contractor-users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $contractor_user)
    {
        $currentUser = $this->checkContractorAccess();
        
        // Can only delete users from same company
        if ($contractor_user->company_id !== $currentUser->company_id) {
            abort(403, 'Access denied. You can only delete users from your company.');
        }
        
        // Cannot delete yourself
        if ($contractor_user->id === $currentUser->id) {
            return redirect()->route('contractor-users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        $contractor_user->delete();

        return redirect()->route('contractor-users.index')
            ->with('success', 'User deleted successfully.');
    }
}
