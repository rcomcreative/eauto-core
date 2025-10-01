<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable;

class VehiclePhoto extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;

    protected $fillable = [
        'vehicle_id',
        'photo_caption',
        'model_year',
        'photo_date',
        'photo_credit',
        'source_photo_name',
        'active',
        'main',
        'new',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk('media')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->width(200)
            ->height(140)
            ->sharpen(10)
            ->nonQueued();
    }

    // In your VehiclePhoto model, for example
    public function getMainImageUrl(?int $seconds = 300): ?string
    {
        $media = $this->getFirstMedia('photos');
        return $media?->getTemporaryUrl(now()->addSeconds($seconds));
    }
}
