<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class VehiclePressRelease extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['id','vehicle_id','pdf','pdfCaption','pdfPathway','entryDate','sortColumn'];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
