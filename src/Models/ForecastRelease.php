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
        'validated_at',
        'validated_by',
        'uploaded_by',
        'source_filename',
        'source_file_path',
        'source_file_hash',
        'notes',
        'import_summary',
        'validation_errors',
        'validation_warnings',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'year' => 'integer',
        'quarter' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'validated_at' => 'datetime',
        'import_summary' => 'array',
        'validation_errors' => 'array',
        'validation_warnings' => 'array',
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
        return $this->belongsTo(\Eauto\Core\Models\User::class, 'uploaded_by');
    }

    /**
     * Admin user who validated this release.
     */
    public function validatedBy()
    {
        return $this->belongsTo(\Eauto\Core\Models\User::class, 'validated_by');
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
        return $this->hasMany(\Eauto\Core\Models\StoredSalesForecastCalculations::class, 'forecast_release_id');
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

    /**
     * Defensive scope to prevent crashes when Filament or dynamic calls
     * attempt to call a non-existent "none" scope.
     * This safely returns an empty result set instead of throwing an error.
     */
    public function scopeNone(Builder $query): Builder
    {
        return $query->whereRaw('1 = 0');
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
            'validated_at' => optional($this->validated_at)->toDateTimeString(),
            'source_filename' => $this->source_filename,
        ];
    }

    /**
     * Stored totals tables (e.g. stored_make_sales_totals / stored_vehicle_sales_totals)
     * are performance caches built at publish time (typically by the admin app), but the
     * models live in this shared core package so both admin + client can read them.
     */

    public function storedDepartmentSegmentTotals()
    {
        return $this->hasMany(\Eauto\Core\Models\StoredDepartmentSegmentSalesTotal::class, 'forecast_release_id');
    }

    public function storedMakeTotals()
    {
        return $this->hasMany(\Eauto\Core\Models\StoredMakeSalesTotal::class, 'forecast_release_id');
    }

    public function storedVehicleTotals()
    {
        return $this->hasMany(\Eauto\Core\Models\StoredVehicleSalesTotal::class, 'forecast_release_id');
    }

    /**
     * Market-level totals per year (Passenger Car / Light Truck / Light Vehicle).
     */
    public function storedMarketTotals()
    {
        return $this->hasMany(\Eauto\Core\Models\StoredMarketTotal::class, 'forecast_release_id');
    }

    /**
     * Share + totals per entity (make / vehicle / segment), per year.
     */
    public function storedShareTotals()
    {
        return $this->hasMany(\Eauto\Core\Models\StoredShareTotal::class, 'forecast_release_id');
    }

    public function storedMakePowertrainTotals()
    {
        return $this->hasMany(
            \Eauto\Core\Models\StoredMakePowertrainTotal::class,
            'forecast_release_id'
        );
    }
}
