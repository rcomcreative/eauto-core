<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BrandLogo extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;

    protected $fillable = ['make_id','name','id'];


    public function make() {
        return $this->belongsTo(Make::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->useDisk('media')
            ->withResponsiveImages();
    }


    protected static function booted(): void
    {
        static::saved(function (BrandLogo $logo) {
            // Only populate name from media if the record name is currently empty.
            if (! empty($logo->name)) {
                return;
            }

            $media = $logo->getFirstMedia('logo');

            if (! $media) {
                return;
            }

            // Keep legacy behavior: store the original media file name in `name`
            // so backfill and existing admin/table usage stay aligned.
            $logo->updateQuietly([
                'name' => $media->file_name,
            ]);
        });
    }

}
