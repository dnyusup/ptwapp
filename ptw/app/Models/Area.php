<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the responsible users for the area (EHS department users)
     */
    public function responsibles()
    {
        return $this->belongsToMany(User::class, 'area_user')
                    ->withTimestamps();
    }

    /**
     * Scope for active areas only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
