<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermitToWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'work_title',
        'work_location',
        'location',
        'equipment_tools',
        'work_description',
        'department',
        'responsible_person',
        'responsible_person_email',
        'receiver_name',
        'receiver_email',
        'receiver_company_name',
        'building',
        'floor',
        'tip',
        'location_owner_id',
        'work_at_heights',
        'hot_work',
        'loto_isolation',
        'line_breaking',
        'excavation',
        'confined_spaces',
        'explosive_atmosphere',
        'form_y_n',
        'form_detail',
        'start_date',
        'end_date',
        'status',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
        'permit_issuer_id',
        'authorizer_id',
        'receiver_id',
        'issued_at',
        'authorized_at',
        'received_at',
    ];

    protected $casts = [
        'emergency_contact_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'work_at_heights' => 'boolean',
        'hot_work' => 'boolean',
        'loto_isolation' => 'boolean',
        'line_breaking' => 'boolean',
        'excavation' => 'boolean',
        'confined_spaces' => 'boolean',
        'explosive_atmosphere' => 'boolean',
        'issued_at' => 'datetime',
        'authorized_at' => 'datetime',
        'received_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'permit_issuer_id');
    }

    public function permitIssuer()
    {
        return $this->belongsTo(User::class, 'permit_issuer_id');
    }

    public function authorizer()
    {
        return $this->belongsTo(User::class, 'authorizer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function locationOwner()
    {
        return $this->belongsTo(User::class, 'location_owner_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function methodStatement()
    {
        return $this->hasOne(MethodStatement::class, 'permit_number', 'permit_number');
    }

    public function riskAssessments()
    {
        return $this->hasMany(RiskAssessment::class);
    }

    public function hraWorkAtHeights()
    {
        return $this->hasMany(HraWorkAtHeight::class);
    }

    public function hraHotWorks()
    {
        return $this->hasMany(HraHotWork::class);
    }

    public function hraLotoIsolations()
    {
        return $this->hasMany(HraLotoIsolation::class);
    }

    public function hraLineBreakings()
    {
        return $this->hasMany(HraLineBreaking::class);
    }

    public function hraExcavations()
    {
        return $this->hasMany(HraExcavation::class);
    }

    public function hraConfinedSpaces()
    {
        return $this->hasMany(HraConfinedSpace::class);
    }

    public function hraExplosiveAtmospheres()
    {
        return $this->hasMany(HraExplosiveAtmosphere::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'permit_number', 'permit_number');
    }

    /**
     * Check if the current user is the permit creator
     */
    public function isCreatedBy($userId)
    {
        return $this->permit_issuer_id === $userId;
    }

    /**
     * Check if the current user can request approval for this permit
     */
    public function canRequestApproval($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        // Only permit creator can request approval
        //if (!$this->isCreatedBy($userId)) {
        //    return false;
        //}

        // Method statement must exist and be completed
        if (!$this->methodStatement || $this->methodStatement->status !== 'completed') {
            return false;
        }

        // Permit must not be in certain statuses
        //if (in_array($this->status, ['approved', 'active', 'rejected', 'pending_approval'])) {
        //    return false;
       // }

        return true;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permit) {
            if (empty($permit->permit_number)) {
                $permit->permit_number = 'PTW-' . date('Y') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
