<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Content extends Model
{
    use HasFactory, Searchable;

    protected  $fillable = ['id','vehicle_id','admin_id','textOrPdf','pressReleasePdf',
        'pressReleasePdfCaption','type','content','publishDate','activeFlag','deleteFlag'];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
