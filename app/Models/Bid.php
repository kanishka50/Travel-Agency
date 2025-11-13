<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{
    protected $fillable = [
        'tourist_request_id',
        'guide_id',
        'bid_number',
        'proposal_message',
        'day_by_day_plan',
        'total_price',
        'price_breakdown',
        'destinations_covered',
        'accommodation_details',
        'transport_details',
        'included_services',
        'excluded_services',
        'estimated_days',
        'status',
        'rejection_reason',
        'submitted_at',
        'responded_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'destinations_covered' => 'array',
        'submitted_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the tourist request this bid belongs to
     */
    public function touristRequest(): BelongsTo
    {
        return $this->belongsTo(TouristRequest::class);
    }

    /**
     * Get the guide who submitted this bid
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    /**
     * Check if this is the first bid from guide
     */
    public function isFirstBid(): bool
    {
        return $this->bid_number === 1;
    }

    /**
     * Check if this is the second bid from guide
     */
    public function isSecondBid(): bool
    {
        return $this->bid_number === 2;
    }

    /**
     * Check if bid is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if bid is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if bid is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Accept this bid
     */
    public function accept(): void
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);
    }

    /**
     * Reject this bid with reason
     */
    public function reject(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'responded_at' => now(),
        ]);
    }

    /**
     * Withdraw this bid
     */
    public function withdraw(): void
    {
        $this->update(['status' => 'withdrawn']);
    }

    /**
     * Scope to get only pending bids
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get only accepted bids
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}
