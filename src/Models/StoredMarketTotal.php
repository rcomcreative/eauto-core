<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoredMarketTotal extends Model
{
    /** @use HasFactory<\Database\Factories\StoredMarketTotalFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'stored_market_totals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'forecast_release_id',
        'alternative_id',
        'sales_year',
        'passenger_car_total',
        'light_truck_total',
        'light_vehicle_total',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'forecast_release_id' => 'int',
        'alternative_id' => 'int',
        'sales_year' => 'int',
        'passenger_car_total' => 'float',
        'light_truck_total' => 'float',
        'light_vehicle_total' => 'float',
    ];

    public function forecastRelease()
    {
        return $this->belongsTo(ForecastRelease::class, 'forecast_release_id');
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id');
    }
}
