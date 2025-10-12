<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    protected $fillable = [
        'user_id',
        'guide_id_number',
        'full_name',
        'phone',
        'national_id',
        'bio',
        'profile_photo',
        'languages',
        'expertise_areas',
        'regions_can_guide',
        'years_experience',
        'average_rating',
        'total_reviews',
        'total_bookings',
        'license_number',
        'license_expiry',
        'vehicle_type',
        'vehicle_registration',
        'insurance_policy_number',
        'insurance_expiry',
        'emergency_contact_name',
        'emergency_contact_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'commission_rate',
    ];

    protected $casts = [
        'languages' => 'array',
        'expertise_areas' => 'array',
        'regions_can_guide' => 'array',
        'license_expiry' => 'date',
        'insurance_expiry' => 'date',
        'average_rating' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(GuidePlan::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}