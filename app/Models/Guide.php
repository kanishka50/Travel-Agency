<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    public const GUIDE_TYPES = [
        'chauffeur_guide' => 'Chauffeur Guide',
        'national_guide' => 'National Guide',
        'area_guide' => 'Area Guide',
        'site_guide' => 'Site Guide',
        'tourist_driver' => 'Tourist Driver',
        'wildlife_tracker' => 'Wildlife Tracker',
        'trekking_guide' => 'Trekking Guide',
        'not_specified' => 'Not Specified',
    ];

    protected $fillable = [
        'user_id',
        'guide_id_number',
        'full_name',
        'guide_type',
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
        'insurance_policy_number',
        'insurance_expiry',
        'emergency_contact_name',
        'emergency_contact_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'commission_rate',
        'admin_notes',
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

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function activeVehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class)->where('is_active', true);
    }

    // Helper to get guide type display label
    public function getGuideTypeLabelAttribute(): string
    {
        return self::GUIDE_TYPES[$this->guide_type] ?? 'Not Specified';
    }
}