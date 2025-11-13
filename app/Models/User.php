<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * Determine if the user can access the Filament admin panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only users with user_type 'admin' and status 'active' can access
        return $this->user_type === 'admin' && $this->status === 'active';
    }

    /**
     * Get the name to display in Filament (required by HasName interface)
     */
    public function getFilamentName(): string
    {
        // Eager load relationships if not loaded
        if (!$this->relationLoaded('admin') && $this->isAdmin()) {
            $this->load('admin');
        }

        if (!$this->relationLoaded('guide') && $this->isGuide()) {
            $this->load('guide');
        }

        if (!$this->relationLoaded('tourist') && $this->isTourist()) {
            $this->load('tourist');
        }

        // Return name based on user type
        if ($this->isAdmin() && $this->admin) {
            return $this->admin->full_name;
        }

        if ($this->isGuide() && $this->guide) {
            return $this->guide->full_name ?? $this->email;
        }

        if ($this->isTourist() && $this->tourist) {
            return $this->tourist->nickname ?? $this->email;
        }

        // Fallback to email
        return $this->email ?? 'Unknown User';
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