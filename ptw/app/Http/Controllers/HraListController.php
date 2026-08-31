<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\HraConfinedSpace;
use App\Models\HraExcavation;
use App\Models\HraExplosiveAtmosphere;
use App\Models\HraHotWork;
use App\Models\HraLineBreaking;
use App\Models\HraLotoIsolation;
use App\Models\HraWorkAtHeight;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class HraListController extends Controller
{
    /**
     * Registry of every HRA type: model class, display label, icon,
     * and the "show" route (name + parameter key) used inside a permit.
     */
    private function hraTypes(): array
    {
        return [
            'work-at-heights' => [
                'model'       => HraWorkAtHeight::class,
                'label'       => 'Work at Height',
                'icon'        => 'fas fa-hard-hat',
                'route'       => 'hra.work-at-heights.show',
                'param'       => 'hraWorkAtHeight',
                'hasApproval' => true,
            ],
            'hot-works' => [
                'model'       => HraHotWork::class,
                'label'       => 'Hot Work',
                'icon'        => 'fas fa-fire',
                'route'       => 'hra.hot-works.show',
                'param'       => 'hraHotWork',
                'hasApproval' => true,
            ],
            'loto-isolations' => [
                'model'       => HraLotoIsolation::class,
                'label'       => 'LOTO / Isolation',
                'icon'        => 'fas fa-lock',
                'route'       => 'hra.loto-isolations.show',
                'param'       => 'hraLotoIsolation',
                'hasApproval' => true,
            ],
            'line-breakings' => [
                'model'       => HraLineBreaking::class,
                'label'       => 'Line Breaking',
                'icon'        => 'fas fa-grip-lines',
                'route'       => 'hra.line-breakings.show',
                'param'       => 'hraLineBreaking',
                'hasApproval' => false,
            ],
            'excavations' => [
                'model'       => HraExcavation::class,
                'label'       => 'Excavation',
                'icon'        => 'fas fa-person-digging',
                'route'       => 'hra.excavations.show',
                'param'       => 'hraExcavation',
                'hasApproval' => false,
            ],
            'confined-spaces' => [
                'model'       => HraConfinedSpace::class,
                'label'       => 'Confined Space',
                'icon'        => 'fas fa-box',
                'route'       => 'hra.confined-spaces.show',
                'param'       => 'hraConfinedSpace',
                'hasApproval' => false,
            ],
            'explosive-atmospheres' => [
                'model'       => HraExplosiveAtmosphere::class,
                'label'       => 'Explosive Atmosphere',
                'icon'        => 'fas fa-bomb',
                'route'       => 'hra.explosive-atmospheres.show',
                'param'       => 'hraExplosiveAtmosphere',
                'hasApproval' => false,
            ],
        ];
    }

    /**
     * Who may open the HRA list at all: Administrator, any Bekaert user, or a
     * contractor that belongs to a company. What each of them actually SEES is
     * narrowed later by scopeForUser().
     */
    private function authorizeAccess(): void
    {
        $user = auth()->user();

        $allowed = $user->role === 'administrator'
            || $user->role === 'bekaert'
            || ($user->role === 'contractor' && $user->company_id);

        if (!$allowed) {
            abort(403, 'You are not allowed to access the HRA list.');
        }
    }

    private function isAdmin(): bool
    {
        return auth()->user()->role === 'administrator';
    }

    /**
     * Full access (see everything, and manage/cancel/delete): Administrator and Bekaert EHS.
     */
    private function canManage(): bool
    {
        $user = auth()->user();

        return $user->role === 'administrator'
            || ($user->role === 'bekaert' && $user->department === 'EHS');
    }

    /**
     * Narrow an HRA query to what the current user is allowed to see.
     *  - Admin / Bekaert EHS: everything.
     *  - Other Bekaert users: only permits where they are the issuer / responsible.
     *  - Contractors: only permits for their own company.
     */
    private function scopeForUser($query)
    {
        if ($this->canManage()) {
            return $query;
        }

        $user = auth()->user();

        if ($user->role === 'contractor') {
            $companyName = $user->company->company_name ?? null;

            return $query->whereHas('permitToWork', fn ($q) => $companyName
                ? $q->where('receiver_company_name', $companyName)
                : $q->whereRaw('1 = 0'));
        }

        // Bekaert (non-EHS)
        return $query->whereHas('permitToWork', function ($q) use ($user) {
            $q->where('permit_issuer_id', $user->id)
                ->orWhere('responsible_person_email', $user->email);
        });
    }

    /**
     * Display a unified listing of every HRA across all permits.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        $items = $this->filteredItems($request);

        // Summary widgets — computed from the full filtered set (before pagination),
        // so they stay in sync with whatever filters are active on the table.
        $summary = [
            'total'    => $items->count(),
            'byStatus' => $items->groupBy('status_label')->map->count()->sortDesc(),
            'byType'   => $items->groupBy('type_label')->map->count()->sortDesc(),
            'byArea'   => $items->groupBy('area')->map->count()->sortDesc(),
        ];

        // Manual pagination over the merged collection.
        $perPage = 20;
        $page    = Paginator::resolveCurrentPage('page');
        $hras    = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        $areas      = Area::where('is_active', true)->orderBy('name')->get();
        $typeList   = collect($this->hraTypes())->map(fn ($c, $k) => ['key' => $k, 'label' => $c['label']])->values();
        $canManage  = $this->canManage();

        return view('hras.index', compact('hras', 'areas', 'typeList', 'summary', 'canManage'));
    }

    /**
     * Download the current (filtered + scoped) HRA list as a spreadsheet.
     */
    public function export(Request $request)
    {
        $this->authorizeAccess();

        $rows = $this->filteredItems($request)->map(function ($i) {
            $findingStyle = match ($i['status_label']) {
                'Approved', 'Completed', 'Active' => 'ok',
                'Rejected', 'Cancelled', 'No Inspected' => 'nok',
                default => '',
            };

            return [
                ['String', $i['hra_permit_number']],
                ['String', $i['type_label']],
                ['String', $i['permit_number']],
                ['String', $i['work_title']],
                ['String', $i['company']],
                ['String', $i['area']],
                ['String', $i['location']],
                ['String', $i['worker_name']],
                ['String', $i['start_datetime'] ? \Carbon\Carbon::parse($i['start_datetime'])->format('d/m/Y H:i') : '-'],
                ['String', $i['end_datetime'] ? \Carbon\Carbon::parse($i['end_datetime'])->format('d/m/Y H:i') : '-'],
                ['String', $i['status_label'], $findingStyle],
                ['String', $i['created_by']],
                ['String', $i['created_at'] ? \Carbon\Carbon::parse($i['created_at'])->format('d/m/Y H:i') : '-'],
            ];
        })->all();

        $headers = ['HRA Number', 'Type', 'Permit', 'Work Title', 'Company', 'Area', 'Location',
                    'Worker', 'Start', 'End', 'Status', 'Created By', 'Created At'];

        return $this->spreadsheetDownload('HRA', $headers, $rows, 'hra_export');
    }

    /**
     * Cancel a single HRA (only allowed while it is Draft or Pending Approval).
     */
    public function cancel(Request $request, string $type, int $id)
    {
        $this->authorizeAccess();
        abort_unless($this->canManage(), 403, 'You are not allowed to cancel HRA.');
        abort_if(!$this->resolveModel($type), 404);

        $cancelled = $this->cancelEligible($type, [$id]);

        return back()->with('success', $cancelled
            ? 'HRA has been cancelled.'
            : 'This HRA cannot be cancelled (only Draft or Pending Approval can be).');
    }

    /**
     * Delete a single HRA (Administrator only).
     */
    public function destroy(Request $request, string $type, int $id)
    {
        $this->authorizeAccess();
        abort_unless($this->isAdmin(), 403, 'Only administrators can delete HRA.');

        $model = $this->resolveModel($type);
        abort_if(!$model, 404);

        $model::where('id', $id)->delete();

        return back()->with('success', 'HRA has been deleted.');
    }

    /**
     * Bulk cancel — either an explicit selection or every HRA matching the filters.
     */
    public function bulkCancel(Request $request)
    {
        $this->authorizeAccess();
        abort_unless($this->canManage(), 403, 'You are not allowed to cancel HRA.');

        $grouped = $this->resolveBulkTargets($request);
        $total = 0;

        foreach ($grouped as $type => $ids) {
            $total += $this->cancelEligible($type, $ids);
        }

        return back()->with('success', $total > 0
            ? "{$total} HRA(s) have been cancelled."
            : 'No HRA was cancelled (only Draft or Pending Approval can be).');
    }

    /**
     * Bulk delete — Administrator only.
     */
    public function bulkDelete(Request $request)
    {
        $this->authorizeAccess();
        abort_unless($this->isAdmin(), 403, 'Only administrators can delete HRA.');

        $grouped = $this->resolveBulkTargets($request);
        $total = 0;

        foreach ($grouped as $type => $ids) {
            $model = $this->resolveModel($type);
            if (!$model) {
                continue;
            }
            $total += $model::whereIn('id', $ids)->delete();
        }

        return back()->with('success', $total > 0
            ? "{$total} HRA(s) have been deleted."
            : 'No HRA was deleted.');
    }

    /**
     * Resolve which HRAs a bulk action targets, grouped as [type_key => [ids]].
     *
     * scope=all  -> re-run the current filters and target the whole result set.
     * otherwise  -> use the "type_key:id" tokens the user ticked.
     */
    private function resolveBulkTargets(Request $request): array
    {
        if ($request->get('scope') === 'all') {
            return $this->filteredItems($request)
                ->groupBy('type_key')
                ->map(fn ($rows) => $rows->pluck('id')->all())
                ->all();
        }

        $grouped = [];
        foreach ((array) $request->get('selected', []) as $token) {
            if (!str_contains((string) $token, ':')) {
                continue;
            }
            [$type, $id] = explode(':', $token, 2);
            if (!is_numeric($id) || !$this->resolveModel($type)) {
                continue;
            }
            $grouped[$type][] = (int) $id;
        }

        return $grouped;
    }

    private function resolveModel(string $type): ?string
    {
        return $this->hraTypes()[$type]['model'] ?? null;
    }

    /**
     * Cancel the given HRA ids of one type, but only those currently
     * Draft or Pending Approval. Returns how many were actually cancelled.
     */
    private function cancelEligible(string $type, array $ids): int
    {
        $config = $this->hraTypes()[$type] ?? null;
        if (!$config || empty($ids)) {
            return 0;
        }
        $model = $config['model'];

        $eligible = $model::whereIn('id', $ids)->get()
            ->filter(function ($row) use ($config) {
                $approval = $config['hasApproval'] ? ($row->ehs_approval ?? null) : null;

                return in_array(
                    $this->statusLabel($approval, $row->status ?? null),
                    ['Draft', 'Pending Approval'],
                    true
                );
            })
            ->pluck('id')
            ->all();

        if (empty($eligible)) {
            return 0;
        }

        $payload = ['status' => 'cancelled'];
        if ($config['hasApproval']) {
            // clear the pending EHS approval so the row reads as Cancelled
            $payload['ehs_approval'] = null;
        }

        return $model::whereIn('id', $eligible)->update($payload);
    }

    /**
     * Build the merged, filtered, sorted collection of HRA rows.
     * Shared by index() and the bulk actions so "select all filtered" is exact.
     */
    private function filteredItems(Request $request): Collection
    {
        $search   = trim((string) $request->get('search'));
        $type     = $request->get('type');
        $status   = $request->get('status');
        $areaId   = $request->get('area');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        $items = collect();

        foreach ($this->hraTypes() as $key => $config) {
            // Skip whole model if a type filter is set and doesn't match.
            if ($type && $type !== $key) {
                continue;
            }

            $query = $config['model']::query()
                ->with([
                    'permitToWork:id,permit_number,work_title,receiver_company_name,area_id,work_location',
                    'permitToWork.area:id,name',
                    'user:id,name',
                ]);

            // Hot Work has a post-approval "Waiting Inspection" state.
            if ($key === 'hot-works') {
                $query->with('inspections');
            }

            // Restrict to what this user is allowed to see.
            $this->scopeForUser($query);

            if ($areaId) {
                $query->whereHas('permitToWork', fn ($q) => $q->where('area_id', $areaId));
            }

            if ($dateFrom) {
                $query->whereDate('end_datetime', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('start_datetime', '<=', $dateTo);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('hra_permit_number', 'like', "%{$search}%")
                        ->orWhere('permit_number', 'like', "%{$search}%")
                        ->orWhere('worker_name', 'like', "%{$search}%")
                        ->orWhere('supervisor_name', 'like', "%{$search}%")
                        ->orWhere('work_location', 'like', "%{$search}%")
                        ->orWhereHas('permitToWork', fn ($p) => $p->where('work_title', 'like', "%{$search}%")
                            ->orWhere('permit_number', 'like', "%{$search}%"));
                });
            }

            $query->latest()->get()->each(function ($hra) use ($config, $key, &$items) {
                $permit = $hra->permitToWork;

                $approval = $config['hasApproval'] ? ($hra->ehs_approval ?? null) : null;

                $statusLabel = $this->statusLabel($approval, $hra->status ?? null);

                // Hot Work: approved but mandatory inspections still outstanding.
                if ($key === 'hot-works' && method_exists($hra, 'displayStatus')) {
                    $ds = $hra->displayStatus();
                    if (in_array($ds, ['Waiting Inspection', 'No Inspected'], true)) {
                        $statusLabel = $ds;
                    }
                }

                $items->push([
                    'type_key'          => $key,
                    'type_label'        => $config['label'],
                    'type_icon'         => $config['icon'],
                    'id'                => $hra->id,
                    'hra_permit_number' => $hra->hra_permit_number ?: ('#' . $hra->id),
                    'permit_id'         => $permit->id ?? null,
                    'permit_number'     => $permit->permit_number ?? ($hra->permit_number ?? '-'),
                    'work_title'        => $permit->work_title ?? '-',
                    'company'           => $permit->receiver_company_name ?? '-',
                    'area'              => $permit->area->name ?? 'No Area',
                    'location'          => $hra->work_location ?: ($permit->work_location ?? '-'),
                    'worker_name'       => $hra->worker_name ?: '-',
                    'start_datetime'    => $hra->start_datetime,
                    'end_datetime'      => $hra->end_datetime,
                    'status'            => $hra->status ?? null,
                    'approval'          => $approval,
                    'status_label'      => $statusLabel,
                    'created_by'        => $hra->user->name ?? '-',
                    'created_at'        => $hra->created_at,
                    'show_url'          => ($permit && $config['route'])
                        ? route($config['route'], ['permit' => $permit->id, $config['param'] => $hra->id])
                        : null,
                ]);
            });
        }

        // Status filter — applied here (not at the DB level) so it matches the exact
        // label rendered in the table, regardless of EHS approval vs. base status.
        if ($status) {
            $wantedLabel = [
                'pending'             => 'Pending Approval',
                'approved'            => 'Approved',
                'rejected'           => 'Rejected',
                'draft'              => 'Draft',
                'active'             => 'Active',
                'completed'          => 'Completed',
                'cancelled'          => 'Cancelled',
                'waiting_inspection' => 'Waiting Inspection',
                'no_inspected'       => 'No Inspected',
            ][$status] ?? ucfirst($status);

            $items = $items->filter(fn ($i) => $i['status_label'] === $wantedLabel);
        }

        return $items->sortByDesc('created_at')->values();
    }

    /**
     * Normalize an HRA's approval + base status into one display label.
     */
    private function statusLabel(?string $approval, ?string $status): string
    {
        if ($approval === 'approved') {
            return 'Approved';
        }
        if ($approval === 'pending') {
            return 'Pending Approval';
        }
        if ($approval === 'rejected') {
            return 'Rejected';
        }

        return match ($status) {
            'completed' => 'Completed',
            'active'    => 'Active',
            'cancelled' => 'Cancelled',
            null, ''    => 'Draft',
            default     => ucfirst($status),
        };
    }
}
