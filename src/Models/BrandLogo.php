<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BrandLogo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;

    protected $fillable = ['make_id','name','id'];


    public function divisions() {
        return $this->belongsTo(Make::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('brandLogo')
            ->useDisk('media')
            ->withResponsiveImages();
    }

}
