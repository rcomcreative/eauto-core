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
        'supersedes_forecast_release_id',
        'label',
        'year',
        'quarter',
        'status',
        'is_published',
        'published_at',
        'uploaded_by',
        'source_filename',
        'source_file_path',
        'source_file_hash',
        'notes',
        'import_summary',
        'validation_errors',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'import_summary' => 'array',
        'validation_errors' => 'array',
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

    public function supersedes()
    {
        return $this->belongsTo(self::class, 'supersedes_forecast_release_id');
    }

    public function supersededBy()
    {
        return $this->hasMany(self::class, 'supersedes_forecast_release_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Only releases visible to clients.
     */
//    public function scopePublished(Builder $query): Builder
//    {
//        return $query->where('is_published', true);
//    }
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('status', 'published')
                ->orWhere('is_published', true);
        });
    }

    /**
     * Filter to a specific year/quarter when you have both.
     */
    public function scopeForQuarter(Builder $query, int $year, int $quarter): Builder
    {
        return $query->where('year', $year)
            ->where('quarter', $quarter);
    }

    /*
     * This gives you a single source of truth for:
	•	APIs
	•	dashboards
	•	charts
	•	exports
    • use ForecastRelease::forPublishedQuarter(2026, 2)->first();
     */
    public function scopeForPublishedQuarter(
        Builder $query,
        int $year,
        int $quarter
    ): Builder {
        return $query
            ->forQuarter($year, $quarter)
            ->published()
            ->orderByDesc('published_at')
            ->limit(1);
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
            'status' => $this->status,
            'is_published' => (bool) $this->is_published,
            'published_at' => optional($this->published_at)->toDateTimeString(),
            'source_filename' => $this->source_filename,
        ];
    }
}
