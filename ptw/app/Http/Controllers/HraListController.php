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
     * Display a unified listing of every HRA across all permits.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Only Administrator and Bekaert EHS may access the HRA list.
        if ($currentUser->role !== 'administrator'
            && !($currentUser->role === 'bekaert' && $currentUser->department === 'EHS')) {
            abort(403, 'You are not allowed to access the HRA list.');
        }

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

            if ($areaId) {
                $query->whereHas('permitToWork', fn ($q) => $q->where('area_id', $areaId));
            }

            // NOTE: status is filtered later on the merged collection using the same
            // normalized label the table badge shows, so approval status (EHS) and the
            // base status column never disagree with what the user filtered on.

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
                    'status_label'      => $this->statusLabel($approval, $hra->status ?? null),
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
                'pending'   => 'Pending Approval',
                'approved'  => 'Approved',
                'rejected'  => 'Rejected',
                'draft'     => 'Draft',
                'active'    => 'Active',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ][$status] ?? ucfirst($status);

            $items = $items->filter(fn ($i) => $i['status_label'] === $wantedLabel);
        }

        $items = $items->sortByDesc('created_at')->values();

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

        $areas     = Area::where('is_active', true)->orderBy('name')->get();
        $typeList  = collect($this->hraTypes())->map(fn ($c, $k) => ['key' => $k, 'label' => $c['label']])->values();

        return view('hras.index', compact('hras', 'areas', 'typeList', 'summary'));
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
