<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use App\Models\User;
use App\Models\Area;
use App\Models\KontraktorList;
use App\Models\Inspection;
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
            'permitsByMonth' => $this->getPermitsByMonth(),
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

            // Trend data
            'weeklyTrend' => $this->getWeeklyTrend(),
            'dailyActivity' => $this->getDailyActivity(),

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

    private function getPermitsByMonth()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = PermitToWork::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $months[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $months;
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

    private function getWeeklyTrend()
    {
        $weeks = [];
        for ($i = 7; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $weeks[] = [
                'week' => $weekStart->format('d M'),
                'created' => PermitToWork::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'completed' => PermitToWork::where('status', 'completed')
                    ->whereBetween('completed_at', [$weekStart, $weekEnd])
                    ->count(),
            ];
        }
        return $weeks;
    }

    private function getDailyActivity()
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = [
                'day' => $date->format('D'),
                'date' => $date->format('d M'),
                'count' => PermitToWork::whereDate('created_at', $date)->count()
            ];
        }
        return $days;
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
}
