<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Gallery Media Conversion Listener
        |--------------------------------------------------------------------------
        |
        | Listener ini menangani hasil conversion image dari Spatie Media Library.
        |
        | Setelah conversion selesai, relative path dari file hasil conversion
        | akan disimpan ke tabel galleries.
        |
        | Conversion yang disimpan:
        |
        | 1. preview
        |    - Digunakan untuk gambar pada halaman detail berita
        |    - Disimpan ke kolom: galleries.preview
        |
        | 2. thumbnail
        |    - Digunakan untuk gambar pada listing/card berita
        |    - Disimpan ke kolom: galleries.thumbnail
        |
        | Data yang disimpan bukan full URL, melainkan relative path.
        | Dengan demikian database tidak bergantung pada domain atau storage URL.
        |
        */

        Event::listen(
            \Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent::class,
            function (
                \Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent $event
            ) {
                $media = $event->media;
                $conversion = $event->conversion;

                /*
                |--------------------------------------------------------------------------
                | Validate Media Owner
                |--------------------------------------------------------------------------
                |
                | Pastikan media yang selesai diproses memang dimiliki oleh
                | model Gallery.
                |
                */

                if ($media->model_type !== \App\Models\Gallery::class) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Get Gallery
                |--------------------------------------------------------------------------
                |
                | Ambil Gallery berdasarkan model_id pada record Media.
                |
                */

                $gallery = \App\Models\Gallery::find($media->model_id);

                if (!$gallery) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Get Conversion Path
                |--------------------------------------------------------------------------
                |
                | Ambil absolute path dari file conversion yang baru selesai.
                | Kemudian ubah menjadi relative path terhadap root disk.
                |
                */

                $conversionName = $conversion->getName();
                $conversionPath = $media->getPath($conversionName);

                $diskRoot = config(
                    'filesystems.disks.' . $media->disk . '.root'
                );

                $relativePath = $diskRoot
                    ? ltrim(str_replace($diskRoot, '', $conversionPath), '/\\')
                    : $conversionPath;

                /*
                |--------------------------------------------------------------------------
                | Save Preview Path
                |--------------------------------------------------------------------------
                |
                | Simpan relative path hasil conversion preview ke Gallery.
                |
                */

                if ($conversionName === 'preview') {
                    $gallery->update([
                        'preview' => $relativePath,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Save Thumbnail Path
                |--------------------------------------------------------------------------
                |
                | Simpan relative path hasil conversion thumbnail ke Gallery.
                |
                */

                if ($conversionName === 'thumbnail') {
                    $gallery->update([
                        'thumbnail' => $relativePath,
                    ]);
                }
            }
        );
    }
}
