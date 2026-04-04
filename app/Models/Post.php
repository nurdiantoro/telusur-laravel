<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use InteractsWithMedia;
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

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Spatie Media Library Configuration
    |--------------------------------------------------------------------------
    |
    | Mengatur media conversion untuk gambar pada Post.
    | Digunakan untuk menghasilkan berbagai ukuran gambar otomatis.
    |
    | Conversion:
    |
    | 1. preview
    |    - Ukuran: 1200x1200
    |    - Format: webp
    |    - Digunakan untuk tampilan utama / detail artikel
    |
    | 2. thumbnail
    |    - Ukuran: 600x300
    |    - Format: webp
    |    - Digunakan untuk list / card / sidebar
    |
    | nonQueued():
    | - Proses dilakukan secara synchronous (langsung)
    | - Cocok untuk development, tapi untuk production besar
    |   sebaiknya pakai queue agar tidak membebani request
    |
    */

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

    /*
    |--------------------------------------------------------------------------
    | Accessor for Media URL (Spatie)
    |--------------------------------------------------------------------------
    |
    | Shortcut untuk mengambil URL gambar dari Spatie Media Library.
    | Bisa langsung dipanggil di blade tanpa harus menulis logic panjang.
    |
    | Contoh penggunaan:
    | - $post->spatie_preview
    | - $post->spatie_thumbnail
    |
    | imagesCollection:
    | - Nama collection media yang digunakan saat upload gambar
    |
    */

    public function getSpatiePreviewAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'preview');
    }

    public function getSpatieThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('imagesCollection', 'thumbnail');
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
            'status' => $this->status,
            'type' => $this->type,
            'publish_time' => optional($this->publish_time)->timestamp,
        ];
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
    | Daftar Scope:
    |
    | 1. published()
    |    - Filter hanya post dengan status 'published'
    |    - Menghindari post yang publish_time-nya masih di masa depan
    |
    | 2. type($type)
    |    - Filter berdasarkan tipe konten (post, opini, video, dll)
    |    - Digunakan jika ingin fleksibel dalam filtering
    |
    | 3. latestPublished()
    |    - Mengurutkan berdasarkan publish_time terbaru (descending)
    |
    | 4. popular()
    |    - Mengurutkan berdasarkan jumlah views terbanyak
    |
    | 5. withRelations()
    |    - Eager load relasi utama:
    |      media, category, author
    |    - Menghindari N+1 query problem saat render frontend
    |
    | 6. post(), opini(), video()
    |    - Shortcut untuk filtering type tertentu
    |    - Lebih readable dibanding type('post'), dll
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
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->where('publish_time', '<=', now());
    }
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderByDesc('publish_time');
    }
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('views');
    }
    public function scopeWithRelations(Builder $query): Builder
    {
        return $query->with(['media', 'category', 'author']);
    }
    public function scopePost(Builder $query): Builder
    {
        return $query->where('type', 'post');
    }
    public function scopeOpini(Builder $query): Builder
    {
        return $query->where('type', 'opini');
    }
    public function scopeVideo(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }
}
