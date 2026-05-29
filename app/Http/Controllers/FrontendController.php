<?php

namespace App\Http\Controllers;

use App\Models\Adsense;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SidebarAds;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Services\PostContentParserService;
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $adsense = Adsense::where('slug', 'inlist')->first();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */
        $beritaUtama = Cache::remember('berita_utama_carousel_cache', 60, function () {
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
        });

        $beritaVideo = Cache::remember('berita_video_cache', 60, function () {
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
                ->limit(8)
                ->get();

            return $posts;
        });

        $beritaFoto = Cache::remember('berita_foto_cache', 60, function () {
            $posts = Post::post()
                ->select([
                    'id',
                    'title',
                    'slug',
                    'category_id',
                    'gallery_id',
                    'publish_time',
                    'video_url'
                ])
                ->where('category_id', 19) // ID kategori Foto
                ->limit(8)
                ->get();

            return $posts;
        });

        $suggestTags = Tag::inRandomOrder()
            ->limit(5)
            ->get();

        return view('index', compact(
            'categories',
            'navbarCategories',
            'sidebarAds',
            'beritaUtama',
            'beritaVideo',
            'beritaFoto',
            'suggestTags',
            'adsense'
        ));
    }

    public function postDetail($categorySlug, $postSlug, PostContentParserService $parser)
    {
        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Template
        |--------------------------------------------------------------------------
        */
        /*
        |--------------------------------------------------------------------------
        | Variable Template Page
        |--------------------------------------------------------------------------
        |
        */
        $categories = Cache::remember('categories_cache', 60, function () {
            return PostCategory::orderBy('name')
                ->select('name', 'slug')
                ->get();
        });
        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });
        $sidebarAds = SidebarAds::orderBy('sort_order')->get();
        $adsense = Adsense::where('slug', 'inarticle2')->first();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */
        $adsense = Adsense::where('slug', 'inarticle2')->first();

        /*
        |--------------------------------------------------------------------------
        | Variable Detail Post
        |--------------------------------------------------------------------------
        |
        */
        $post           = Post::where('slug', $postSlug)->firstOrFail();
        $title          = $post->title;
        $thumbnail      = $post->gallery?->spatie_preview ?: asset('img/no_image.webp');
        $description    = Str::limit(html_entity_decode(trim(preg_replace('/\s+/', ' ', strip_tags($post->content)))), 155);
        $parsedContent  = $parser->parse($post);
        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'approved')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Variable Other Post
        |--------------------------------------------------------------------------
        |
        | 1. cek dulu berdasarkan type post nya
        | 2. baru cek berdasarkan kategori
        | (NEXT nanti diubah diubah jadi scoring kalo udah ada meilisearch)
        |
        */
        $otherPostsQuery = Post::where('id', '!=', $post->id);

        if ($post->type == 'post') {
            $otherPostsQuery->post()
                ->where('category_id', $post->category_id);
        } elseif ($post->type == 'opini') {
            $otherPostsQuery->opini();
        } elseif ($post->type == 'video') {
            $otherPostsQuery->video();
        }
        $otherPosts = $otherPostsQuery
            ->take(30)
            ->get()
            ->shuffle()
            ->take(8);

        /*
        |--------------------------------------------------------------------------
        | Blade View
        |--------------------------------------------------------------------------
        |
        */

        return view('post_detail', compact(
            'post',
            'title',
            'thumbnail',
            'description',
            'parsedContent',
            'comments',
            'categories',
            'sidebarAds',
            'navbarCategories',
            'adsense',
            'otherPosts',
        ));
    }

    /*
    |
    |
    |
    |
    |
    |--------------------------------------------------------------------------
    | Semua Controller yang pake View post_index.blade.php
    |--------------------------------------------------------------------------
    */
    public function index_post()
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $adsense = Adsense::where('slug', 'inlist')->first();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

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
            'adsense',
            'posts'
        ));
    }
    public function opini()
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $adsense = Adsense::where('slug', 'inlist')->first();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

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
            'adsense',
            'posts'
        ));
    }
    public function video()
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = Cache::remember('sidebar_ads_cache', 60, function () {
            return SidebarAds::orderBy('sort_order')->get();
        });

        $adsense = Adsense::where('slug', 'inlist')->first();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

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
            'adsense',
            'posts'
        ));
    }
    public function postByCategory($slug = null)
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $adsense = Adsense::where('slug', 'inlist')->first();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

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
            'adsense',
            'beritaPopulers',
            'navbarCategories'
        ));
    }

    public function postByTag($slug = null)
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
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

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::post()
            ->where('tag')
            ->paginate(10);

        return view('post_category', compact(
            'tag',
            'posts',
            'categories',
            'sidebarAds',
            'adsense',
            'beritaPopulers',
            'navbarCategories'
        ));
    }

    public function postSearch(Request $request)
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        /*
        |
        |
        |--------------------------------------------------------------------------
        | Variable Query
        |--------------------------------------------------------------------------
        */

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
            'adsense',
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
    public function postComment(Request $request, $post_id = null)
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
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

        $navbarCategories = Cache::rememberForever('navbar_categories_cache', function () {
            return PostCategory::with(['children'])
                ->whereNull('parent_id')
                ->where('is_navbar', true)
                ->orderBy('sort_order')
                ->get();
        });

        return view('terms', compact('categories', 'navbarCategories'));
    }
}
