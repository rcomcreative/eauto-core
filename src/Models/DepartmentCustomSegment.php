<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Eauto\Core\Models\Department;
use Eauto\Core\Models\Vehicle;

class DepartmentCustomSegment extends Model
{
    use HasFactory, Searchable;

    protected $table = 'department_custom_segments';

    public $incrementing = false;   // id comes from live data
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'department_id',
        'segment_name',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function vehicles()
    {
        return $this->belongsToMany(
            Vehicle::class,
            'department_custom_segment_vehicles',
            'department_custom_segment_id',
            'vehicle_id'
        )->withTimestamps();
    }
}
