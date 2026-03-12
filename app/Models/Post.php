<?php

namespace App\Models;

use Dom\Comment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;

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
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
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



    // =======================================SPATIE LOG ACTIVITY========================================================
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'type',
                'cover',
                'caption',
                'video_url',
                'content',
                'status',
                'category_id',
                'author_id',
                'publish_time',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    // =======================================SPATIE LOG ACTIVITY========================================================
}
