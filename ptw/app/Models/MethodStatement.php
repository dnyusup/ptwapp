<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MethodStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'responsible_person_name',
        'method_statement_date',
        'permit_receiver_name',
        'permit_issuer_name',
        'responsible_persons',
        'responsible_person_1',
        'responsible_person_2',
        'responsible_person_3',
        'responsible_person_4',
        'responsible_person_5',
        'responsible_person_6',
        // New field names
        'safe_access_explanation',
        'ppe_safety_equipment_explanation',
        'qualifications_training_explanation',
        'safe_routes_identification_explanation',
        'storage_security_explanation',
        'equipment_checklist_explanation',
        'work_order_explanation',
        'temporary_work_explanation',
        'weather_conditions_explanation',
        'area_maintenance_explanation',
        'risk_activities',
        'risk_levels',
        'control_measures',
        'status',
        'created_by'
    ];

    protected $casts = [
        'method_statement_date' => 'date',
        'risk_activities' => 'array',
        'risk_levels' => 'array',
        'control_measures' => 'array',
        'responsible_persons' => 'array'
    ];

    // Relationship with PermitToWork
    public function permitToWork()
    {
        return $this->belongsTo(PermitToWork::class, 'permit_number', 'permit_number');
    }

    // Relationship with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
