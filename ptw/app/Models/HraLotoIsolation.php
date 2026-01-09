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
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
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
}
