<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class DepartmentCustomSegmentVehicle extends Model
{
    use HasFactory, Searchable;
    protected $table = 'department_custom_segment_vehicles';

    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'department_custom_segment_id',
        'vehicle_id',
    ];

    // Relationships
    public function customSegment()
    {
        return $this->belongsTo(DepartmentCustomSegment::class, 'department_custom_segment_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
