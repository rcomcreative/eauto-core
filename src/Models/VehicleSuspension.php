<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleSuspension extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'id',
        'vehicle_id',
        'front_suspension',
        'rear_suspenstion',
        'delete_flag',
        'order'
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
