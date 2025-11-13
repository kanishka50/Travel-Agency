<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAddon extends Model
{
    protected $fillable = [
        'booking_id',
        'addon_id',
        'addon_name',
        'quantity',
        'price_per_unit',
        'total_price',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the booking that owns this addon
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
