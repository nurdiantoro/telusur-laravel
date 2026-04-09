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

use function Pest\Laravel\json;

class FrontendController extends Controller
{
    public function index()
    {
        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $post = Post::post()
            ->first();

        $beritaTerbaru = Post::post()
            ->limit(12)
            ->get();

        // Views Terbanyak dalam 1 bulan terakhir
        $beritaUtama = Post::post()
            ->orderByDesc('views')
            ->limit(10)
            ->get()
            ->values();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $opinions = Post::opini()
            ->limit(9)
            ->get();

        $videos = Post::video()
            ->limit(8)
            ->get();

        $suggestTags = Tag::inRandomOrder()
            ->limit(5)
            ->get();

        return view('index', compact(
            'navbarCategories',
            'sidebarAds',
            'post',
            'beritaTerbaru',
            'beritaUtama',
            'beritaPopulers',
            'opinions',
            'videos',
            'suggestTags'
        ));
    }

    public function index_post() {}

    public function postDetail($categorySlug, $postSlug)
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $post = Post::post()
            ->where('slug', $postSlug)
            ->firstOrFail();

        $title = $post->title . ' - Telusur';

        $description = Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($post->content))),
            155
        );

        $otherArticles = Post::post()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->limit(10)
            ->get();

        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'approved')
            ->get();

        $adsense = Adsense::where('slug', 'inarticle2')->first();

        return view('post_detail', compact(
            'post',
            'title',
            'description',
            'categories',
            'otherArticles',
            'sidebarAds',
            'beritaPopulers',
            'navbarCategories',
            'comments',
            'adsense'
        ));
    }
    public function postByCategory($slug)
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

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
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

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

    public function kebijakan()
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::with([
            'media',
            'category',
        ])
            ->published()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('kebijakan', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function pedoman()
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::with([
            'media',
            'category',
        ])
            ->published()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('pedoman', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function disclaimer()
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::with([
            'media',
            'category',
        ])
            ->published()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('disclaimer', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function about()
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::with([
            'media',
            'category',
        ])
            ->published()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('about', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function terms()
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::with([
            'media',
            'category',
        ])
            ->published()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        return view('terms', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

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

    public function postSearch(Request $request)
    {
        $categories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $navbarCategories = PostCategory::with(['children'])
            ->whereNull('parent_id')
            ->where('is_navbar', true)
            ->orderBy('sort_order')
            ->get();

        $sidebarAds = SidebarAds::orderBy('sort_order')->get();

        $beritaPopulers = Post::post()
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $posts = Post::search($request->search_input)
            ->where('type', 'post')
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

        return view('post_search', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories', 'posts'));
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
}
