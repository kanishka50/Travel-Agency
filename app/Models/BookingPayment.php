<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'total_amount',
        'platform_fee',
        'guide_payout',
        'amount_paid',
        'amount_remaining',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'guide_payout' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_remaining' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public $timestamps = false; // Uses created_at only

    /**
     * Get the booking that this payment belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get all payment transactions for this booking payment
     */
    public function transactions()
    {
        return $this->hasMany(GuidePaymentTransaction::class);
    }

    /**
     * Check if fully paid
     */
    public function isFullyPaid(): bool
    {
        return $this->amount_remaining <= 0;
    }

    /**
     * Get payment progress percentage
     */
    public function getPaymentProgressAttribute(): int
    {
        if ($this->guide_payout <= 0) return 0;
        return (int) (($this->amount_paid / $this->guide_payout) * 100);
    }
}
