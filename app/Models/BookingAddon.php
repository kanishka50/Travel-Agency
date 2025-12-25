<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingAddon extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'booking_id',
        'guide_plan_addon_id',
        'addon_name',
        'addon_description',
        'day_number',
        'price_per_person',
        'num_participants',
        'total_price',
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'total_price' => 'decimal:2',
        'day_number' => 'integer',
        'num_participants' => 'integer',
    ];

    /**
     * Get the booking that owns this addon
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the original guide plan addon
     */
    public function guidePlanAddon(): BelongsTo
    {
        return $this->belongsTo(GuidePlanAddon::class);
    }
}
