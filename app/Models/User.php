<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser, HasName
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


    // Filament: Only admins can access panel
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->user_type === 'admin' && $this->status === 'active';
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

    // For Filament (if you want users to login via User model)
    public function canAccessFilament(): bool
    {
        return $this->isAdmin();
    }

    public function getFilamentName(): string
    {
        // GUARANTEED to return a string - never null
        return $this->email;
    }
}