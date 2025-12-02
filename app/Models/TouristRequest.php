<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TouristRequest extends Model
{

    protected $fillable = [
        'tourist_id',
        'title',
        'description',
        'duration_days',
        'preferred_destinations',
        'must_visit_places',
        'num_adults',
        'num_children',
        'children_ages',
        'start_date',
        'end_date',
        'dates_flexible',
        'flexibility_range',
        'budget_min',
        'budget_max',
        'trip_focus',
        'transport_preference',
        'accommodation_preference',
        'dietary_requirements',
        'accessibility_needs',
        'special_requests',
        'status',
        'bid_count',
        'expires_at',
    ];

    protected $casts = [
        'preferred_destinations' => 'array',
        'children_ages' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'dates_flexible' => 'boolean',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'trip_focus' => 'array',
        'dietary_requirements' => 'array',
        'expires_at' => 'date',
    ];

    /**
     * Get the tourist that created this request
     */
    public function tourist(): BelongsTo
    {
        return $this->belongsTo(Tourist::class);
    }

    /**
     * Get all bids for this request
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Get only pending bids
     */
    public function pendingBids(): HasMany
    {
        return $this->bids()->where('status', 'pending');
    }

    /**
     * Get the accepted bid
     */
    public function acceptedBid(): HasMany
    {
        return $this->bids()->where('status', 'accepted');
    }

    /**
     * Check if request is still open for bids
     */
    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->expires_at->isFuture();
    }

    /**
     * Check if request has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Increment bid count
     */
    public function incrementBidCount(): void
    {
        $this->increment('bid_count');
    }

    /**
     * Scope to get only open requests
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open')
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired requests
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now())
                    ->where('status', 'open');
    }

    /**
     * Get the booking created from this request
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    /**
     * Check if request has been booked
     */
    public function isBooked(): bool
    {
        return $this->status === 'booked';
    }
}
