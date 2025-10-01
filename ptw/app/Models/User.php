<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'department',
        'company_id',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function issuedPermits()
    {
        return $this->hasMany(PermitToWork::class, 'permit_issuer_id');
    }

    public function authorizedPermits()
    {
        return $this->hasMany(PermitToWork::class, 'authorizer_id');
    }

    public function receivedPermits()
    {
        return $this->hasMany(PermitToWork::class, 'receiver_id');
    }

    /**
     * Check if user can delete other users
     */
    public function canDeleteUsers()
    {
        // Only administrators have full delete access
        if ($this->role === 'administrator') {
            return true;
        }

        // Bekaert EHS department cannot delete users
        if ($this->role === 'bekaert' && $this->department === 'EHS') {
            return false;
        }

        // Other Bekaert users can delete (except EHS)
        //if ($this->role === 'bekaert') {
        //    return true;
        //}

        // Contractors cannot delete users
        return false;
    }

    /**
     * Check if user can edit administrator accounts
     */
    public function canEditAdminUsers()
    {
        return $this->role === 'administrator';
    }

    /**
     * Check if user can view administrator accounts
     */
    public function canViewAdminUsers()
    {
        return $this->role === 'administrator';
    }

    // Relationship with KontraktorList
    public function company()
    {
        return $this->belongsTo(KontraktorList::class, 'company_id');
    }
}
