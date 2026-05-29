<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VehiclePressRelease extends Model implements HasMedia
{
    use HasFactory, Searchable;
    use InteractsWithMedia;

    protected $fillable = [
        'id',
        'vehicle_id',
        'pdf',
        'media_file_name',
        'pdfCaption',
        'pdfPathway',
        'entryDate',
        'sortColumn',
    ];

    protected $appends = [
        'pdf_url',
    ];

    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }

    public function getPdfUrlAttribute(): ?string
    {
        $url = $this->getFirstMediaUrl('press-releases');

        return $url !== '' ? $url : null;
    }


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('press-releases')
            ->useDisk('media')
            ->singleFile(); // one PDF per press release
    }
}
