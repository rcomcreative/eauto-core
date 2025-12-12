<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class CategoryItem extends Model
{
    use HasFactory, Searchable;

    protected $table = 'category_items';

    // Allow mass assignment for 'type'
    protected $fillable = [
        'type',
    ];

    // If you ever want to disable timestamps, set this to false:
    // public $timestamps = false;
}
