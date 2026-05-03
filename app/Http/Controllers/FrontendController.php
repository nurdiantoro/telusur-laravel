<?php

namespace App\Http\Controllers;

use App\Models\Adsense;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SidebarAds;
use App\Models\Subscriber;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function index()
    {
        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Template
        |--------------------------------------------------------------------------
        */
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $suggestTags = Tag::inRandomOrder()
            ->limit(5)
            ->get();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Semua Berita
        |--------------------------------------------------------------------------
        */
        $limitBeritaTerbaru = 9;
        $limitBeritaPopuler = 9;
        $limitBeritaVideo = 8;
        $limitBeritaOpini = 9;
        // Cache::forget('berita_utama_carousel_cache');

        $beritaUtama = Cache::remember(
            'berita_utama_carousel_cache',
            60,
            function () {
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

                return $posts;
            }
        );

        $beritaTerbaru = Cache::remember(
            'berita_terbaru_cache_' . $limitBeritaTerbaru,
            60,
            function () use ($limitBeritaTerbaru) {
                $posts = Post::post()
                    ->select([
                        'id',
                        'title',
                        'slug',
                        'category_id',
                        'gallery_id',
                        'publish_time'
                    ])
                    ->limit($limitBeritaTerbaru)
                    ->get();

                return $posts;
            }
        );

        $beritaPopuler = Cache::remember(
            'berita_populer_cache_' . $limitBeritaPopuler,
            60,
            function () use ($limitBeritaPopuler) {
                /*
                |--------------------------------------------------------------------------
                | Base query untuk berita populer
                |--------------------------------------------------------------------------
                */
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

                /*
                |--------------------------------------------------------------------------
                | Ambil berita populer 7 hari terakhir
                |--------------------------------------------------------------------------
                */
                $posts = (clone $baseQuery)
                    ->where('publish_time', '>=', now()->subDays(7))
                    ->orderByDesc('views')
                    ->limit($limitBeritaPopuler)
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | Tambah berita populer 30 hari terakhir jika kurang dari limit
                |--------------------------------------------------------------------------
                */
                if ($posts->count() < $limitBeritaPopuler) {
                    $excludeIds = $posts->pluck('id');

                    $morePosts = (clone $baseQuery)
                        ->where('publish_time', '>=', now()->subDays(30))
                        ->whereNotIn('id', $excludeIds)
                        ->orderByDesc('views')
                        ->limit($limitBeritaPopuler - $posts->count())
                        ->get();

                    $posts = $posts->merge($morePosts);
                }

                /*
                |--------------------------------------------------------------------------
                | Tambah berita populer semua waktu jika kurang dari limit
                |--------------------------------------------------------------------------
                */
                if ($posts->count() < $limitBeritaPopuler) {
                    $excludeIds = $posts->pluck('id');

                    $morePosts = (clone $baseQuery)
                        ->whereNotIn('id', $excludeIds)
                        ->orderByDesc('views')
                        ->limit($limitBeritaPopuler - $posts->count())
                        ->get();

                    $posts = $posts->merge($morePosts);
                }

                return $posts;
            }
        );

        $beritaVideo = Cache::remember(
            'berita_video_cache_' . $limitBeritaVideo,
            60,
            function () use ($limitBeritaVideo) {
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
                    ->limit($limitBeritaVideo)
                    ->get();

                return $posts;
            }
        );

        $beritaOpini = Cache::remember(
            'berita_opini_cache_' . $limitBeritaOpini,
            60,
            function () use ($limitBeritaOpini) {
                $posts = Post::opini()
                    ->select([
                        'id',
                        'title',
                        'slug',
                        'type',
                        'category_id',
                        'gallery_id',
                        'publish_time',
                    ])
                    ->limit($limitBeritaOpini)
                    ->get();

                return $posts;
            }
        );

        /*
        |
        |
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'suggestTags',
            'beritaUtama',
            'beritaTerbaru',
            'beritaPopuler',
            'beritaVideo',
            'beritaOpini'
        ));
    }

    public function postDetail($categorySlug, $postSlug)
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        // Detail Post
        $post           = Post::where('slug', $postSlug)->firstOrFail();
        $title          = $post->title;
        $thumbnail      = $post->gallery?->spatie_preview ?: asset('img/no_image.webp');
        $description    = Str::limit(html_entity_decode(trim(preg_replace('/\s+/', ' ', strip_tags($post->content)))), 155);

        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'approved')
            ->get();

        // Other Article
        if ($post->type == 'post') {
            $otherArticles = Post::post()
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->limit(10)
                ->get();
        } elseif ($post->type == 'opini') {
            $otherArticles = Post::opini()
                ->where('id', '!=', $post->id)
                ->limit(10)
                ->get();
        } elseif ($post->type == 'video') {
            $otherArticles = Post::video()
                ->where('id', '!=', $post->id)
                ->limit(10)
                ->get();
        }

        $adsense = Adsense::where('slug', 'inarticle2')->first();

        return view('post_detail', compact(
            'post',
            'title',
            'description',
            'thumbnail',
            'categories',
            'otherArticles',
            'sidebarAds',
            'navbarCategories',
            'comments',
            'adsense'
        ));
    }

    /*
    |
    |
    |
    |
    |
    |--------------------------------------------------------------------------
    | Semua Controller yang pake View List Search
    |--------------------------------------------------------------------------
    */
    public function index_post()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $limit = 10;
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
        // dd($posts);

        return view('post_index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'posts'
        ));
    }
    public function opini()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $limit = 10;
        $posts = Post::opini()
            ->select([
                'id',
                'title',
                'type',
                'slug',
                'category_id',
                'gallery_id',
                'publish_time'
            ])
            ->paginate($limit);

        return view('post_index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'posts'
        ));
    }
    public function video()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $limit = 10;
        $posts = Post::video()
            ->select([
                'id',
                'title',
                'type',
                'slug',
                'video_url',
                'category_id',
                'gallery_id',
                'publish_time'
            ])
            ->paginate($limit);

        return view('post_index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'posts'
        ));
    }
    public function postByCategory($slug)
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $category = PostCategory::where('slug', $slug)
            ->firstOrFail();

        $posts = Post::post()
            ->where('category_id', $category->id)
            ->paginate(10);

        return view('post_index', compact(
            'category',
            'posts',
            'categories',
            'sidebarAds',
            'beritaPopulers',
            'navbarCategories'
        ));
    }

    public function postByTag($slug)
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::post()
            ->where('tag')
            ->paginate(10);

        return view('post_category', compact(
            'tag',
            'posts',
            'categories',
            'sidebarAds',
            'beritaPopulers',
            'navbarCategories'
        ));
    }

    public function postSearch(Request $request)
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $posts = Post::search($request->search_input)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->orderBy('publish_time', 'desc')
            ->paginate(10)
            ->appends([
                'search_input' => $request->search_input
            ]);

        if (!$request->search_input) {
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian.');
        }

        return view('post_index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'posts'
        ));
    }
    /*
    |
    |
    |
    |
    |
    |--------------------------------------------------------------------------
    | Method POST
    |--------------------------------------------------------------------------
    */
    public function postComment(Request $request, $post_id)
    {
        // Honeypot: jika field ini diisi, berarti bot
        if ($request->filled('jangan_diisi')) {
            return redirect()->back()->with('error', 'Spam terdeteksi.');
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'comment' => 'required|string|max:2000',
        ]);

        // Pastikan post ada
        $post = Post::findOrFail($post_id);

        // Simpan komentar
        Comment::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'post_id' => $post->id,
            'comment' => $validated['comment'],
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }

    public function postSubscriber(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        if ($request->filled('jangan_diisi')) {
            return redirect()->back()->with('error', 'Spam terdeteksi.');
        }

        if (Subscriber::where('email', $validated['email'])->exists()) {
            return redirect()->back()->with('success', 'Berhasil berlangganan.');
        }

        Subscriber::create([
            'email' => $validated['email'],
        ]);

        return redirect()->back()->with('success', 'Berhasil berlangganan.');
    }
    /*
    |
    |
    |
    |
    |
    |--------------------------------------------------------------------------
    | Static page
    |--------------------------------------------------------------------------
    */
    public function kebijakan()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('kebijakan', compact('categories', 'navbarCategories'));
    }

    public function pedoman()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('pedoman', compact('categories', 'navbarCategories'));
    }

    public function disclaimer()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('disclaimer', compact('categories', 'navbarCategories'));
    }

    public function about()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('about', compact('categories', 'navbarCategories'));
    }

    public function terms()
    {
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });

        $navbarCategories = Cache::remember('navbar_categories_cache', 60, function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('terms', compact('categories', 'navbarCategories'));
    }
}
