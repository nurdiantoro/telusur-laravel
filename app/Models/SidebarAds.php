<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SidebarAds extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->format('webp')
            ->width(562)
            // ->quality(80)
            ->nonQueued();

        // $this->addMediaConversion('thumbnail')
        //     ->format('webp')
        // ->fit(Fit::Crop, 600, 300)
        //     ->quality(85)
        //     ->nonQueued();
    }
}
