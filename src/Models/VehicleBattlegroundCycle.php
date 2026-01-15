<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleBattlegroundCycle extends Model
{
    use HasFactory, Searchable;

    protected $table = 'vehicle_battleground_cycles';

    protected $fillable = [
        'vehicle_id',
        'line_year',
        'cycle_text',
        'time_stamp',
        'id'
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
