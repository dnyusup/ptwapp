<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontraktorList extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_code',
        'address',
        'phone',
        'email',
        'contact_person',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship with users
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }
}
