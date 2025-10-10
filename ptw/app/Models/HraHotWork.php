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
        'status',
        // Hot Work Safety Checklist
        'q1_alternative_considered',
        'q2_equipment_checked',
        'q3_flammable_moved',
        'q3_distance',
        'q4_protected_cover',
        'q5_debris_cleaned',
        'q6_area_inspected',
        'q7_flammable_structures',
        'q7_actions_taken',
        'q8_fire_blanket',
        'q9_valve_drain_covered',
        'q10_isolation_ducting',
        'q11_holes_sealed',
        'q12_ventilation_type',
        'q12_natural_ventilation',
        'q12_artificial_ventilation',
        'q12_ventilation_adequate',
        'q13_electrical_protected',
        'q14_equipment_protected',
        'q15_overhead_protection',
        'q16_area_marked',
        'q17_gas_monitoring',
        // Peralatan Pemadam Api fields
        'apar_air',
        'apar_powder',
        'apar_co2',
        'fire_blanket',
        'fire_watch_officer',
        'fire_watch_name',
        'monitoring_sprinkler',
        'monitoring_combustible',
        'monitoring_distance',
        'emergency_call',
        'sprinkler_check',
        'detector_shutdown',
        'notification_required',
        'notification_phone',
        'notification_name',
        'insurance_notification',
        'detector_confirmed_off',
        'gas_monitoring_required',
        // Approval system fields
        'approval_status',
        'approval_requested_at',
        'area_owner_approval',
        'area_owner_approved_at',
        'area_owner_comments',
        'ehs_approval',
        'ehs_approved_at',
        'ehs_comments',
        'final_approved_at',
        'area_owner_notified',
        'ehs_notified',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'approval_requested_at' => 'datetime',
        'area_owner_approved_at' => 'datetime',
        'ehs_approved_at' => 'datetime',
        'final_approved_at' => 'datetime',
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
