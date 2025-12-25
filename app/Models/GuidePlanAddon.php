<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidePlanAddon extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'guide_plan_id',
        'day_number',
        'addon_name',
        'addon_description',
        'price_per_person',
        'require_all_participants',
        'max_participants',
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'require_all_participants' => 'boolean',
        'max_participants' => 'integer',
        'day_number' => 'integer',
    ];

    /**
     * Get the guide plan that owns this addon.
     */
    public function guidePlan(): BelongsTo
    {
        return $this->belongsTo(GuidePlan::class);
    }

    /**
     * Format the price for display.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price_per_person, 2);
    }

    /**
     * Get the day label for display.
     */
    public function getDayLabelAttribute(): string
    {
        return $this->day_number > 0 ? "Day {$this->day_number}" : 'Any Day';
    }
}
