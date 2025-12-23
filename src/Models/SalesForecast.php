<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class SalesForecast extends Model
{
    use HasFactory, Searchable;

    protected $table = 'sales_forecasts';

    protected $fillable = [
        'forecast_release_id',
        'vehicle_id',
        'bodystyle_id',
        'category_item_id',
        'alternative_id',
        'sales_year',
        'sales_value',
        'segment_share',
        'make_share',
    ];

    protected $casts = [
        'forecast_release_id' => 'integer',
        'vehicle_id' => 'integer',
        'bodystyle_id' => 'integer',
        'category_item_id' => 'integer',
        'alternative_id' => 'integer',
        'sales_year' => 'integer',
        'sales_value' => 'decimal:1',
        'segment_share' => 'decimal:1',
        'make_share' => 'decimal:1',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function forecastRelease()
    {
        return $this->belongsTo(ForecastRelease::class, 'forecast_release_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function bodystyle()
    {
        return $this->belongsTo(Bodystyle::class, 'bodystyle_id');
    }

    public function categoryItem()
    {
        return $this->belongsTo(CategoryItem::class, 'category_item_id');
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForRelease(Builder $query, int $forecastReleaseId): Builder
    {
        return $query->where('forecast_release_id', $forecastReleaseId);
    }

    public function scopeForVehicle(Builder $query, int $vehicleId): Builder
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('sales_year', $year);
    }

    public function scopeForAlternative(Builder $query, int $alternativeId): Builder
    {
        return $query->where('alternative_id', $alternativeId);
    }

    /*
     * Add a scope for “current published release”
     * Right now, callers must manually:
	•	find the published release
	•	pass its ID
	•	then query sales_forecasts
    • That logic will spread quickly.
    • Use SalesForecast::forPublishedQuarter(2026, 2)->get();
     */

    public function scopeForPublishedQuarter(
        Builder $query,
        int $year,
        int $quarter
    ): Builder {
        return $query->whereHas('forecastRelease', function ($q) use ($year, $quarter) {
            $q->forQuarter($year, $quarter)
                ->published();
        });
    }

    /*
     * a convenience scope for “latest published”
     */
    public function scopeForLatestPublished(Builder $query): Builder
    {
        return $query->whereHas('forecastRelease', function ($q) {
            $q->published()
                ->orderByDesc('published_at')
                ->limit(1);
        });
    }
    /*
     * read-only guard (future-proofing)
     * Since we’ve decided SalesForecast rows should not be manually edited, you can make that explicit:
     */
    protected static function booted()
    {
        static::updating(function () {
            if (! app()->runningInConsole()) {
                throw new \RuntimeException(
                    'SalesForecast records are immutable; create a new ForecastRelease instead.'
                );
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scout (Search)
    |--------------------------------------------------------------------------
    */

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'forecast_release_id' => $this->forecast_release_id,
            'vehicle_id' => $this->vehicle_id,
            'bodystyle_id' => $this->bodystyle_id,
            'category_item_id' => $this->category_item_id,
            'alternative_id' => $this->alternative_id,
            'sales_year' => $this->sales_year,
        ];
    }
}
