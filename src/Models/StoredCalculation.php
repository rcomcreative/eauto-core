<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class StoredCalculation extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = [
        'id',
        'sfc_id',
        'total',
        'category_item_id',
        'sales_year',
        'truck_flag',
    ];

}
