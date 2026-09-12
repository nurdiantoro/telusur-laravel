<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use LogsActivity;
    use Searchable;

    protected $guarded = [];
    protected $casts = ['publish_time' => 'datetime'];

    /*
    |--------------------------------------------------------------------------
    | Model Relationships
    |--------------------------------------------------------------------------
    |
    | Kumpulan relasi antar model yang digunakan pada Post.
    | Digunakan untuk menghubungkan data seperti author, category, komentar,
    | dan tag.
    |
    | Daftar Relasi:
    |
    | 1. author()
    |    - Relasi ke User (penulis artikel)
    |    - Foreign key: author_id
    |
    | 2. category()
    |    - Relasi ke PostCategory
    |    - Menentukan kategori dari artikel
    |
    | 3. comments()
    |    - Relasi ke Comment (one to many)
    |    - Satu post bisa memiliki banyak komentar
    |
    | 4. tags()
    |    - Relasi many-to-many ke Tag
    |    - Digunakan untuk sistem tagging artikel
    |
    */

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Spatie Activity Log Configuration
    |--------------------------------------------------------------------------
    |
    | Mengatur logging aktivitas pada model Post.
    | Digunakan untuk mencatat perubahan data (audit log).
    |
    | Konfigurasi:
    |
    | - logOnly():
    |   Hanya field tertentu yang dicatat
    |
    | - logOnlyDirty():
    |   Hanya mencatat perubahan data (tidak semua field)
    |
    | - dontSubmitEmptyLogs():
    |   Tidak membuat log jika tidak ada perubahan
    |
    | Cocok untuk:
    | - audit perubahan artikel
    | - tracking editing oleh admin
    |
    */

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'type',
                'cover',
                'gallery_id',
                'caption',
                'video_url',
                'content',
                'status',
                'category_id',
                'headline',
                'infografis',
                'author_id',
                'publish_time',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /*
    |--------------------------------------------------------------------------
    | Laravel Scout (Search Integration)
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk integrasi full-text search menggunakan Laravel Scout.
    | Bisa dihubungkan dengan driver seperti Meilisearch, Algolia, dll.
    |
    | toSearchableArray():
    | - Menentukan field apa saja yang akan di-index ke search engine
    |
    | searchableAs():
    | - Menentukan nama index (collection) di search engine
    |
    | Catatan:
    | - Jangan terlalu banyak field agar indexing tetap ringan
    | - Bisa ditambahkan field seperti views untuk ranking search
    |
    */

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'publish_time' => $this->publish_time?->timestamp,
        ];
    }

    public function shouldBeSearchable()
    {
        return $this->status === 'published';
    }

    public function searchableAs()
    {
        return 'posts';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes for Post Model
    |--------------------------------------------------------------------------
    |
    | Kumpulan reusable query scope untuk mempermudah pengambilan data Post
    | di seluruh aplikasi, khususnya untuk kebutuhan frontend seperti homepage,
    | sidebar, dan section berita.
    |
    | Tujuan:
    | - Menghindari duplikasi query (DRY principle)
    | - Meningkatkan readability pada controller
    | - Memudahkan maintenance & scaling
    |
    | Contoh penggunaan:
    |
    | Post::withRelations()
    |     ->published()
    |     ->post()
    |     ->latestPublished()
    |     ->limit(10)
    |     ->get();
    |
    */
    public function scopePost(Builder $query): Builder
    {
        return $query
            ->where('type', 'post')
            ->where('status', 'published')
            ->with([
                'category:id,name,slug',
                'gallery:id,thumbnail,preview',
            ])
            ->orderByDesc('publish_time');
    }
    public function scopeOpini(Builder $query): Builder
    {
        return $query
            ->where('type', 'opini')
            ->where('status', 'published')
            ->with(['category', 'gallery'])
            ->orderByDesc('publish_time');
    }
    public function scopeVideo(Builder $query): Builder
    {
        return $query
            ->where('type', 'video')
            ->where('status', 'published')
            ->with([
                'category:id,name,slug',
                'gallery:id',
                'gallery.media:id,model_id,file_name,collection_name,disk,conversions_disk'
            ])
            ->orderByDesc('publish_time');
    }

    /*
    |--------------------------------------------------------------------------
    | Image Accessors
    |--------------------------------------------------------------------------
    |
    | Menentukan sumber image artikel.
    |
    | Artikel baru:
    | - Menggunakan Gallery melalui gallery_id
    | - Thumbnail diambil dari Gallery.thumbnail
    | - Preview diambil dari Gallery.preview
    |
    | Artikel lama:
    | - Tidak memiliki gallery_id
    | - Menggunakan column cover sebagai nama file
    |
    */

    public function getThumbnailUrlAttribute(): string
    {
        // Artikel baru
        if ($this->gallery_id && $this->gallery?->thumbnail) {
            $path = $this->gallery->thumbnail;
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        }

        // Artikel lama
        if ($this->cover) {
            $path = 'backup/storage/thumbnails/' . $this->cover;
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Default
        return asset('img/no_image.webp');
    }

    public function getThumbnailVideoAttribute(): string
    {
        // Artikel baru
        if ($this->gallery_id && $this->gallery?->thumbnail) {
            $path = $this->gallery->thumbnail;
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        }

        // Artikel lama
        if ($this->cover) {
            $path = 'backup/storage/thumbnails/' . $this->cover;
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Default
        return 'https://img.youtube.com/vi/' . $this->video_url . '/hqdefault.jpg';
    }

    public function getPreviewUrlAttribute(): string
    {
        // Artikel baru
        if ($this->gallery_id && $this->gallery?->preview) {
            $path = $this->gallery->preview;
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        }

        // Artikel lama
        if ($this->cover) {
            $path = 'backup/storage/images/' . $this->cover;

            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Default
        return asset('img/no_image.webp');
    }
}
