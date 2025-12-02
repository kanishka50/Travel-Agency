<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanProposal extends Model
{
    protected $fillable = [
        'guide_plan_id',
        'tourist_id',
        'proposed_price',
        'start_date',
        'end_date',
        'num_adults',
        'num_children',
        'children_ages',
        'modifications',
        'message',
        'status',
        'rejection_reason',
        'booking_id',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'children_ages' => 'array',
    ];

    /**
     * Get the guide plan this proposal is for
     */
    public function guidePlan(): BelongsTo
    {
        return $this->belongsTo(GuidePlan::class);
    }

    /**
     * Get the tourist who made this proposal
     */
    public function tourist(): BelongsTo
    {
        return $this->belongsTo(Tourist::class);
    }

    /**
     * Get the booking created from this proposal (if accepted)
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if proposal is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if proposal is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if proposal is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if proposal is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get the guide through the plan relationship
     */
    public function getGuideAttribute()
    {
        return $this->guidePlan?->guide;
    }

    /**
     * Calculate total travelers
     */
    public function getTotalTravelersAttribute(): int
    {
        return $this->num_adults + $this->num_children;
    }

    /**
     * Get duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if price is below minimum
     */
    public function isBelowMinimum(): bool
    {
        $minPrice = $this->guidePlan?->min_proposal_price;

        if (!$minPrice) {
            return false;
        }

        return $this->proposed_price < $minPrice;
    }

    /**
     * Get discount percentage from original price
     */
    public function getDiscountPercentageAttribute(): float
    {
        $originalPrice = $this->guidePlan?->price_per_adult;

        if (!$originalPrice || $originalPrice == 0) {
            return 0;
        }

        return round((($originalPrice - $this->proposed_price) / $originalPrice) * 100, 1);
    }
}
