<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Salesforecast extends Model
{
    use HasFactory, Searchable;
    protected  $fillable = [
        'id',
        'vehicle_id',
        'bodystyle_id',
        'category_item_id',
        'sales_year',
        'sales_value',
        'segment_share',
        'make_share'
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
