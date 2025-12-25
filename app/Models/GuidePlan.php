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
        'vehicle_capacity',
        'vehicle_ac',
        'vehicle_description',
        'dietary_options',
        'accessibility_info',
        'inclusions',
        'exclusions',
        'cover_photo',
        'status',
        'allow_proposals',
        'min_proposal_price',
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
        'allow_proposals' => 'boolean',
        'min_proposal_price' => 'decimal:2',
    ];

    // Relationships
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'guide_plan_id');
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Booking::class, 'guide_plan_id', 'booking_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(PlanProposal::class);
    }

    public function pendingProposals(): HasMany
    {
        return $this->hasMany(PlanProposal::class)->where('status', 'pending');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GuidePlanPhoto::class)->orderBy('display_order');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(GuidePlanItinerary::class)->orderBy('day_number');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(GuidePlanAddon::class)->orderBy('day_number');
    }

    /**
     * Get all photos including cover photo as the first item
     */
    public function getAllPhotosAttribute(): \Illuminate\Support\Collection
    {
        $photos = collect();

        // Add cover photo first if exists
        if ($this->cover_photo) {
            $photos->push((object)[
                'id' => null,
                'photo_path' => $this->cover_photo,
                'url' => asset('storage/' . $this->cover_photo),
                'is_cover' => true,
            ]);
        }

        // Add gallery photos
        foreach ($this->photos as $photo) {
            $photos->push((object)[
                'id' => $photo->id,
                'photo_path' => $photo->photo_path,
                'url' => $photo->url,
                'is_cover' => false,
            ]);
        }

        return $photos;
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

    /**
     * Check if proposals are allowed for this plan
     */
    public function allowsProposals(): bool
    {
        return $this->allow_proposals ?? true;
    }
}
