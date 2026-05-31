<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Infographic extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // Hapus cache kalau ada perubahan
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('infographics_cache');
        });

        static::deleted(function () {
            Cache::forget('infographics_cache');
        });
    }
}
