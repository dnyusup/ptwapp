<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KontraktorList;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();
        if ($user && !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
        if ($user && $user->status !== 'active') {
            return back()->withErrors([
                'email' => 'Akun belum diaktivasi oleh EHS. Silakan tunggu proses aktivasi.'
            ])->onlyInput('email');
        }
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            Auth::user()->update(['last_login_at' => now()]);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        $companies = KontraktorList::where('is_active', true)->get();
        return view('auth.register', compact('companies'));
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:administrator,contractor,bekaert',
            'phone' => 'required|string|max:20',
        ];
        if ($request->role === 'bekaert') {
            $rules['department'] = 'required|string|max:255';
        } else {
            $rules['department'] = 'nullable|string|max:255';
        }
        if ($request->role === 'contractor') {
            $rules['company_id'] = 'required|exists:kontraktor_lists,id';
        } else {
            $rules['company_id'] = 'nullable|exists:kontraktor_lists,id';
        }
        $request->validate($rules);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department' => $request->department,
            'phone' => $request->phone,
            'company_id' => $request->company_id,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}
