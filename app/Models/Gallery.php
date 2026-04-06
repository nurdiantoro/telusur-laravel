<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->format('webp')
            ->height(1080)
            ->quality(80)
            ->sharpen(2)
            ->nonQueued();

        $this->addMediaConversion('thumbnail')
            ->format('webp')
            ->height(200)
            ->quality(80)
            ->sharpen(2)
            ->nonQueued();
    }

    public function getSpatiePreviewAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'preview') ?: asset('img/no_image.webp');
    }

    public function getSpatieThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'thumbnail') ?: asset('img/no_image.webp');
    }
}
