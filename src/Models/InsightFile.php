<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InsightFile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'insight_files';

    protected $fillable = [
        'file_name',
        'caption',
        'truck_flag',
    ];

    protected $casts = [
        'truck_flag' => 'boolean',
    ];

    /**
     * Relationships
     */

    public function makes()
    {
        return $this->belongsToMany(
            Make::class,
            'make_insight_files',
            'insight_file_id',
            'make_id'
        )
            ->using(MakeInsightFile::class)
            ->withPivot('truck_flag')
            ->withTimestamps();
    }

    /**
     * Spatie Media Collection
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('insight_pdf')
            ->useDisk('media')
            ->singleFile();
    }

    /**
     * Media conversions (placeholder for future use)
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Example (disabled for now):
        // $this->addMediaConversion('thumb')
        //     ->width(300)
        //     ->height(300)
        //     ->performOnCollections('insight_pdf');
    }

    /**
     * Helper: Get PDF URL
     */
    public function getPdfUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('insight_pdf');
    }
}