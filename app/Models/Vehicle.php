<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    public const VEHICLE_TYPES = [
        'car' => 'Car',
        'van' => 'Van',
        'suv' => 'SUV',
        'minibus' => 'Minibus',
        'bus' => 'Bus',
        'tuk_tuk' => 'Tuk Tuk',
        'motorcycle' => 'Motorcycle',
    ];

    protected $fillable = [
        'guide_id',
        'vehicle_type',
        'make',
        'model',
        'year',
        'license_plate',
        'seating_capacity',
        'has_ac',
        'description',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'seating_capacity' => 'integer',
        'has_ac' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class)->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function bookingAssignments(): HasMany
    {
        return $this->hasMany(BookingVehicleAssignment::class);
    }

    /**
     * Alias for bookingAssignments - for easier access
     */
    public function assignments(): HasMany
    {
        return $this->bookingAssignments();
    }

    /**
     * Get upcoming assignments relationship
     */
    public function upcomingAssignments(): HasMany
    {
        return $this->bookingAssignments()
            ->whereHas('booking', function ($query) {
                $query->where('start_date', '>=', now()->toDateString())
                    ->whereNotIn('status', [
                        'cancelled_by_tourist',
                        'cancelled_by_guide',
                        'cancelled_by_admin',
                        'completed'
                    ]);
            });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithMinCapacity($query, int $capacity)
    {
        return $query->where('seating_capacity', '>=', $capacity);
    }

    // Helpers
    public function getVehicleTypeLabelAttribute(): string
    {
        return self::VEHICLE_TYPES[$this->vehicle_type] ?? $this->vehicle_type;
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->make} {$this->model}";
    }

    public function getFullDisplayAttribute(): string
    {
        $ac = $this->has_ac ? 'AC' : 'Non-AC';
        return "{$this->make} {$this->model} ({$this->vehicle_type_label}, {$this->seating_capacity} seats, {$ac})";
    }

    public function getPrimaryPhotoAttribute(): ?VehiclePhoto
    {
        return $this->photos()->where('is_primary', true)->first()
            ?? $this->photos()->first();
    }

    public function getRegistrationDocumentAttribute(): ?VehicleDocument
    {
        return $this->documents()->where('document_type', 'registration')->first();
    }

    public function getInsuranceDocumentAttribute(): ?VehicleDocument
    {
        return $this->documents()->where('document_type', 'insurance')->first();
    }

    /**
     * Check if vehicle has any upcoming booking assignments
     */
    public function hasUpcomingAssignments(): bool
    {
        return $this->bookingAssignments()
            ->whereHas('booking', function ($query) {
                $query->where('start_date', '>=', now()->toDateString())
                    ->whereNotIn('status', [
                        'cancelled_by_tourist',
                        'cancelled_by_guide',
                        'cancelled_by_admin',
                        'completed'
                    ]);
            })
            ->exists();
    }

    /**
     * Get count of upcoming booking assignments
     */
    public function upcomingAssignmentsCount(): int
    {
        return $this->bookingAssignments()
            ->whereHas('booking', function ($query) {
                $query->where('start_date', '>=', now()->toDateString())
                    ->whereNotIn('status', [
                        'cancelled_by_tourist',
                        'cancelled_by_guide',
                        'cancelled_by_admin',
                        'completed'
                    ]);
            })
            ->count();
    }

    /**
     * Check if vehicle can be edited (not assigned to upcoming bookings)
     */
    public function canBeEdited(): bool
    {
        return !$this->hasUpcomingAssignments();
    }

    /**
     * Check if vehicle can be deleted (not assigned to upcoming bookings)
     */
    public function canBeDeleted(): bool
    {
        return !$this->hasUpcomingAssignments();
    }

    /**
     * Check if vehicle can be deactivated (not assigned to upcoming bookings)
     */
    public function canBeDeactivated(): bool
    {
        return !$this->hasUpcomingAssignments();
    }
}
