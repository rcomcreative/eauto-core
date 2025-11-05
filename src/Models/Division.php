<?php

namespace Eauto\Core\Models;

use Eauto\Core\Models\Make;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Division extends Model
{
    use Searchable;

    protected $fillable = [
        'id',
        'name',
    ];

    public function makes() {
        return $this->hasMany(Make::class);
    }
}
