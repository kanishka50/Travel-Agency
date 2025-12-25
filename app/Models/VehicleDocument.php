<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleDocument extends Model
{
    public $timestamps = false;

    public const DOCUMENT_TYPES = [
        'registration' => 'Vehicle Registration',
        'insurance' => 'Vehicle Insurance',
    ];

    protected $fillable = [
        'vehicle_id',
        'document_type',
        'document_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Helpers
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->document_path);
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type;
    }
}
