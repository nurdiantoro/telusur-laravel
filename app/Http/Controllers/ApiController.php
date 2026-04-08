<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
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

    public function test()
    {


        // dd(
        //     DB::select("
        //             EXPLAIN SELECT
        //     id, title, slug, category_id, gallery_id, publish_time
        // FROM posts
        // WHERE type = 'post'
        // AND status = 'published'
        // ORDER BY publish_time DESC
        // LIMIT 8

        //         ")
        // );
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

    // API untuk Berita Utama (pagination)
    public function berita_utama()
    {
        $data = Cache::remember('berita_utama', 60, function () {
            return

                $posts = Post::post()
                ->where('publish_time', '>=', now()->subDays(7))
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
        });

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // API untuk Artikel Terbaru (pagination)
    public function artikel_terbaru(Request $request)
    {
        $perPage = 9;
        $artikelTerbaru = Post::post()
            ->orderByDesc('publish_time')
            ->paginate($perPage);

        return response()->json([
            'data' => $artikelTerbaru->items(),
            'meta' => [
                'current_page' => $artikelTerbaru->currentPage(),
                'last_page' => $artikelTerbaru->lastPage(),
            ]
        ]);
    }

    public function berita_populer()
    {
        $berita_populer = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil berita populer',
            'data' => $berita_populer
        ], 200);
    }

    public function berita_video($limit)
    {
        $berita_video = Post::video()
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil berita video',
            'data' => $berita_video
        ], 200);
    }

    public function berita_opini($limit)
    {
        $berita_opini = Post::opini()
            ->limit($limit)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil berita opini',
            'data' => $berita_opini
        ], 200);
    }
}
