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
        'start_of_production',
        'start_of_production_mq',
        'start_of_production_y',
        'start_of_production_notes',
        'delete_flag',
        'time_stamp',
    ];

    protected $casts = [
        'time_stamp' => 'date',
        'delete_flag' => 'integer',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

}
