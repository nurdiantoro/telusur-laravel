<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PostCategory extends Model
{
    protected $table = 'post_categories';

    protected $guarded = [];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('navbar_categories_cache');
        });

        static::updated(function () {
            Cache::forget('navbar_categories_cache');
        });

        static::deleted(function () {
            Cache::forget('navbar_categories_cache');
        });
    }

    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_id')->where('is_navbar', true)->orderBy('sort_order', 'asc');
    }
}
