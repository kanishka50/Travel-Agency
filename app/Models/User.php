<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
        'user_type',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function tourist(): HasOne
    {
        return $this->hasOne(Tourist::class);
    }

    public function guide(): HasOne
    {
        return $this->hasOne(Guide::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    // Helper methods
    public function isTourist(): bool
    {
        return $this->user_type === 'tourist';
    }

    public function isGuide(): bool
    {
        return $this->user_type === 'guide';
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}