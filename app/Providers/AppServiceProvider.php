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
        Event::listen(\Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent::class, function (\Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent $event) {
            $media = $event->media;
            if ($media->model_type === \App\Models\Gallery::class) {
                $gallery = \App\Models\Gallery::find($media->model_id);
                if ($gallery) {
                    $gallery->update([
                        'gallery' => $media->getUrl(),
                    ]);
                }
            }
        });

        Event::listen(\Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent::class, function (\Spatie\MediaLibrary\Conversions\Events\ConversionHasBeenCompletedEvent $event) {
            $media = $event->media;
            $conversion = $event->conversion;
            if ($media->model_type === \App\Models\Gallery::class) {
                $gallery = \App\Models\Gallery::find($media->model_id);
                if ($gallery) {
                    if ($conversion->getName() === 'preview') {
                        $gallery->update([
                            'spatie_preview' => $media->getUrl('preview'),
                        ]);
                    } elseif ($conversion->getName() === 'thumbnail') {
                        $gallery->update([
                            'spatie_thumbnail' => $media->getUrl('thumbnail'),
                        ]);
                    }
                }
            }
        });

        \Spatie\MediaLibrary\MediaCollections\Models\Media::deleted(function ($media) {
            if ($media->model_type === \App\Models\Gallery::class) {
                $gallery = \App\Models\Gallery::find($media->model_id);
                if ($gallery) {
                    $nextMedia = $gallery->getFirstMedia('imagesCollection');
                    if ($nextMedia) {
                        $gallery->update([
                            'gallery' => $nextMedia->getUrl(),
                            'spatie_preview' => $nextMedia->getUrl('preview'),
                            'spatie_thumbnail' => $nextMedia->getUrl('thumbnail'),
                        ]);
                    } else {
                        $gallery->update([
                            'gallery' => null,
                            'spatie_preview' => null,
                            'spatie_thumbnail' => null,
                        ]);
                    }
                }
            }
        });
    }
}
