<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidePaymentTransaction extends Model
{
    protected $fillable = [
        'booking_payment_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'notes',
        'paid_by_admin',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the booking payment that this transaction belongs to
     */
    public function bookingPayment(): BelongsTo
    {
        return $this->belongsTo(BookingPayment::class);
    }

    /**
     * Get the admin who recorded this payment
     */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'paid_by_admin');
    }
}
