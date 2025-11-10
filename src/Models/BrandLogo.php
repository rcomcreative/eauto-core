<?php

namespace Eauto\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BrandLogo extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['make_id','name','id'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('brandLogo')
            ->useDisk('media')
            ->withResponsiveImages();
    }

}
