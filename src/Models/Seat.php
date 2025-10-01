<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Seat extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'id',
        'seats'
    ];

    public function vehicles() {
        return $this->belongsToMany(Vehicle::class)->withTimestamps();
    }

}
