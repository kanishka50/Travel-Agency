<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model  
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'role',
        'phone',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    // Helper: Check if super admin
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    // Helper: Check if registration manager
    public function isRegistrationManager(): bool
    {
        return $this->role === 'registration_manager';
    }

    // Helper: Check if can approve guides
    public function canApproveGuides(): bool
    {
        return in_array($this->role, ['super_admin', 'registration_manager']);
    }
}