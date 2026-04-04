<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Gallery extends Model implements HasMedia
{
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
}
