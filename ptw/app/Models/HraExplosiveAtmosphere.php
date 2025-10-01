<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraExplosiveAtmosphere extends Model
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
        $prefix = 'HRA-EA-';
        $year = date('Y');
        $month = date('m');
        
        // Get the latest HRA number for this month
        $latestHra = static::whereYear('created_at', $year)
                           ->whereMonth('created_at', $month)
                           ->orderBy('created_at', 'desc')
                           ->first();
        
        if ($latestHra) {
            // Extract the sequence number from the latest HRA permit number
            $latestNumber = $latestHra->hra_permit_number;
            preg_match('/EA-(\d{4})(\d{2})-(\d{4})/', $latestNumber, $matches);
            $sequence = isset($matches[3]) ? intval($matches[3]) + 1 : 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . $month . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
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
