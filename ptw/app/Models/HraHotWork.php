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
        'work_area_photo',
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
        
        // Additional fields for hot work assessment 
        'drainage_sealed',
        'openings_sealed', 
        'conveyor_belts_stopped',
        'q3_distance',
        'q3_flammable_moved',
        'fire_alarm_disabled',
        'q12_ventilation_type',
        'q12_ventilation_adequate',
        'fire_watch_assigned',
        'fire_extinguisher_available', 
        'water_hose_available',
        'welding_machine_grounded',
        'gas_cylinders_secured',
        'hoses_cables_good_condition',
        'hot_work_equipment_inspected',
        'torch_shutoff_valves_working',
        'weather_conditions_suitable',
        'area_cleared_personnel',
        'additional_notes',
        
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
        'created_via',
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

    /** Minutes that must elapse between one inspection and the next. */
    public const INSPECTION_INTERVAL_MINUTES = 30;

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
     * Inspections recorded against this HRA (max 4, ordered by sequence).
     */
    public function inspections()
    {
        return $this->hasMany(HraHotWorkInspection::class, 'hra_hot_work_id')->orderBy('sequence');
    }

    /**
     * Number of inspections required = additional inspection duration / 30 min.
     * "90min" -> 3. Missing / not applicable -> 1. Always between 1 and 4.
     */
    public function requiredInspectionCount(): int
    {
        $minutes = (int) preg_replace('/\D/', '', (string) $this->additional_inspection_duration);
        $count   = $minutes > 0 ? intdiv($minutes, self::INSPECTION_INTERVAL_MINUTES) : 1;

        return max(1, min(4, $count));
    }

    /**
     * When inspection slot #$sequence becomes fillable.
     * Slot 1 is always open (returns null). For later slots it is 30 minutes
     * after the previous inspection; null if the previous one is not done yet.
     */
    public function inspectionSlotUnlockAt(int $sequence): ?\Illuminate\Support\Carbon
    {
        if ($sequence <= 1) {
            return null;
        }

        $previous = $this->inspections->firstWhere('sequence', $sequence - 1);

        return $previous
            ? $previous->inspected_at->copy()->addMinutes(self::INSPECTION_INTERVAL_MINUTES)
            : null;
    }

    /**
     * Whether inspection slot #$sequence can be filled right now:
     * not already done, all previous slots done, and (for slot >= 2) the
     * 30-minute wait after the previous inspection has passed.
     */
    public function isInspectionSlotUnlocked(int $sequence): bool
    {
        if ($this->inspections->firstWhere('sequence', $sequence)) {
            return false; // already recorded
        }

        for ($i = 1; $i < $sequence; $i++) {
            if (!$this->inspections->firstWhere('sequence', $i)) {
                return false; // a previous inspection is still missing
            }
        }

        if ($sequence <= 1) {
            return true;
        }

        $unlockAt = $this->inspectionSlotUnlockAt($sequence);

        return $unlockAt !== null && now()->gte($unlockAt);
    }

    /**
     * Overall inspection progress label for the card header.
     */
    public function getInspectionStatusAttribute(): string
    {
        $required = $this->requiredInspectionCount();
        $done     = $this->inspections->count();

        if ($done === 0) {
            return 'Not Inspected';
        }

        if ($done < $required) {
            return "In Progress ({$done}/{$required})";
        }

        return $this->inspections->contains('finding_type', 'NOK')
            ? 'Complete (NOK found)'
            : 'Complete';
    }

    /**
     * Check if HRA can be approved
     */
    public function canBeApproved()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if both approvals are completed (only EHS approval required)
     */
    public function isFullyApproved()
    {
        return $this->ehs_approval === 'approved';
    }

    /**
     * Check if any approval is rejected
     */
    public function isRejected()
    {
        return $this->ehs_approval === 'rejected';
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

    /**
     * Get start date from start_datetime
     */
    public function getStartDateAttribute()
    {
        return $this->start_datetime ? $this->start_datetime->format('Y-m-d') : null;
    }

    /**
     * Get start time from start_datetime
     */
    public function getStartTimeAttribute()
    {
        return $this->start_datetime ? $this->start_datetime->format('H:i') : null;
    }

    /**
     * Get end date from end_datetime
     */
    public function getEndDateAttribute()
    {
        return $this->end_datetime ? $this->end_datetime->format('Y-m-d') : null;
    }

    /**
     * Get end time from end_datetime
     */
    public function getEndTimeAttribute()
    {
        return $this->end_datetime ? $this->end_datetime->format('H:i') : null;
    }
}
