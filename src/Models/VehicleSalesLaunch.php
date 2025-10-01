<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleSalesLaunch extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'id',
        'vehicle_id',
        'sales_launch',
        'sales_launch_MQ',
        'sales_launch_Y',
        'sales_launch_notes',
        'delete_flag',
        'time_stamp',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
