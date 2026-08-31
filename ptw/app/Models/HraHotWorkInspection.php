<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HraHotWorkInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'hra_hot_work_id',
        'sequence',
        'inspector_name',
        'inspector_email',
        'finding_type',
        'findings',
        'photo_path',
        'inspected_at',
        'inspected_by',
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
    ];

    public function hraHotWork()
    {
        return $this->belongsTo(HraHotWork::class, 'hra_hot_work_id');
    }

    public function inspectedBy()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}
