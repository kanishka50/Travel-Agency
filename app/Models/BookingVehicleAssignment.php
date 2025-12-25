<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingVehicleAssignment extends Model
{
    protected $fillable = [
        'booking_id',
        'vehicle_id',
        'is_temporary',
        'temporary_vehicle_data',
        'assigned_at',
        'assigned_by',
    ];

    protected $casts = [
        'is_temporary' => 'boolean',
        'temporary_vehicle_data' => 'array',
        'assigned_at' => 'datetime',
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Helpers

    /**
     * Get vehicle details (works for both saved and temporary vehicles)
     */
    public function getVehicleDetailsAttribute(): array
    {
        if ($this->is_temporary && $this->temporary_vehicle_data) {
            return $this->temporary_vehicle_data;
        }

        if ($this->vehicle) {
            return [
                'vehicle_type' => $this->vehicle->vehicle_type,
                'make' => $this->vehicle->make,
                'model' => $this->vehicle->model,
                'license_plate' => $this->vehicle->license_plate,
                'seating_capacity' => $this->vehicle->seating_capacity,
                'has_ac' => $this->vehicle->has_ac,
                'description' => $this->vehicle->description,
                'photos' => $this->vehicle->photos->pluck('photo_path')->toArray(),
            ];
        }

        return [];
    }

    /**
     * Get display name for the vehicle
     */
    public function getVehicleDisplayNameAttribute(): string
    {
        if ($this->is_temporary && $this->temporary_vehicle_data) {
            $data = $this->temporary_vehicle_data;
            return ($data['make'] ?? '') . ' ' . ($data['model'] ?? '');
        }

        return $this->vehicle ? $this->vehicle->display_name : 'Unknown Vehicle';
    }

    /**
     * Get license plate
     */
    public function getLicensePlateAttribute(): string
    {
        if ($this->is_temporary && $this->temporary_vehicle_data) {
            return $this->temporary_vehicle_data['license_plate'] ?? '';
        }

        return $this->vehicle->license_plate ?? '';
    }

    /**
     * Get seating capacity
     */
    public function getSeatingCapacityAttribute(): int
    {
        if ($this->is_temporary && $this->temporary_vehicle_data) {
            return $this->temporary_vehicle_data['seating_capacity'] ?? 0;
        }

        return $this->vehicle->seating_capacity ?? 0;
    }

    /**
     * Check if assigned by admin
     */
    public function wasAssignedByAdmin(): bool
    {
        if (!$this->assigned_by) {
            return false;
        }

        $user = $this->assignedByUser;
        return $user && $user->user_type === 'admin';
    }

    /**
     * Get photos for the assigned vehicle
     */
    public function getPhotosAttribute(): array
    {
        if ($this->is_temporary && $this->temporary_vehicle_data) {
            return $this->temporary_vehicle_data['photos'] ?? [];
        }

        if ($this->vehicle) {
            return $this->vehicle->photos->pluck('photo_path')->toArray();
        }

        return [];
    }
}
