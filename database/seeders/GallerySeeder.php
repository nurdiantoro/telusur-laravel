<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gallery::factory(50)->create()->each(function ($gallery) {

            $url = 'https://picsum.photos/800/600?random=' . rand();

            try {
                $gallery
                    ->addMediaFromUrl($url)
                    ->usingName($gallery->name . '-' . Str::random(5))
                    ->toMediaCollection('imagesCollection');
            } catch (\Exception $e) {
                // skip error
            }
        });
    }
}
