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
        'overhead_hazards_checked',
        'overhead_hazards_minimal_guards',
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
        'mobile_elevation_operator_trained',
        'mobile_elevation_rescue_person',
        'mobile_elevation_monitor_in_place',
        'mobile_elevation_legal_inspection',
        'mobile_elevation_pre_use_inspection',
        'mobile_elevation_location_marked',
        'ladder_checked',
        'ladder_area_barriers',
        'fall_arrest_used',
        'fall_arrest_worker_trained',
        'fall_arrest_legal_inspection',
        'fall_arrest_pre_use_inspection',
        'fall_arrest_qualified_personnel',
        'roof_work_checked',
        'roof_load_capacity_adequate',
        'roof_edge_protection',
        'roof_fall_protection_system',
        'roof_communication_method',
        'area_closed_from_below',
        'cable_ducts_disruption',
        'ventilation_openings',
        'machine_equipment_protected',
        'emergency_exit_point',
        'materials_secured',
        'safety_personnel_required',
        'other_conditions',
        // Work Conditions fields
        'workers_have_training_proof',
        'area_below_blocked',
        'workers_below_present',
        'floor_suitable_for_access_equipment',
        'obstacles_near_work_location',
        'ventilation_hazardous_emissions',
        'protection_needed_for_equipment',
        'safe_access_exit_method',
        'safe_material_handling_method',
        'emergency_escape_plan_needed',
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
        'mobile_elevation_operator_trained' => 'boolean',
        'mobile_elevation_rescue_person' => 'boolean',
        'mobile_elevation_monitor_in_place' => 'boolean',
        'mobile_elevation_legal_inspection' => 'boolean',
        'mobile_elevation_pre_use_inspection' => 'boolean',
        'mobile_elevation_location_marked' => 'boolean',
        'ladder_checked' => 'boolean',
        'ladder_area_barriers' => 'boolean',
        'fall_arrest_used' => 'boolean',
        'fall_arrest_worker_trained' => 'boolean',
        'fall_arrest_legal_inspection' => 'boolean',
        'fall_arrest_pre_use_inspection' => 'boolean',
        'fall_arrest_qualified_personnel' => 'boolean',
        'roof_work_checked' => 'boolean',
        'roof_load_capacity_adequate' => 'boolean',
        'roof_edge_protection' => 'boolean',
        'roof_fall_protection_system' => 'boolean',
        'roof_communication_method' => 'boolean',
        'area_closed_from_below' => 'boolean',
        'cable_ducts_disruption' => 'boolean',
        'ventilation_openings' => 'boolean',
        'machine_equipment_protected' => 'boolean',
        'emergency_exit_point' => 'boolean',
        'materials_secured' => 'boolean',
        'safety_personnel_required' => 'boolean',
        // Work Conditions casts  
        'workers_have_training_proof' => 'boolean',
        'area_below_blocked' => 'boolean',
        'workers_below_present' => 'boolean',
        'floor_suitable_for_access_equipment' => 'boolean',
        'obstacles_near_work_location' => 'boolean',
        'ventilation_hazardous_emissions' => 'boolean',
        'protection_needed_for_equipment' => 'boolean',
        'safe_access_exit_method' => 'boolean',
        'safe_material_handling_method' => 'boolean',
        'emergency_escape_plan_needed' => 'boolean',
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
    public static function generateHraPermitNumber($permitNumber)
    {
        // Get count of existing HRA Work at Heights for this permit
        $count = static::where('permit_number', $permitNumber)->count();
        
        // Format: General Permit Number + HRA Type + Sequence
        return $permitNumber . '-WaH-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }
}
