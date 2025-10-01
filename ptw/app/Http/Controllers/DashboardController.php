<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_permits' => PermitToWork::count(),
            'pending_permits' => PermitToWork::where('status', 'pending_approval')->count(),
            'active_permits' => PermitToWork::where('status', 'active')->count(),
            'in_progress_permits' => PermitToWork::where('status', 'in_progress')->count(),
        ];

        $recent_permits = PermitToWork::with(['permitIssuer', 'authorizer'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'recent_permits', 'user'));
    }
}
