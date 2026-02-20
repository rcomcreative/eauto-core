<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class StoredMakeSalesTotal extends Model
{
    protected $table = 'stored_make_sales_totals';

    protected $fillable = [
        'forecast_release_id',
        'make_id',
        'alternative_id',
        'sales_year',
        'truck_flag',
        'total',
    ];

    protected $casts = [
        'truck_flag' => 'boolean',
        'sales_year' => 'integer',
        'total' => 'decimal:1',
    ];

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