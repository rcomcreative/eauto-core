<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleDimension extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['id',
        'vehicle_id',
        'variation_name',
        'planned_life_cycle',
        'OAH',
        'OAW',
        'OAL',
        'WB',
        'delete_flag',
        'order',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
