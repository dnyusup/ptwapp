<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiskAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_to_work_id',
        'hazard_activity',
        'risk_level',
        'control_measures',
        'author_id',
        'receiver_id',
        'author_date',
        'receiver_date',
        'detailed_control_measures',
    ];

    protected $casts = [
        'author_date' => 'date',
        'receiver_date' => 'date',
    ];

    public function permitToWork()
    {
        return $this->belongsTo(PermitToWork::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
