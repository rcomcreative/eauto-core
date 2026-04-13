<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Bodystyle extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'id',
        'name',
        'abbrev',
        'is_system',
    ];

    public function vehicles() {
        return $this->belongsToMany(Vehicle::class)->withTimestamps();
    }

    public function scopeUserSelectable($query)
    {
        return $query->where('is_system', false);
    }
}
