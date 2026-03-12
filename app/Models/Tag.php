<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $guarded = [];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    protected static function booted()
    {
        // otomatis membuat huruf kecil
        static::creating(function ($tag) {
            $tag->name = strtolower($tag->name);
        });

        // otomatis membuat slug
        static::creating(function ($tag) {
            if (!$tag->slug) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
