<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Scout\Searchable;

class Media extends Model implements HasMedia
{
    use InteractsWithMedia, HasFactory, Searchable;

    protected $fillable = [
        'id','model','uuid','collection_name','name','file_name','mime_type','disk','conversions_disk','size','manipulations','custom_properties','generated_conversions','responsive_images','order_column',
    ];

}
