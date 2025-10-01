<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleConfigPowerTrainProfile extends Model
{
    use HasFactory, Searchable;



    protected  $fillable = [
        'id',
        'admin_id',
        'vehicle_id',
        'vehicleConfigPowerTrainProfile',
        'entrydate',
        'modified',
        'live',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
