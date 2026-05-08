<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleKeypoint extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'entry_date',
        'time_stamp',
        'modified',
        'keypoint_text',
        'live',
        'admin_id',
        'make_id',
        'vehicle_id',
    ];
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
