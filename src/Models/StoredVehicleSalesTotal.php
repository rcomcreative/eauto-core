<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class StoredVehicleSalesTotal extends Model
{
    protected $table = 'stored_vehicle_sales_totals';

    protected $fillable = [
        'forecast_release_id',
        'vehicle_id',
        'bodystyle_id',
        'alternative_id',
        'sales_year',
        'total',
    ];

    protected $casts = [
        'sales_year' => 'integer',
        'total' => 'decimal:1',
    ];

    public function forecastRelease()
    {
        return $this->belongsTo(\Eauto\Core\Models\ForecastRelease::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(\Eauto\Core\Models\Vehicle::class);
    }

    public function bodystyle()
    {
        return $this->belongsTo(\Eauto\Core\Models\Bodystyle::class);
    }

    public function alternative()
    {
        return $this->belongsTo(\Eauto\Core\Models\Alternative::class);
    }
}
