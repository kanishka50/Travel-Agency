<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidePlanItinerary extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * Only created_at is used in this table.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'guide_plan_id',
        'day_number',
        'day_title',
        'description',
        'accommodation_name',
        'accommodation_type',
        'accommodation_tier',
        'breakfast_included',
        'lunch_included',
        'dinner_included',
        'meal_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'day_number' => 'integer',
        'breakfast_included' => 'boolean',
        'lunch_included' => 'boolean',
        'dinner_included' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Get the guide plan that owns this itinerary day.
     */
    public function guidePlan(): BelongsTo
    {
        return $this->belongsTo(GuidePlan::class);
    }

    /**
     * Get a formatted label for the accommodation type.
     */
    public function getAccommodationTypeLabelAttribute(): ?string
    {
        $labels = [
            'hotel' => 'Hotel',
            'guesthouse' => 'Guesthouse',
            'resort' => 'Resort',
            'homestay' => 'Homestay',
            'camping' => 'Camping',
            'other' => 'Other',
        ];

        return $this->accommodation_type ? ($labels[$this->accommodation_type] ?? $this->accommodation_type) : null;
    }

    /**
     * Get a formatted label for the accommodation tier.
     */
    public function getAccommodationTierLabelAttribute(): ?string
    {
        $labels = [
            'budget' => 'Budget',
            'midrange' => 'Mid-Range',
            'luxury' => 'Luxury',
        ];

        return $this->accommodation_tier ? ($labels[$this->accommodation_tier] ?? $this->accommodation_tier) : null;
    }

    /**
     * Get an array of included meals.
     */
    public function getIncludedMealsAttribute(): array
    {
        $meals = [];
        if ($this->breakfast_included) $meals[] = 'Breakfast';
        if ($this->lunch_included) $meals[] = 'Lunch';
        if ($this->dinner_included) $meals[] = 'Dinner';
        return $meals;
    }

    /**
     * Check if any meal is included.
     */
    public function hasMealsIncluded(): bool
    {
        return $this->breakfast_included || $this->lunch_included || $this->dinner_included;
    }

    /**
     * Check if accommodation is specified.
     */
    public function hasAccommodation(): bool
    {
        return !empty($this->accommodation_name);
    }
}
