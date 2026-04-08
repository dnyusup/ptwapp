<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermitToWork;
use App\Models\HraHotWork;
use App\Models\HraWorkAtHeight;
use App\Models\HraLotoIsolation;
use App\Models\HraLineBreaking;
use App\Models\HraExcavation;
use App\Models\HraConfinedSpace;
use App\Models\HraExplosiveAtmosphere;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display the task list page
     */
    public function index()
    {
        $currentUser = auth()->user();
        $tasks = $this->getTasksForUser($currentUser);
        $areas = Area::where('is_active', true)->orderBy('name')->get();
        
        return view('tasks.index', compact('tasks', 'areas'));
    }

    /**
     * Get all tasks for a specific user
     */
    public function getTasksForUser($user)
    {
        $tasks = collect();
        
        // EHS Approval Tasks
        if ($user->role === 'bekaert' && $user->department === 'EHS') {
            // Permits pending EHS approval
            $pendingEhsPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
                ->whereIn('status', ['pending_approval', 'resubmitted'])
                ->where('ehs_approval_status', '!=', 'approved')
                ->get()
                ->map(function($permit) {
                    return [
                        'id' => $permit->id,
                        'type' => 'permit',
                        'subtype' => 'ehs_approval',
                        'title' => $permit->work_title,
                        'permit_number' => $permit->permit_number,
                        'description' => 'Permit needs EHS approval',
                        'company' => $permit->receiver_company_name,
                        'location' => $permit->work_location,
                        'area_id' => $permit->area_id,
                        'area_name' => $permit->area->name ?? '',
                        'created_by' => $permit->permitIssuer->name ?? '-',
                        'date' => $permit->created_at,
                        'priority' => 'high',
                        'route' => route('permits.show', $permit->id),
                        'badge_class' => 'bg-warning',
                        'badge_text' => 'Pending EHS Approval'
                    ];
                });
            $tasks = $tasks->merge($pendingEhsPermits);

            // Permits pending extension approval (for EHS to approve)
            $pendingExtensionEhsPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
                ->where('status', 'pending_extension_approval')
                ->get()
                ->map(function($permit) {
                    return [
                        'id' => $permit->id,
                        'type' => 'permit',
                        'subtype' => 'extension_approval',
                        'title' => $permit->work_title,
                        'permit_number' => $permit->permit_number,
                        'description' => 'Extension request needs EHS approval',
                        'company' => $permit->receiver_company_name,
                        'location' => $permit->work_location,
                        'area_id' => $permit->area_id,
                        'area_name' => $permit->area->name ?? '',
                        'created_by' => $permit->permitIssuer->name ?? '-',
                        'date' => $permit->updated_at,
                        'priority' => 'high',
                        'route' => route('permits.show', $permit->id),
                        'badge_class' => 'bg-purple',
                        'badge_text' => 'Extension Approval'
                    ];
                });
            $tasks = $tasks->merge($pendingExtensionEhsPermits);

            // HRAs pending EHS approval (only models with ehs_approval column)
            $hraModels = [
                'HraHotWork' => HraHotWork::class,
                'HraWorkAtHeight' => HraWorkAtHeight::class,
                'HraLotoIsolation' => HraLotoIsolation::class,
            ];

            foreach ($hraModels as $modelName => $modelClass) {
                $pendingHras = $modelClass::with(['permitToWork.area', 'user'])
                    ->where('ehs_approval', 'pending')
                    ->get()
                    ->map(function($hra) use ($modelName) {
                        $hraType = str_replace('Hra', '', $modelName);
                        $hraType = preg_replace('/([a-z])([A-Z])/', '$1 $2', $hraType);
                        
                        // Determine route name and parameter based on model
                        $routeMap = [
                            'HraHotWork' => ['route' => 'hra.hot-works.show', 'param' => 'hraHotWork'],
                            'HraWorkAtHeight' => ['route' => 'hra.work-at-heights.show', 'param' => 'hraWorkAtHeight'],
                            'HraLotoIsolation' => ['route' => 'hra.loto-isolations.show', 'param' => 'hraLotoIsolation'],
                        ];
                        $routeInfo = $routeMap[$modelName] ?? null;
                        $permitId = $hra->permit_to_work_id ?? ($hra->permitToWork->id ?? 0);
                        
                        $hraRoute = $routeInfo 
                            ? route($routeInfo['route'], ['permit' => $permitId, $routeInfo['param'] => $hra->id])
                            : route('permits.show', $permitId);
                        
                        return [
                            'id' => $hra->id,
                            'type' => 'hra',
                            'subtype' => strtolower(str_replace(' ', '_', $hraType)),
                            'title' => 'HRA ' . $hraType . ' - ' . ($hra->permitToWork->work_title ?? $hra->work_description ?? 'N/A'),
                            'permit_number' => $hra->hra_permit_number ?? $hra->permit_number,
                            'description' => 'HRA ' . $hraType . ' needs EHS approval',
                            'company' => $hra->permitToWork->receiver_company_name ?? '-',
                            'location' => $hra->work_location ?? ($hra->permitToWork->work_location ?? '-'),
                            'area_id' => $hra->permitToWork->area_id ?? null,
                            'area_name' => $hra->permitToWork->area->name ?? '',
                            'created_by' => $hra->user->name ?? '-',
                            'date' => $hra->created_at,
                            'priority' => 'high',
                            'route' => $hraRoute,
                            'badge_class' => 'bg-info',
                            'badge_text' => 'HRA Pending Approval'
                        ];
                    });
                $tasks = $tasks->merge($pendingHras);
            }
        }

        // Location Owner Approval Tasks
        $pendingLocationOwnerPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
            ->whereIn('status', ['pending_approval', 'resubmitted'])
            ->where('location_owner_as_approver', true)
            ->where('location_owner_id', $user->id)
            ->where('location_owner_approval_status', '!=', 'approved')
            ->get()
            ->map(function($permit) {
                return [
                    'id' => $permit->id,
                    'type' => 'permit',
                    'subtype' => 'location_owner_approval',
                    'title' => $permit->work_title,
                    'permit_number' => $permit->permit_number,
                    'description' => 'Permit needs Location Owner approval',
                    'company' => $permit->receiver_company_name,
                    'location' => $permit->work_location,
                    'area_id' => $permit->area_id,
                    'area_name' => $permit->area->name ?? '',
                    'created_by' => $permit->permitIssuer->name ?? '-',
                    'date' => $permit->created_at,
                    'priority' => 'high',
                    'route' => route('permits.show', $permit->id),
                    'badge_class' => 'bg-purple',
                    'badge_text' => 'Pending Location Owner'
                ];
            });
        $tasks = $tasks->merge($pendingLocationOwnerPermits);

        // Permit Issuer - Rejected permits that need action
        $rejectedPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
            ->where('status', 'rejected')
            ->where('permit_issuer_id', $user->id)
            ->get()
            ->map(function($permit) {
                return [
                    'id' => $permit->id,
                    'type' => 'permit',
                    'subtype' => 'rejected',
                    'title' => $permit->work_title,
                    'permit_number' => $permit->permit_number,
                    'description' => 'Permit was rejected - needs revision',
                    'company' => $permit->receiver_company_name,
                    'location' => $permit->work_location,
                    'area_id' => $permit->area_id,
                    'area_name' => $permit->area->name ?? '',
                    'created_by' => $permit->permitIssuer->name ?? '-',
                    'date' => $permit->updated_at,
                    'priority' => 'medium',
                    'route' => route('permits.show', $permit->id),
                    'badge_class' => 'bg-danger',
                    'badge_text' => 'Rejected'
                ];
            });
        $tasks = $tasks->merge($rejectedPermits);

        // Permit Issuer - Expired permits that need action (complete or extend)
        $expiredPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
            ->where('status', 'expired')
            ->where('permit_issuer_id', $user->id)
            ->get()
            ->map(function($permit) {
                return [
                    'id' => $permit->id,
                    'type' => 'permit',
                    'subtype' => 'expired',
                    'title' => $permit->work_title,
                    'permit_number' => $permit->permit_number,
                    'description' => 'Permit has expired - needs to be completed or extended',
                    'company' => $permit->receiver_company_name,
                    'location' => $permit->work_location,
                    'area_id' => $permit->area_id,
                    'area_name' => $permit->area->name ?? '',
                    'created_by' => $permit->permitIssuer->name ?? '-',
                    'date' => $permit->end_date,
                    'priority' => 'medium',
                    'route' => route('permits.show', $permit->id),
                    'badge_class' => 'bg-secondary',
                    'badge_text' => 'Expired'
                ];
            });
        $tasks = $tasks->merge($expiredPermits);

        // Permit Issuer - Pending Extension Approval
        $pendingExtensionPermits = PermitToWork::with(['permitIssuer', 'receiver', 'area'])
            ->where('status', 'pending_extension_approval')
            ->where('permit_issuer_id', $user->id)
            ->get()
            ->map(function($permit) {
                return [
                    'id' => $permit->id,
                    'type' => 'permit',
                    'subtype' => 'pending_extension',
                    'title' => $permit->work_title,
                    'permit_number' => $permit->permit_number,
                    'description' => 'Extension request pending approval',
                    'company' => $permit->receiver_company_name,
                    'location' => $permit->work_location,
                    'area_id' => $permit->area_id,
                    'area_name' => $permit->area->name ?? '',
                    'created_by' => $permit->permitIssuer->name ?? '-',
                    'date' => $permit->updated_at,
                    'priority' => 'medium',
                    'route' => route('permits.show', $permit->id),
                    'badge_class' => 'bg-warning',
                    'badge_text' => 'Pending Extension'
                ];
            });
        $tasks = $tasks->merge($pendingExtensionPermits);

        // HRA Creator - Rejected HRAs that need revision
        $rejectedHraModels = [
            'HraHotWork' => HraHotWork::class,
            'HraWorkAtHeight' => HraWorkAtHeight::class,
            'HraLotoIsolation' => HraLotoIsolation::class,
        ];

        foreach ($rejectedHraModels as $modelName => $modelClass) {
            $rejectedHras = $modelClass::with(['permitToWork.area', 'user'])
                ->where('ehs_approval', 'rejected')
                ->where('user_id', $user->id)
                ->get()
                ->map(function($hra) use ($modelName) {
                    $hraType = str_replace('Hra', '', $modelName);
                    $hraType = preg_replace('/([a-z])([A-Z])/', '$1 $2', $hraType);
                    
                    // Determine route name and parameter based on model
                    $routeMap = [
                        'HraHotWork' => ['route' => 'hra.hot-works.show', 'param' => 'hraHotWork'],
                        'HraWorkAtHeight' => ['route' => 'hra.work-at-heights.show', 'param' => 'hraWorkAtHeight'],
                        'HraLotoIsolation' => ['route' => 'hra.loto-isolations.show', 'param' => 'hraLotoIsolation'],
                    ];
                    $routeInfo = $routeMap[$modelName] ?? null;
                    $permitId = $hra->permit_to_work_id ?? ($hra->permitToWork->id ?? 0);
                    
                    $hraRoute = $routeInfo 
                        ? route($routeInfo['route'], ['permit' => $permitId, $routeInfo['param'] => $hra->id])
                        : route('permits.show', $permitId);
                    
                    return [
                        'id' => $hra->id,
                        'type' => 'hra',
                        'subtype' => 'rejected',
                        'title' => 'HRA ' . $hraType . ' - ' . ($hra->permitToWork->work_title ?? $hra->work_description ?? 'N/A'),
                        'permit_number' => $hra->hra_permit_number ?? $hra->permit_number,
                        'description' => 'HRA was rejected by EHS - needs revision',
                        'company' => $hra->permitToWork->receiver_company_name ?? '-',
                        'location' => $hra->work_location ?? ($hra->permitToWork->work_location ?? '-'),
                        'area_id' => $hra->permitToWork->area_id ?? null,
                        'area_name' => $hra->permitToWork->area->name ?? '',
                        'created_by' => $hra->user->name ?? '-',
                        'date' => $hra->updated_at,
                        'priority' => 'high',
                        'route' => $hraRoute,
                        'badge_class' => 'bg-danger',
                        'badge_text' => 'HRA Rejected'
                    ];
                });
            $tasks = $tasks->merge($rejectedHras);
        }

        // Sort by date descending
        return $tasks->sortByDesc('date')->values();
    }

    /**
     * Get task count for current user (for badge in sidebar)
     */
    public static function getTaskCount($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        if (!$user) {
            return 0;
        }

        $count = 0;

        // EHS Approval Tasks
        if ($user->role === 'bekaert' && $user->department === 'EHS') {
            // Permits pending EHS approval
            $count += PermitToWork::whereIn('status', ['pending_approval', 'resubmitted'])
                ->where('ehs_approval_status', '!=', 'approved')
                ->count();

            // Permits pending extension approval
            $count += PermitToWork::where('status', 'pending_extension_approval')->count();

            // HRAs pending EHS approval (only models with ehs_approval column)
            $hraModels = [
                HraHotWork::class,
                HraWorkAtHeight::class,
                HraLotoIsolation::class,
            ];

            foreach ($hraModels as $modelClass) {
                $count += $modelClass::where('ehs_approval', 'pending')->count();
            }
        }

        // Location Owner Approval Tasks
        $count += PermitToWork::whereIn('status', ['pending_approval', 'resubmitted'])
            ->where('location_owner_as_approver', true)
            ->where('location_owner_id', $user->id)
            ->where('location_owner_approval_status', '!=', 'approved')
            ->count();

        // Permit Issuer - Rejected permits
        $count += PermitToWork::where('status', 'rejected')
            ->where('permit_issuer_id', $user->id)
            ->count();

        // Permit Issuer - Expired permits
        $count += PermitToWork::where('status', 'expired')
            ->where('permit_issuer_id', $user->id)
            ->count();

        // HRA Creator - Rejected HRAs
        $rejectedHraModels = [
            HraHotWork::class,
            HraWorkAtHeight::class,
            HraLotoIsolation::class,
        ];

        foreach ($rejectedHraModels as $modelClass) {
            $count += $modelClass::where('ehs_approval', 'rejected')
                ->where('user_id', $user->id)
                ->count();
        }

        return $count;
    }
}
