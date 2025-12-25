<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'booking_type',
        'tourist_id',
        'guide_id',
        'guide_plan_id',
        'tourist_request_id',
        'accepted_bid_id',
        'accepted_proposal_id',
        'start_date',
        'end_date',
        'num_adults',
        'num_children',
        'children_ages',
        'base_price',
        'addons_total',
        'subtotal',
        'platform_fee',
        'total_amount',
        'guide_payout',
        'status',
        'tourist_notes',
        'guide_notes',
        'cancellation_reason',
        'payment_intent_id',
        'payment_status',
        'paid_at',
        'confirmed_at',
        'cancelled_at',
        'agreement_pdf_path',
        'stripe_session_id',
        'stripe_payment_id',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'children_ages' => 'array',
        'base_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'guide_payout' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the tourist that made this booking
     */
    public function tourist(): BelongsTo
    {
        return $this->belongsTo(Tourist::class, 'tourist_id');
    }

    /**
     * Get the guide for this booking
     */
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class, 'guide_id');
    }

    /**
     * Get the guide plan for this booking
     */
    public function guidePlan(): BelongsTo
    {
        return $this->belongsTo(GuidePlan::class, 'guide_plan_id');
    }

    /**
     * Get the add-ons for this booking
     */
    public function addons(): HasMany
    {
        return $this->hasMany(BookingAddon::class);
    }

    /**
     * Get the payment record for this booking
     */
    public function payment(): HasOne
    {
        return $this->hasOne(BookingPayment::class);
    }

    /**
     * Get the tourist request this booking originated from (for custom request bookings)
     */
    public function touristRequest(): BelongsTo
    {
        return $this->belongsTo(TouristRequest::class);
    }

    /**
     * Get the accepted bid this booking was created from (for custom request bookings)
     */
    public function acceptedBid(): BelongsTo
    {
        return $this->belongsTo(Bid::class, 'accepted_bid_id');
    }

    /**
     * Get the accepted proposal this booking was created from (for plan proposal bookings)
     */
    public function acceptedProposal(): BelongsTo
    {
        return $this->belongsTo(PlanProposal::class, 'accepted_proposal_id');
    }

    /**
     * Check if this booking was created from a plan proposal
     */
    public function isFromProposal(): bool
    {
        return $this->booking_type === 'plan_proposal' && $this->accepted_proposal_id !== null;
    }

    /**
     * Get the vehicle assignment for this booking
     */
    public function vehicleAssignment(): HasOne
    {
        return $this->hasOne(BookingVehicleAssignment::class);
    }

    /**
     * Check if this booking has a vehicle assigned
     */
    public function hasVehicleAssigned(): bool
    {
        return $this->vehicleAssignment()->exists();
    }

    /**
     * Get total number of participants (adults + children)
     */
    public function getTotalParticipantsAttribute(): int
    {
        return $this->num_adults + $this->num_children;
    }

    /**
     * Check if booking needs vehicle assignment (upcoming and no vehicle)
     */
    public function needsVehicleAssignment(): bool
    {
        return !$this->hasVehicleAssigned()
            && $this->start_date >= now()
            && !in_array($this->status, [
                'cancelled_by_tourist',
                'cancelled_by_guide',
                'cancelled_by_admin',
                'completed',
                'pending_payment',
                'payment_failed'
            ]);
    }

    /**
     * Get days until tour starts
     */
    public function getDaysUntilStartAttribute(): int
    {
        return max(0, now()->diffInDays($this->start_date, false));
    }

    /**
     * Check if booking is within 3-day vehicle assignment deadline
     */
    public function isWithinVehicleDeadline(): bool
    {
        return $this->days_until_start <= 3 && $this->days_until_start >= 0;
    }
}
