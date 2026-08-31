<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Inspection;
use App\Models\PermitToWork;
use Illuminate\Http\Request;

class InspectionListController extends Controller
{
    /**
     * Categories offered in the main-permit inspection form.
     */
    private array $categories = [
        'Kepatuhan APD',
        'Kebersihan/Penempatan Barang/Akses/5R',
        'Kepatuhan standar Hotwork',
        'Kepatuhan standar WaH',
        'Kepatuhan standar LOTOTO',
        'Bahan Kimia',
        'Lain-lain',
    ];

    /**
     * Who may open the inspection list: Administrator, any Bekaert user, or a
     * contractor that belongs to a company. What each of them SEES is narrowed
     * by scopeForUser() / scopePermits().
     */
    private function authorizeAccess(): void
    {
        $user = auth()->user();

        $allowed = $user->role === 'administrator'
            || $user->role === 'bekaert'
            || ($user->role === 'contractor' && $user->company_id);

        if (!$allowed) {
            abort(403, 'You are not allowed to access the inspection list.');
        }
    }

    /**
     * Full visibility: Administrator and Bekaert EHS.
     */
    private function canSeeAll(): bool
    {
        $user = auth()->user();

        return $user->role === 'administrator'
            || ($user->role === 'bekaert' && $user->department === 'EHS');
    }

    /**
     * Narrow an Inspection query to what the current user may see.
     *  - Admin / Bekaert EHS: everything.
     *  - Other Bekaert users: only permits where they are the issuer / responsible.
     *  - Contractors: only permits for their own company.
     */
    private function scopeForUser($query)
    {
        if ($this->canSeeAll()) {
            return $query;
        }

        $user = auth()->user();

        if ($user->role === 'contractor') {
            $companyName = $user->company->company_name ?? null;

            return $query->whereHas('permit', fn ($q) => $companyName
                ? $q->where('receiver_company_name', $companyName)
                : $q->whereRaw('1 = 0'));
        }

        return $query->whereHas('permit', function ($q) use ($user) {
            $q->where('permit_issuer_id', $user->id)
                ->orWhere('responsible_person_email', $user->email);
        });
    }

    /**
     * Same visibility rule, applied directly to a PermitToWork query
     * (used to build the company dropdown).
     */
    private function scopePermits($query)
    {
        if ($this->canSeeAll()) {
            return $query;
        }

        $user = auth()->user();

        if ($user->role === 'contractor') {
            $companyName = $user->company->company_name ?? null;

            return $companyName
                ? $query->where('receiver_company_name', $companyName)
                : $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user) {
            $q->where('permit_issuer_id', $user->id)
                ->orWhere('responsible_person_email', $user->email);
        });
    }

    /**
     * The scoped + filtered Inspection query (no pagination), shared by
     * index() and export().
     */
    private function filteredQuery(Request $request)
    {
        $query = Inspection::query()->with([
            'permit:id,permit_number,work_title,receiver_company_name,area_id',
            'permit.area:id,name',
        ]);

        $this->scopeForUser($query);

        if ($request->filled('finding_type')) {
            $query->where('finding_type', $request->get('finding_type'));
        }
        if ($request->filled('category')) {
            $query->where('inspection_category', $request->get('category'));
        }
        if ($request->filled('area')) {
            $query->whereHas('permit', fn ($q) => $q->where('area_id', $request->get('area')));
        }
        if ($request->filled('company')) {
            $query->whereHas('permit', fn ($q) => $q->where('receiver_company_name', $request->get('company')));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }
        if ($request->filled('search')) {
            $s = $request->get('search');
            $query->where(function ($q) use ($s) {
                $q->where('permit_number', 'like', "%{$s}%")
                    ->orWhere('inspector_name', 'like', "%{$s}%")
                    ->orWhere('inspector_email', 'like', "%{$s}%")
                    ->orWhere('findings', 'like', "%{$s}%")
                    ->orWhereHas('permit', fn ($p) => $p->where('work_title', 'like', "%{$s}%"));
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $this->authorizeAccess();

        $query = $this->filteredQuery($request);

        // Summary from the full filtered set (before pagination).
        $all = (clone $query)->get();
        $summary = [
            'total'      => $all->count(),
            'nok'        => $all->where('finding_type', 'NOK')->count(),
            'byFinding'  => $all->groupBy(fn ($i) => $i->finding_type ?: 'N/A')->map->count()->sortDesc(),
            'byCategory' => $all->groupBy(fn ($i) => $i->inspection_category ?: 'Lain-lain')->map->count()->sortDesc(),
            'byArea'     => $all->groupBy(fn ($i) => $i->permit->area->name ?? 'No Area')->map->count()->sortDesc(),
        ];

        $inspections = $query->latest()->paginate(20)->withQueryString();

        $areas = Area::where('is_active', true)->orderBy('name')->get();

        $companies = $this->scopePermits(PermitToWork::query())
            ->whereNotNull('receiver_company_name')
            ->where('receiver_company_name', '!=', '')
            ->distinct()
            ->orderBy('receiver_company_name')
            ->pluck('receiver_company_name');

        $categories = $this->categories;

        return view('inspections.list', compact('inspections', 'summary', 'areas', 'companies', 'categories'));
    }

    /**
     * Download the current (filtered + scoped) inspection list as a spreadsheet.
     */
    public function export(Request $request)
    {
        $this->authorizeAccess();

        $rows = $this->filteredQuery($request)->latest()->get()->map(function ($i) {
            $findingStyle = $i->finding_type === 'OK' ? 'ok' : ($i->finding_type === 'NOK' ? 'nok' : '');

            return [
                ['String', $i->created_at ? $i->created_at->format('d/m/Y H:i') : '-'],
                ['String', $i->permit_number],
                ['String', $i->permit->work_title ?? '-'],
                ['String', $i->permit->receiver_company_name ?? '-'],
                ['String', $i->permit->area->name ?? 'No Area'],
                ['String', $i->inspector_name],
                ['String', $i->inspector_email],
                ['String', $i->inspection_category ?: '-'],
                ['String', $i->finding_type ?: '-', $findingStyle],
                ['String', $i->findings],
            ];
        })->all();

        $headers = ['Date & Time', 'Permit', 'Work Title', 'Company', 'Area',
                    'Inspector', 'Inspector Email', 'Category', 'Finding', 'Findings'];

        return $this->spreadsheetDownload('Inspections', $headers, $rows, 'inspections_export');
    }
}
