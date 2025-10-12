<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'role',
        'phone',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isContentModerator(): bool
    {
        return $this->role === 'content_moderator';
    }

    public function isFinanceAdmin(): bool
    {
        return $this->role === 'finance_admin';
    }
}