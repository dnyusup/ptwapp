<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use App\Models\User;
use App\Models\Area;
use App\Models\KontraktorList;
use App\Models\Inspection;
use App\Models\HraWorkAtHeight;
use App\Models\HraHotWork;
use App\Models\HraLotoIsolation;
use App\Models\HraLineBreaking;
use App\Models\HraExcavation;
use App\Models\HraConfinedSpace;
use App\Models\HraExplosiveAtmosphere;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // Update expired permits when reports page is accessed
        PermitToWork::updateExpiredPermits();

        // Get comprehensive statistics
        $data = [
            // Basic counts
            'totalPermits' => PermitToWork::count(),
            'activePermits' => PermitToWork::where('status', 'active')->count(),
            'pendingPermits' => PermitToWork::where('status', 'pending_approval')->count(),
            'draftPermits' => PermitToWork::where('status', 'draft')->count(),
            'expiredPermits' => PermitToWork::where('status', 'expired')->count(),
            'completedPermits' => PermitToWork::where('status', 'completed')->count(),
            'rejectedPermits' => PermitToWork::where('status', 'rejected')->count(),
            'pendingExtension' => PermitToWork::where('status', 'pending_extension_approval')->count(),

            // User statistics
            'totalUsers' => User::count(),
            'adminUsers' => User::where('role', 'administrator')->count(),
            'bekaertUsers' => User::where('role', 'bekaert')->count(),
            'contractorUsers' => User::where('role', 'contractor')->count(),

            // Contract company statistics
            'totalContractors' => KontraktorList::count(),
            'activeContractors' => KontraktorList::where('is_active', true)->count(),

            // Area statistics
            'totalAreas' => Area::count(),
            'activeAreas' => Area::where('is_active', true)->count(),

            // Time-based statistics
            'permitsByStatus' => $this->getPermitsByStatus(),
            'permitsByArea' => $this->getPermitsByArea(),
            'permitsByContractor' => $this->getPermitsByContractor(),
            'permitsByDepartment' => $this->getPermitsByDepartment(),
            
            // Hazard Risk Assessment counts
            'hraWorkAtHeight' => PermitToWork::where('work_at_heights', true)->count(),
            'hraHotWork' => PermitToWork::where('hot_work', true)->count(),
            'hraLotoIsolation' => PermitToWork::where('loto_isolation', true)->count(),
            'hraLineBreaking' => PermitToWork::where('line_breaking', true)->count(),
            'hraExcavation' => PermitToWork::where('excavation', true)->count(),
            'hraConfinedSpace' => PermitToWork::where('confined_spaces', true)->count(),
            'hraExplosiveAtmosphere' => PermitToWork::where('explosive_atmosphere', true)->count(),

            // Recent activities
            'recentPermits' => PermitToWork::with(['permitIssuer', 'area'])
                ->latest()
                ->take(10)
                ->get(),

            // Top performers
            'topIssuers' => $this->getTopIssuers(),
            'topContractorCompanies' => $this->getTopContractorCompanies(),
            
            // Inspection statistics
            'inspectionStats' => $this->getInspectionStats(),

            // Average permit duration
            'avgPermitDuration' => $this->getAveragePermitDuration(),
        ];

        return view('reports.index', $data);
    }

    private function getPermitsByStatus()
    {
        return PermitToWork::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->toArray();
    }

    private function getPermitsByArea()
    {
        return PermitToWork::select('areas.name as area_name', DB::raw('count(*) as count'))
            ->join('areas', 'permit_to_works.area_id', '=', 'areas.id')
            ->groupBy('areas.id', 'areas.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPermitsByContractor()
    {
        return PermitToWork::select('receiver_company_name', DB::raw('count(*) as count'))
            ->whereNotNull('receiver_company_name')
            ->where('receiver_company_name', '!=', '')
            ->groupBy('receiver_company_name')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPermitsByDepartment()
    {
        return PermitToWork::select('department', DB::raw('count(*) as count'))
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->groupBy('department')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getTopIssuers()
    {
        return User::select('users.id', 'users.name', 'users.department', DB::raw('count(permit_to_works.id) as permit_count'))
            ->join('permit_to_works', 'users.id', '=', 'permit_to_works.permit_issuer_id')
            ->groupBy('users.id', 'users.name', 'users.department')
            ->orderByDesc('permit_count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getTopContractorCompanies()
    {
        return PermitToWork::select('receiver_company_name', DB::raw('count(*) as permit_count'))
            ->whereNotNull('receiver_company_name')
            ->where('receiver_company_name', '!=', '')
            ->groupBy('receiver_company_name')
            ->orderByDesc('permit_count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getInspectionStats()
    {
        $totalInspections = Inspection::count();

        return [
            'total' => $totalInspections,
            'passed' => 0,
            'failed' => 0,
            'pending' => $totalInspections,
            'passRate' => 0
        ];
    }

    private function getAveragePermitDuration()
    {
        $completedPermits = PermitToWork::where('status', 'completed')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->get();

        if ($completedPermits->count() === 0) {
            return 0;
        }

        $totalDays = 0;
        foreach ($completedPermits as $permit) {
            $totalDays += $permit->start_date->diffInDays($permit->end_date) + 1;
        }

        return round($totalDays / $completedPermits->count(), 1);
    }

    /**
     * Export reports to PDF (optional future feature)
     */
    public function exportPdf()
    {
        // Future implementation
        return redirect()->route('reports.index')->with('info', 'PDF export coming soon!');
    }

    /**
     * Export reports to Excel (optional future feature)
     */
    public function exportExcel()
    {
        // Future implementation
        return redirect()->route('reports.index')->with('info', 'Excel export coming soon!');
    }

    /**
     * API endpoint for work schedule trends
     */
    public function getWorkScheduleTrend(Request $request)
    {
        $period = $request->get('period', 'daily');
        $dateRange = $request->get('range', 30); // default 30 days/weeks/months

        $data = $this->calculateWorkScheduleTrend($period, $dateRange);
        
        return response()->json($data);
    }

    /**
     * Calculate work schedule trend based on permit start_date (work schedule, not creation date)
     */
    private function calculateWorkScheduleTrend($period, $range)
    {
        $now = Carbon::now();
        $labels = [];
        $workCounts = [];
        $companyBreakdown = []; // Company breakdown for tooltips
        $hraCounts = [
            'workAtHeight' => [],
            'hotWork' => [],
            'lotoIsolation' => [],
            'lineBreaking' => [],
            'excavation' => [],
            'confinedSpace' => [],
            'explosiveAtmosphere' => [],
        ];

        switch ($period) {
            case 'daily':
                for ($i = $range - 1; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[] = $date->format('d M');
                    
                    // Count permits with work scheduled on this date
                    $permits = PermitToWork::whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date)
                        ->whereIn('status', ['active', 'completed', 'expired'])
                        ->get();
                    
                    $workCounts[] = $permits->count();
                    
                    // Get company breakdown
                    $companyBreakdown[] = $permits->groupBy('receiver_company_name')
                        ->map(function($group) {
                            return $group->count();
                        })
                        ->sortDesc()
                        ->take(10)
                        ->toArray();
                    
                    // Count HRAs with work scheduled on this date
                    $hraCounts['workAtHeight'][] = HraWorkAtHeight::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['hotWork'][] = HraHotWork::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['lotoIsolation'][] = HraLotoIsolation::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['lineBreaking'][] = HraLineBreaking::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['excavation'][] = HraExcavation::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['confinedSpace'][] = HraConfinedSpace::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                    $hraCounts['explosiveAtmosphere'][] = HraExplosiveAtmosphere::whereDate('start_datetime', '<=', $date)
                        ->whereDate('end_datetime', '>=', $date)
                        ->count();
                }
                break;

            case 'weekly':
                for ($i = $range - 1; $i >= 0; $i--) {
                    $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                    $weekEnd = $now->copy()->subWeeks($i)->endOfWeek();
                    $labels[] = 'Week ' . $weekStart->weekOfYear;
                    
                    // Count permits with work overlapping this week
                    $permits = PermitToWork::where('start_date', '<=', $weekEnd)
                        ->where('end_date', '>=', $weekStart)
                        ->whereIn('status', ['active', 'completed', 'expired'])
                        ->get();
                    
                    $workCounts[] = $permits->count();
                    
                    // Get company breakdown
                    $companyBreakdown[] = $permits->groupBy('receiver_company_name')
                        ->map(function($group) {
                            return $group->count();
                        })
                        ->sortDesc()
                        ->take(10)
                        ->toArray();
                    
                    // Count HRAs with work overlapping this week
                    $hraCounts['workAtHeight'][] = HraWorkAtHeight::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['hotWork'][] = HraHotWork::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['lotoIsolation'][] = HraLotoIsolation::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['lineBreaking'][] = HraLineBreaking::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['excavation'][] = HraExcavation::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['confinedSpace'][] = HraConfinedSpace::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                    $hraCounts['explosiveAtmosphere'][] = HraExplosiveAtmosphere::whereDate('start_datetime', '<=', $weekEnd)
                        ->whereDate('end_datetime', '>=', $weekStart)
                        ->count();
                }
                break;

            case 'monthly':
                for ($i = $range - 1; $i >= 0; $i--) {
                    $monthStart = $now->copy()->subMonths($i)->startOfMonth();
                    $monthEnd = $now->copy()->subMonths($i)->endOfMonth();
                    $labels[] = $monthStart->format('M Y');
                    
                    // Count permits with work overlapping this month
                    $permits = PermitToWork::where('start_date', '<=', $monthEnd)
                        ->where('end_date', '>=', $monthStart)
                        ->whereIn('status', ['active', 'completed', 'expired'])
                        ->get();
                    
                    $workCounts[] = $permits->count();
                    
                    // Get company breakdown
                    $companyBreakdown[] = $permits->groupBy('receiver_company_name')
                        ->map(function($group) {
                            return $group->count();
                        })
                        ->sortDesc()
                        ->take(10)
                        ->toArray();
                    
                    // Count HRAs with work overlapping this month
                    $hraCounts['workAtHeight'][] = HraWorkAtHeight::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['hotWork'][] = HraHotWork::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['lotoIsolation'][] = HraLotoIsolation::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['lineBreaking'][] = HraLineBreaking::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['excavation'][] = HraExcavation::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['confinedSpace'][] = HraConfinedSpace::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                    $hraCounts['explosiveAtmosphere'][] = HraExplosiveAtmosphere::whereDate('start_datetime', '<=', $monthEnd)
                        ->whereDate('end_datetime', '>=', $monthStart)
                        ->count();
                }
                break;

            case 'yearly':
                for ($i = $range - 1; $i >= 0; $i--) {
                    $yearStart = $now->copy()->subYears($i)->startOfYear();
                    $yearEnd = $now->copy()->subYears($i)->endOfYear();
                    $labels[] = $yearStart->format('Y');
                    
                    // Count permits with work overlapping this year
                    $permits = PermitToWork::where('start_date', '<=', $yearEnd)
                        ->where('end_date', '>=', $yearStart)
                        ->whereIn('status', ['active', 'completed', 'expired'])
                        ->get();
                    
                    $workCounts[] = $permits->count();
                    
                    // Get company breakdown
                    $companyBreakdown[] = $permits->groupBy('receiver_company_name')
                        ->map(function($group) {
                            return $group->count();
                        })
                        ->sortDesc()
                        ->take(10)
                        ->toArray();
                    
                    // Count HRAs with work overlapping this year
                    $hraCounts['workAtHeight'][] = HraWorkAtHeight::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['hotWork'][] = HraHotWork::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['lotoIsolation'][] = HraLotoIsolation::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['lineBreaking'][] = HraLineBreaking::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['excavation'][] = HraExcavation::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['confinedSpace'][] = HraConfinedSpace::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                    $hraCounts['explosiveAtmosphere'][] = HraExplosiveAtmosphere::whereDate('start_datetime', '<=', $yearEnd)
                        ->whereDate('end_datetime', '>=', $yearStart)
                        ->count();
                }
                break;
        }

        return [
            'labels' => $labels,
            'workCounts' => $workCounts,
            'companyBreakdown' => $companyBreakdown,
            'hraCounts' => $hraCounts,
            'period' => $period,
            'range' => $range
        ];
    }
}
