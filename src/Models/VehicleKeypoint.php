<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehicleKeypoint extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['id','admin_id','vehicle_id','make_id','keypoint_text','entry_time','modified','live','time_stamp'];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
