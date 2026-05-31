<?php

namespace App\Models;

use App\Filament\Resources\PageSettings\PageSettingResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSetting extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // Hapus cache kalau ada perubahan
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('page_settings_cache');
        });

        static::deleted(function () {
            Cache::forget('page_settings_cache');
        });
    }


    // Redirect ke halaman edit jika hanya ada 1 record, karena memang hanya butuh 1 record untuk menyimpan semua settingan
    protected static string $resource = PageSettingResource::class;
    public function mount(): void
    {
        $setting = PageSetting::first();

        $this->redirect(
            PageSettingResource::getUrl('edit', [
                'record' => $setting,
            ])
        );
    }
}
