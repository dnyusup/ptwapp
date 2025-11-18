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
        
        // Update expired permits when dashboard is accessed
        PermitToWork::updateExpiredPermits();
        
        $stats = [
            'total_permits' => PermitToWork::count(),
            'waiting_approval_permits' => PermitToWork::where('status', 'pending_approval')->count(),
            'active_permits' => PermitToWork::where('status', 'active')->count(),
            'expired_permits' => PermitToWork::where('status', 'expired')->count(),
            'pending_extension_permits' => PermitToWork::where('status', 'pending_extension_approval')->count(),
            'rejected_permits' => PermitToWork::where('status', 'rejected')->count(),
            'draft_permits' => PermitToWork::where('status', 'draft')->count(),
            'completed_permits' => PermitToWork::where('status', 'completed')->count(),
            // Keep old keys for backward compatibility
            'pending_permits' => PermitToWork::where('status', 'pending_approval')->count(),
            'in_progress_permits' => PermitToWork::where('status', 'in_progress')->count(),
        ];

        $recent_permits = PermitToWork::with(['permitIssuer', 'authorizer'])
            ->latest()
            ->take(10)
            ->get();

        // Get today's work permits (active permits that are scheduled for today)
        $today = now()->format('Y-m-d');
        $today_permits = PermitToWork::with(['permitIssuer', 'authorizer'])
            ->where(function($query) use ($today) {
                $query->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today);
            })
            ->whereIn('status', ['active', 'expired', 'completed'])
            ->orderBy('start_date')
            ->get();

        // Today's work summary
        $today_summary = [
            'total' => $today_permits->count(),
            'active' => $today_permits->where('status', 'active')->count(),
            'expired' => $today_permits->where('status', 'expired')->count(),
            'completed' => $today_permits->where('status', 'completed')->count(),
        ];

        return view('dashboard', compact('stats', 'recent_permits', 'user', 'today_permits', 'today_summary'));
    }
}
