<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Contractors should not access dashboard, redirect to permits
        if ($user->role === 'contractor') {
            return redirect()->route('permits.index');
        }
        
        // Update expired permits when dashboard is accessed
        PermitToWork::updateExpiredPermits();

        $myDashboard = $request->boolean('my_dashboard');

        // Base query — filter by issuer when My Dashboard is active
        $base = $myDashboard
            ? PermitToWork::where('permit_issuer_id', $user->id)
            : PermitToWork::query();

        $stats = [
            'total_permits' => (clone $base)->count(),
            'waiting_approval_permits' => (clone $base)->where('status', 'pending_approval')->count(),
            'active_permits' => (clone $base)->where('status', 'active')->count(),
            'expired_permits' => (clone $base)->where('status', 'expired')->count(),
            'pending_extension_permits' => (clone $base)->where('status', 'pending_extension_approval')->count(),
            'rejected_permits' => (clone $base)->where('status', 'rejected')->count(),
            'draft_permits' => (clone $base)->where('status', 'draft')->count(),
            'completed_permits' => (clone $base)->where('status', 'completed')->count(),
            // Keep old keys for backward compatibility
            'pending_permits' => (clone $base)->where('status', 'pending_approval')->count(),
            'in_progress_permits' => (clone $base)->where('status', 'in_progress')->count(),
        ];

        $recent_permits = (clone $base)->with(['permitIssuer', 'authorizer'])
            ->latest()
            ->take(10)
            ->get();

        // Get today's work permits (active permits that are scheduled for today)
        $today = now()->format('Y-m-d');
        $today_base = (clone $base)->with(['permitIssuer', 'authorizer'])
            ->where(function($query) use ($today) {
                $query->whereDate('start_date', '<=', $today)
                      ->whereDate('end_date', '>=', $today);
            })
            ->whereIn('status', ['active', 'expired', 'completed'])
            ->orderBy('start_date');

        // Today's work summary (count all, not just paginated)
        $all_today_permits = (clone $today_base)->get();
        $today_summary = [
            'total' => $all_today_permits->count(),
            'active' => $all_today_permits->where('status', 'active')->count(),
            'expired' => $all_today_permits->where('status', 'expired')->count(),
            'completed' => $all_today_permits->where('status', 'completed')->count(),
        ];

        // Paginate for display (10 per page)
        $today_permits = $today_base->paginate(10, ['*'], 'today_page');

        return view('dashboard', compact('stats', 'recent_permits', 'user', 'today_permits', 'today_summary', 'myDashboard'));
    }
}
