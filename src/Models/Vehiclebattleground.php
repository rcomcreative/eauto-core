<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Vehiclebattleground extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['id','vehicle_id','FutureProduct','XToY','PresentProduct','History',
        'PowertrainChassis','CurrentCodeName','FutureCodeName','Change1','Change2','Chang3',
        'AmericaRegion','AsiaRegion','EuropeRegion'];
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
