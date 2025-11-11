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


    public function make() {
        return $this->belongsTo(Make::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('brandLogo')
            ->useDisk('media')
            ->withResponsiveImages();
    }


    protected static function booted(): void
    {
        static::saved(function (BrandLogo $logo) {
            // Only do this if name is still empty
            if (! empty($logo->name)) {
                return;
            }

            $media = $logo->getFirstMedia('logo');

            if (! $media) {
                return;
            }

            // Use the media's file_name (without extension) as the logo name
            $baseName = pathinfo($media->file_name, PATHINFO_FILENAME);

            $logo->updateQuietly([
                'name' => $baseName,
            ]);
        });
    }

}
