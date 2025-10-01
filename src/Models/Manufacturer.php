<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Manufacturer extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['name','id'];

    public function makes() {
        return $this->hasMany(Make::class);
    }
}
