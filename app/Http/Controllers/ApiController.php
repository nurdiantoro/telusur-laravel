<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function test()
    {
        $start = microtime(true);
        register_shutdown_function(function () use ($start) {
            $total = (microtime(true) - $start) * 1000;
            // Log waktu eksekusi ke file sementara
            file_put_contents('timer.txt', "Total Boot Time: " . round($total, 2) . " ms\n", FILE_APPEND);
        });
        return [
            'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB',
            'laravel_start' => round((microtime(true) - LARAVEL_START) * 1000, 2) . ' ms'
        ];
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        $galleries = Gallery::with('media')
            ->when(
                $search,
                fn($q) =>
                $q->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => $galleries->map(fn($g) => [
                'id' => $g->id,
                'title' => $g->title,
                'thumbnail' => $g->spatie_thumbnail,
            ]),
            'meta' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
            ]
        ]);
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'thumbnail' => $gallery->spatie_thumbnail,
            ]
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2480'
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->save();

        $gallery
            ->addMediaFromRequest('file')
            ->toMediaCollection('imagesCollection');

        return response()->json([
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'thumbnail' => $gallery->spatie_thumbnail,
            ]
        ]);
    }
    /*
    |--------------------------------------------------------------------------
    | API untuk Berita Utama
    |--------------------------------------------------------------------------
    |
    | Berita utama diambil berdasarkan publish_time dalam 7 hari terakhir, dengan limit 10.
    | Jika dalam 7 hari terakhir kurang dari 10 berita, maka akan diambil berita
    | tambahan berdasarkan publish_time terdepan (tanpa batasan waktu)
    | untuk melengkapi total 10 berita.
    |
    */
    public function berita_utama()
    {
        // clear cache
        // Cache::forget('berita_utama_cache');
        $data = Cache::tags(['posts'])->remember('berita_utama_cache', 60, function () {
            $posts = Post::post()
                ->where('publish_time', '>=', now()->subDays(7))
                ->where('headline', true)
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time'
                ])
                ->limit(10)
                ->get();

            if ($posts->count() < 10) {
                $excludeIds = $posts->pluck('id');

                $morePosts = Post::post()
                    ->whereNotIn('id', $excludeIds)
                    ->where('headline', true)
                    ->select([
                        'id',
                        'title',
                        'slug',
                        'category_id',
                        'gallery_id',
                        'publish_time'
                    ])
                    ->limit(10 - $posts->count())
                    ->get();

                $posts = $posts->merge($morePosts);
            }

            return $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'category' => [
                        'slug' => $post->category?->slug
                    ],
                    'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            });
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
    /*
    |--------------------------------------------------------------------------
    | API untuk Berita Terbaru
    |--------------------------------------------------------------------------
    |
    | Berita utama diambil berdasarkan publish_time dalam 7 hari terakhir, dengan limit 10.
    | Jika dalam 7 hari terakhir kurang dari 10 berita, maka akan diambil berita
    | tambahan berdasarkan publish_time terdepan (tanpa batasan waktu)
    | untuk melengkapi total 10 berita.
    |
    */
    public function berita_terbaru()
    {
        $limit = 20;

        $result = Post::post()
            ->select([
                'id',
                'title',
                'slug',
                'category_id',
                'gallery_id',
                'publish_time'
            ])
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => collect($result->items())->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug
                    ],
                    'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            }),
            'pagination' => [
                'current_page' => $result->currentPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
                'last_page' => $result->lastPage(),
                'next_page_url' => $result->nextPageUrl(),
                'prev_page_url' => $result->previousPageUrl(),
            ]
        ], 200);
    }

    public function berita_terbaru_tanpa_pagination($limit = 9)
    {
        Cache::forget('berita_terbaru_cache');
        $limit = (int) $limit;
        $result = Cache::tags(['posts'])->remember('berita_terbaru_cache_' . $limit, 60, function () use ($limit) {
            $posts = Post::post()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time'
                ])
                ->paginate($limit);

            return $posts;
        });

        return response()->json([
            'status' => 'success',
            'data' => collect($result->items())->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug
                    ],
                    'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            })
        ], 200);
    }
    /*
    |--------------------------------------------------------------------------
    | API untuk Berita Populer
    |--------------------------------------------------------------------------
    |
    | Berita populer diambil berdasarkan jumlah views terbanyak dalam 7 hari terakhir.
    | Jika dalam 7 hari terakhir kurang dari 10 berita, maka akan diambil berita
    | tambahan berdasarkan jumlah views terbanyak dalam waktu 30 hari terakhir.
    | jika masih kurang dari 10 berita, maka akan diambil berita tambahan
    | berdasarkan jumlah views terbanyak tanpa batasan waktu
    */
    public function berita_populer($limit = 9)
    {
        $limit = (int) $limit;
        $data = Cache::tags(['posts'])->remember('berita_populer_cache_' . $limit, 60, function () use ($limit) {

            $baseQuery = Post::post()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time',
                    'views'
                ])
                ->with([
                    'category:id,name,slug',
                    'gallery:id'
                ]);

            // STEP 1: 7 hari terakhir
            $posts = (clone $baseQuery)
                ->where('publish_time', '>=', now()->subDays(7))
                ->orderByDesc('views')
                ->limit($limit)
                ->get();

            // STEP 2: fallback 30 hari
            if ($posts->count() < $limit) {
                $excludeIds = $posts->pluck('id');

                $morePosts = (clone $baseQuery)
                    ->where('publish_time', '>=', now()->subDays(30))
                    ->whereNotIn('id', $excludeIds)
                    ->orderByDesc('views')
                    ->limit($limit - $posts->count())
                    ->get();

                $posts = $posts->merge($morePosts);
            }

            // STEP 3: fallback all time
            if ($posts->count() < $limit) {
                $excludeIds = $posts->pluck('id');

                $morePosts = (clone $baseQuery)
                    ->whereNotIn('id', $excludeIds)
                    ->orderByDesc('views')
                    ->limit($limit - $posts->count())
                    ->get();

                $posts = $posts->merge($morePosts);
            }

            return $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'views' => $post->views,
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug
                    ],
                    'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            });
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function berita_video($limit = 8)
    {
        $limit = (int) $limit;

        $data = Cache::tags(['posts'])->remember('berita_video_cache_' . $limit, 60, function () use ($limit) {
            $posts = Post::video()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time',
                    'video_url'
                ])
                ->limit($limit)
                ->get();

            return $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug
                    ],
                    'video_url' => 'https://www.youtube.com/embed/' . $post->video_url,
                    'thumbnail' => 'https://img.youtube.com/vi/' . $post->video_url . '/hqdefault.jpg' ?? asset('img/no_image.webp')
                    // 'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            });
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function berita_opini($limit = 9)
    {
        $limit = (int) $limit;

        $data = Cache::tags(['posts'])->remember('berita_opini_cache_' . $limit, 60, function () use ($limit) {
            $posts = Post::opini()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time',
                ])
                ->limit($limit)
                ->get();

            return $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'publish_time' => $post->publish_time->diffForHumans(),
                    'category' => [
                        'name' => $post->category?->name,
                        'slug' => $post->category?->slug
                    ],
                    'thumbnail' => $post->gallery?->spatie_thumbnail ?? asset('img/no_image.webp')
                ];
            });
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
