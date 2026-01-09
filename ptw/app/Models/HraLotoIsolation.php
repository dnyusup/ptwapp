<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraLotoIsolation extends Model
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
        'status',
        // Pre Isolation
        'pid_reviewed',
        // Electrical Isolation
        'electrical_enabled',
        'electrical_hv_installation',
        'electrical_isolations',
        'electrical_energy_control_method',
        // Mechanical Isolation
        'mechanical_enabled',
        'mechanical_gravitasi',
        'mechanical_hidrolik',
        'mechanical_kelembaman',
        'mechanical_spring',
        'mechanical_pneumatik',
        'mechanical_lainnya',
        'mechanical_isolations',
        'mechanical_energy_control_method',
        // Process Isolation
        'process_enabled',
        'process_isolations',
        'process_energy_control_method',
        // Utility Isolation
        'utility_enabled',
        'utility_isolations',
        'utility_energy_control_method',
        // Verifikasi Isolasi
        'affected_area',
        'all_individuals_informed',
        'individual_lototo_required',
        'ptw_issuer_lototo_key',
        // Line Breaking
        'line_content_before',
        'lb_no_residual_pressure',
        'lb_drain_valve_open',
        'lb_emergency_arrangements',
        'lb_line_isolated',
        'lb_line_empty',
        'lb_line_clean',
        'lb_no_asbestos',
        'lb_pipe_no_support_needed',
        'lb_lototo_drainage',
        'lb_purged_air',
        'lb_purged_water',
        'lb_purged_n2',
        'lb_additional_control',
        // Approval Fields
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
        // Get count of existing HRA LOTO/Isolation for this permit
        $count = static::where('permit_number', $permitNumber)->count();
        
        // Format: General Permit Number + HRA Type + Sequence
        return $permitNumber . '-LOTO-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
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
     * Relationship with Area Owner approver
     */
    public function areaOwnerApprover()
    {
        return $this->belongsTo(User::class, 'area_owner_approved_by');
    }

    /**
     * Relationship with EHS approver
     */
    public function ehsApprover()
    {
        return $this->belongsTo(User::class, 'ehs_approved_by');
    }

    /**
     * Relationship with rejector
     */
    public function rejector()
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
     * Check if HRA is fully approved (only EHS approval required)
     */
    public function isFullyApproved()
    {
        return $this->ehs_approval === 'approved';
    }

    /**
     * Check if HRA is rejected
     */
    public function isRejected()
    {
        return $this->ehs_approval === 'rejected';
    }

    /**
     * Update final approval status
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
