<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $casts = ['publish_time' => 'datetime',];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    // =======================================SPATIE MEDIA LIBRARY=======================================================

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->format('webp')
            ->width(1200)
            ->height(1200)
            ->quality(80)
            ->nonQueued();

        $this->addMediaConversion('thumbnail')
            ->format('webp')
            ->width(600)
            ->height(300)
            ->quality(80)
            ->nonQueued();
    }

    // getSpatiePreviewAttribute bisa di panggil di view dengan $post->spatie_preview
    // $this->getFirstMediaUrl('Name Collection', 'Name Conversion')
    public function getSpatiePreviewAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'preview');
    }
    public function getSpatieThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'thumbnail');
    }
    // =======================================SPATIE MEDIA LIBRARY=======================================================
}
