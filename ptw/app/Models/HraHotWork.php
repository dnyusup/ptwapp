<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraHotWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'hra_permit_number',
        'permit_to_work_id',
        'permit_number',
        'user_id',
        // Basic Information
        'worker_name',
        'worker_phone',
        'supervisor_name',
        'work_location',
        'start_datetime',
        'end_datetime',
        'work_description',
        'hot_work_avoidable',
        'hot_work_designated_area',
        'status',
        
        // New comprehensive assessment fields
        'flammable_materials_removed',
        'flammable_liquids_removed',
        'flammable_floors_wetted',
        'walls_ceiling_protected',
        'floors_swept_clean',
        'materials_other_side_removed',
        'explosive_atmosphere_removed',
        'wall_floor_openings_covered',
        'ducts_conveyors_protected',
        'fire_risk_prevention_applied',
        'equipment_cleaned_flammable',
        'containers_emptied_cleaned',
        'building_materials_non_flammable',
        'flammable_materials_cut_protected',
        'ventilation_type',
        'ventilation_adequate',
        'gas_lamps_open_area',
        'equipment_installed_monitored',
        'workers_notified',
        // Peralatan Pemadam Api fields
        'apar_air',
        'apar_powder',
        'apar_co2',
        'apar_foam',
        'fire_blanket',
        'fire_watch_officer',
        'fire_watch_name',
        'monitoring_sprinkler',
        'monitoring_combustible',
        'monitoring_distance',
        'additional_inspection_duration',
        // Emergency Systems fields
        'emergency_call_point',
        'sprinkler_system_disabled',
        'fire_detection_isolated',
        'isolation_notified_by',
        'isolation_notified_when',
        'reinstatement_notified_by',
        'reinstatement_notified_when',
        // Approval system fields
        'approval_status',
        'approval_requested_at',
        'area_owner_approval',
        'area_owner_approved_at',
        'area_owner_approved_by',
        'area_owner_comments',
        'ehs_approval',
        'ehs_approved_at',
        'ehs_approved_by',
        'ehs_comments',
        'final_approved_at',
        'area_owner_notified',
        'ehs_notified',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'approval_requested_at' => 'datetime',
        'area_owner_approved_at' => 'datetime',
        'ehs_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'area_owner_notified' => 'boolean',
        'ehs_notified' => 'boolean',
    ];

    /**
     * Generate unique HRA permit number
     */
    public static function generateHraPermitNumber($permitNumber)
    {
        // Get count of existing HRA Hot Work for this permit
        $count = static::where('permit_number', $permitNumber)->count();
        
        // Format: General Permit Number + HRA Type + Sequence
        return $permitNumber . '-HW-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship with PermitToWork
     */
    public function permitToWork()
    {
        return $this->belongsTo(PermitToWork::class, 'permit_to_work_id');
    }

    /**
     * Relationship with User (creator)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with User who approved as area owner
     */
    public function areaOwnerApprovedBy()
    {
        return $this->belongsTo(User::class, 'area_owner_approved_by');
    }

    /**
     * Relationship with User who approved as EHS
     */
    public function ehsApprovedBy()
    {
        return $this->belongsTo(User::class, 'ehs_approved_by');
    }

    /**
     * Relationship with User who rejected the HRA
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Check if HRA can be approved
     */
    public function canBeApproved()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if both approvals are completed
     */
    public function isFullyApproved()
    {
        return $this->area_owner_approval === 'approved' && $this->ehs_approval === 'approved';
    }

    /**
     * Check if any approval is rejected
     */
    public function isRejected()
    {
        return $this->area_owner_approval === 'rejected' || $this->ehs_approval === 'rejected';
    }

    /**
     * Update final approval status based on individual approvals
     */
    public function updateFinalApprovalStatus()
    {
        if ($this->isRejected()) {
            $this->approval_status = 'rejected';
            $this->final_approved_at = null;
        } elseif ($this->isFullyApproved()) {
            $this->approval_status = 'approved';
            $this->final_approved_at = now();
        }
        
        $this->save();
    }
}
