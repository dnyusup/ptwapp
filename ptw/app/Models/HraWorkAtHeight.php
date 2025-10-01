<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraWorkAtHeight extends Model
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
        // Checklist items
        'fixed_scaffolding_checked',
        'fixed_scaffolding_approved_by_she',
        'fixed_scaffolding_operator_trained',
        'fixed_scaffolding_usage_correct',
        'fixed_scaffolding_fall_protection',
        'mobile_scaffolding_checked',
        'mobile_scaffolding_approved_by_she',
        'mobile_scaffolding_operator_trained',
        'mobile_scaffolding_usage_correct',
        'mobile_scaffolding_fall_protection',
        'mobile_elevation_checked',
        'mobile_elevation_no_other_tools',
        'mobile_elevation_activities_short',
        'mobile_elevation_used_before',
        'mobile_elevation_training_provided',
        'mobile_elevation_location_marked',
        'ladder_checked',
        'ladder_area_barriers',
        'fall_arrest_used',
        'roof_work_checked',
        'roof_load_capacity',
        'roof_fragile_areas',
        'roof_fall_protection',
        'roof_additional_controls',
        'area_closed_from_below',
        'cable_ducts_disruption',
        'ventilation_openings',
        'machine_equipment_protected',
        'emergency_exit_point',
        'materials_secured',
        'safety_personnel_required',
        'other_conditions',
        // Work Conditions fields
        'area_below_closed',
        'work_area_disturbances',
        'ventilation_hazards',
        'equipment_protection',
        'emergency_exit_available',
        'material_handling',
        'safety_personnel_needed',
        'other_conditions_check',
        'other_conditions_text',
        // Environmental Conditions fields (single select)
        'visibility_condition',
        'rain_condition',
        'surface_condition',
        'wind_condition',
        'chemical_spill_condition',
        'environment_other_conditions',
        'visibility',
        'rain',
        'surface_condition',
        'wind',
        'oil_spill_risk',
        'additional_controls',
        'permit_issuer_name',
        'permit_issuer_signature',
        'receiver_name',
        'receiver_signature',
        'issue_date',
        'issue_time',
        'status'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'fixed_scaffolding_checked' => 'boolean',
        'fixed_scaffolding_approved_by_she' => 'boolean',
        'fixed_scaffolding_operator_trained' => 'boolean',
        'fixed_scaffolding_usage_correct' => 'boolean',
        'fixed_scaffolding_fall_protection' => 'boolean',
        'mobile_scaffolding_checked' => 'boolean',
        'mobile_scaffolding_approved_by_she' => 'boolean',
        'mobile_scaffolding_operator_trained' => 'boolean',
        'mobile_scaffolding_usage_correct' => 'boolean',
        'mobile_scaffolding_fall_protection' => 'boolean',
        'mobile_elevation_checked' => 'boolean',
        'mobile_elevation_no_other_tools' => 'boolean',
        'mobile_elevation_activities_short' => 'boolean',
        'mobile_elevation_used_before' => 'boolean',
        'mobile_elevation_training_provided' => 'boolean',
        'mobile_elevation_location_marked' => 'boolean',
        'ladder_checked' => 'boolean',
        'ladder_area_barriers' => 'boolean',
        'fall_arrest_used' => 'boolean',
        'roof_work_checked' => 'boolean',
        'roof_load_capacity' => 'boolean',
        'roof_fragile_areas' => 'boolean',
        'roof_fall_protection' => 'boolean',
        'area_closed_from_below' => 'boolean',
        'cable_ducts_disruption' => 'boolean',
        'ventilation_openings' => 'boolean',
        'machine_equipment_protected' => 'boolean',
        'emergency_exit_point' => 'boolean',
        'materials_secured' => 'boolean',
        'safety_personnel_required' => 'boolean',
        // Work Conditions casts  
        'area_below_closed' => 'boolean',
        'work_area_disturbances' => 'boolean',
        'ventilation_hazards' => 'boolean',
        'equipment_protection' => 'boolean',
        'emergency_exit_available' => 'boolean',
        'material_handling' => 'boolean',
        'safety_personnel_needed' => 'boolean',
        'other_conditions_check' => 'boolean',
        'oil_spill_risk' => 'boolean',
        'issue_date' => 'date',
        'issue_time' => 'datetime:H:i',
    ];

    /**
     * Relationship to main permit
     */
    public function permitToWork()
    {
        return $this->belongsTo(PermitToWork::class);
    }

    /**
     * Relationship to user who created the HRA
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique HRA permit number
     */
    public static function generateHraPermitNumber($mainPermitNumber)
    {
        $count = self::where('permit_number', $mainPermitNumber)->count();
        return $mainPermitNumber . '-WaH-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
