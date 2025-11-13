<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuidePlan extends Model
{
    protected $fillable = [
        'guide_id',
        'title',
        'description',
        'num_days',
        'num_nights',
        'pickup_location',
        'dropoff_location',
        'destinations',
        'trip_focus_tags',
        'price_per_adult',
        'price_per_child',
        'max_group_size',
        'min_group_size',
        'availability_type',
        'available_start_date',
        'available_end_date',
        'vehicle_type',
        'vehicle_category',
        'vehicle_capacity',
        'vehicle_ac',
        'vehicle_description',
        'dietary_options',
        'accessibility_info',
        'cancellation_policy',
        'inclusions',
        'exclusions',
        'cover_photo',
        'status',
        'view_count',
        'booking_count',
    ];

    protected $casts = [
        'destinations' => 'array',
        'trip_focus_tags' => 'array',
        'dietary_options' => 'array',
        'price_per_adult' => 'decimal:2',
        'price_per_child' => 'decimal:2',
        'available_start_date' => 'date',
        'available_end_date' => 'date',
        'vehicle_ac' => 'boolean',
    ];

    // Relationships
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'plan_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'plan_id');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementBookingCount(): void
    {
        $this->increment('booking_count');
    }
}
