<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleStartOfProduction extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'id',
        'vehicle_id',
        'StartOfProduction',
        'startOfProductionMQ',
        'startOfProductionY',
        'startOfProductionNotes',
        'delete_flag',
        'time_stamp',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
