<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;

class StoredDepartmentSegmentSalesTotal extends Model
{
    protected $table = 'stored_department_segment_sales_totals';

    protected $fillable = [
        'forecast_release_id',
        'department_id',
        'department_custom_segment_id',
        'alternative_id',
        'sales_year',
        'truck_flag',
        'total',
    ];

    protected $casts = [
        'forecast_release_id' => 'integer',
        'department_id' => 'integer',
        'department_custom_segment_id' => 'integer',
        'alternative_id' => 'integer',
        'sales_year' => 'integer',
        'truck_flag' => 'boolean',
        'total' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function forecastRelease()
    {
        return $this->belongsTo(ForecastRelease::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function segment()
    {
        return $this->belongsTo(DepartmentCustomSegment::class, 'department_custom_segment_id');
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}