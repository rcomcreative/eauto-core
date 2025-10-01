<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class OverviewHeader extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'id',
        'flush_heading',
        'flush_collapse',
        'flush_collapse_two',
        'accordian_header'
    ];
}
