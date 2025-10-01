<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Laravel\Scout\Searchable;

class MediaDownload extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia, Searchable;

    protected $fillable = [
        'id','uuid','collection_name','name','file_name','mime_type','disk','conversions_disk','size','manipulations','custom_properties','generated_conversions','responsive_images','order_column',
    ];

}
