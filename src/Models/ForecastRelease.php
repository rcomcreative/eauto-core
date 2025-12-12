<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ForecastRelease extends Model
{
    use HasFactory, Searchable;

    /**
     * If your table name is non-standard, lock it in.
     */
    protected $table = 'forecast_releases';

    /**
     * Mass-assignable attributes.
     * (We avoid making `is_published` guarded so Filament can toggle it intentionally.)
     */
    protected $fillable = [
        'label',
        'year',
        'quarter',
        'is_published',
        'uploaded_by',
        'source_filename',
        'source_file_hash',
        'notes',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'is_published' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Admin user who uploaded / created this release.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    /**
     * Row-level forecasts for this release.
     */
    public function salesForecasts()
    {
        return $this->hasMany(SalesForecast::class, 'forecast_release_id');
    }

    /**
     * Precomputed aggregates for this release.
     */
    public function storedSalesForecastCalculations()
    {
        return $this->hasMany(StoredSalesForecastCalculation::class, 'forecast_release_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Only releases visible to clients.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Filter to a specific year/quarter when you have both.
     */
    public function scopeForQuarter(Builder $query, int $year, int $quarter): Builder
    {
        return $query->where('year', $year)->where('quarter', $quarter);
    }

    /*
    |--------------------------------------------------------------------------
    | Scout (Search)
    |--------------------------------------------------------------------------
    */

    /**
     * Keep the searchable payload small and stable.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'year' => $this->year,
            'quarter' => $this->quarter,
            'is_published' => (bool) $this->is_published,
            'source_filename' => $this->source_filename,
        ];
    }
}
