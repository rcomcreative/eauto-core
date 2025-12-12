<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class StoredSalesForecastCalculations extends Model
{
    use HasFactory, Searchable;

    protected $table = 'stored_sales_forecast_calculations';

    protected $fillable = [
        'forecast_release_id',
        'category_item_id',
        'alternative_id',
        'sales_year',
        'truck_flag',
        'total',
    ];

    protected $casts = [
        'forecast_release_id' => 'integer',
        'category_item_id' => 'integer',
        'alternative_id' => 'integer',
        'sales_year' => 'integer',
        'truck_flag' => 'boolean',
        'total' => 'decimal:1',
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

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->where('sales_year', $year);
    }

    public function scopeForAlternative(Builder $query, int $alternativeId): Builder
    {
        return $query->where('alternative_id', $alternativeId);
    }

    public function scopeTruck(Builder $query): Builder
    {
        return $query->where('truck_flag', true);
    }

    public function scopeNonTruck(Builder $query): Builder
    {
        return $query->where('truck_flag', false);
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
            'category_item_id' => $this->category_item_id,
            'alternative_id' => $this->alternative_id,
            'sales_year' => $this->sales_year,
            'truck_flag' => (bool) $this->truck_flag,
        ];
    }
}
