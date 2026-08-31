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

        // Contractors only see HRAs that belong to their company's permits.
        $companyName = null;
        if ($currentUser->role === 'contractor' && $currentUser->company_id) {
            $companyName = $currentUser->company->company_name ?? null;
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
                ->with(['permitToWork:id,permit_number,work_title,receiver_company_name,area_id,work_location', 'user:id,name']);

            if ($companyName) {
                $query->whereHas('permitToWork', fn ($q) => $q->where('receiver_company_name', $companyName));
            }

            if ($areaId) {
                $query->whereHas('permitToWork', fn ($q) => $q->where('area_id', $areaId));
            }

            if ($status) {
                if ($config['hasApproval'] && in_array($status, ['pending', 'approved', 'rejected'], true)) {
                    $query->where('ehs_approval', $status);
                } else {
                    $query->where('status', $status);
                }
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
                    'location'          => $hra->work_location ?: ($permit->work_location ?? '-'),
                    'worker_name'       => $hra->worker_name ?: '-',
                    'start_datetime'    => $hra->start_datetime,
                    'end_datetime'      => $hra->end_datetime,
                    'status'            => $hra->status ?? null,
                    'approval'          => $approval,
                    'created_by'        => $hra->user->name ?? '-',
                    'created_at'        => $hra->created_at,
                    'show_url'          => ($permit && $config['route'])
                        ? route($config['route'], ['permit' => $permit->id, $config['param'] => $hra->id])
                        : null,
                ]);
            });
        }

        $items = $items->sortByDesc('created_at')->values();

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

        return view('hras.index', compact('hras', 'areas', 'typeList'));
    }
}
