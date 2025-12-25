<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GuidePlanPhoto extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'guide_plan_id',
        'photo_path',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    // Relationships
    public function guidePlan(): BelongsTo
    {
        return $this->belongsTo(GuidePlan::class);
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->photo_path);
    }

    // Delete the physical file when the model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($photo) {
            if ($photo->photo_path) {
                Storage::disk('public')->delete($photo->photo_path);
            }
        });
    }
}
