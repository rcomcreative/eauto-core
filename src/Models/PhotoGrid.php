<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class PhotoGrid extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'makeid',
        'fullcount',
        'mainphotopath',
        'photos',
        'path'
    ];

}
