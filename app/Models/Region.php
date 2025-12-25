<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Sri Lankan provinces for dropdown
     */
    public const PROVINCES = [
        'Western Province' => 'Western Province',
        'Central Province' => 'Central Province',
        'Southern Province' => 'Southern Province',
        'Northern Province' => 'Northern Province',
        'Eastern Province' => 'Eastern Province',
        'North Western Province' => 'North Western Province',
        'North Central Province' => 'North Central Province',
        'Uva Province' => 'Uva Province',
        'Sabaragamuwa Province' => 'Sabaragamuwa Province',
    ];

    /**
     * Scope to get only active regions
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order then name
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get active regions for dropdown/select
     */
    public static function getActiveRegions(): array
    {
        return static::active()
            ->ordered()
            ->pluck('name', 'name')
            ->toArray();
    }

    /**
     * Get active regions grouped by province
     */
    public static function getActiveRegionsGroupedByProvince(): array
    {
        $regions = static::active()
            ->ordered()
            ->get()
            ->groupBy('province');

        $result = [];
        foreach ($regions as $province => $provinceRegions) {
            $provinceName = $province ?: 'Other';
            $result[$provinceName] = $provinceRegions->pluck('name', 'name')->toArray();
        }

        return $result;
    }
}
