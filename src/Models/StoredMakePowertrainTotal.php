<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class StoredMakePowertrainTotal extends Model
{
    protected $table = 'stored_make_powertrain_totals';

    protected $fillable = [
        'forecast_release_id',
        'make_id',
        'alternative_id',
        'sales_year',
        'total',
    ];

    protected $casts = [
        'forecast_release_id' => 'integer',
        'make_id' => 'integer',
        'alternative_id' => 'integer',
        'sales_year' => 'integer',
        'total' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (optional but useful)
    |--------------------------------------------------------------------------
    */

    public function forecastRelease()
    {
        return $this->belongsTo(\Eauto\Core\Models\ForecastRelease::class);
    }

    public function make()
    {
        return $this->belongsTo(\Eauto\Core\Models\Make::class);
    }

    public function alternative()
    {
        return $this->belongsTo(\Eauto\Core\Models\Alternative::class);
    }
}