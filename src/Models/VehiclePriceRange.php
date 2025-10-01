<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehiclePriceRange extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'id',
        'vehicle_id',
        'low',
        'priceRangeLowMQ',
        'priceRangeLowY',
        'priceRangeLowNotes',
        'high',
        'priceRangeHighMQ',
        'priceRangeHighY',
        'priceRangeHighNotes',
        'delete_flag',
        'order',
        'time_stamp',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
