<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'permit_number',
        'inspector_name', 
        'inspector_email',
        'findings',
        'photo_path'
    ];

    public function permit()
    {
        return $this->belongsTo(PermitToWork::class, 'permit_number', 'permit_number');
    }
}
