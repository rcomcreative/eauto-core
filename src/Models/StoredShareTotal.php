<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoredShareTotal extends Model
{
    /** @use HasFactory<\Database\Factories\StoredShareTotalFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'stored_share_totals';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'forecast_release_id',
        'entity_type',
        'entity_id',
        'department_id',
        'alternative_id',
        'sales_year',

        'car_total',
        'truck_total',
        'lv_total',

        'share_car_market',
        'share_truck_market',
        'share_total_market',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'forecast_release_id' => 'int',
        'entity_id' => 'int',
        'department_id' => 'int',
        'alternative_id' => 'int',
        'sales_year' => 'int',

        'car_total' => 'float',
        'truck_total' => 'float',
        'lv_total' => 'float',

        'share_car_market' => 'float',
        'share_truck_market' => 'float',
        'share_total_market' => 'float',
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
