<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'booking_type',
        'tourist_id',
        'guide_id',
        'guide_plan_id',
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
}
