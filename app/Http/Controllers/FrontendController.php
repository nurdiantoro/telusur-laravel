<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SidebarAds;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    protected $categories;
    protected $navbarCategories;
    protected $sidebarAds;
    protected $beritaPopulers;

    public function __construct()
    {
        $this->categories = PostCategory::all();
        $this->navbarCategories = PostCategory::whereNull('parent_id')
            ->where('is_navbar', true)
            ->with('children')
            ->orderBy('sort_order')
            ->get();
        $this->sidebarAds = SidebarAds::orderBy('sort_order')->get();
        $this->beritaPopulers = Post::with(['media', 'category', 'author',])
            ->latest('publish_time')
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(6)
            ->get();
    }

    public function index()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        $title = 'Telusur - Jelajahi Dunia dengan Mudah';
        $description = 'Telusur adalah platform pencarian yang membantu Anda menemukan informasi, tempat, dan layanan dengan mudah. Jelajahi dunia dengan Telusur!';

        $post = Post::with(['media'])->find(25);

        $beritaTerbaru = Post::with(['media', 'category', 'author',])
            ->latest('publish_time')
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(12)
            ->get();

        // dd($beritaPopulers);

        return view('index', compact('title', 'description', 'categories', 'post', 'beritaPopulers', 'beritaTerbaru', 'sidebarAds', 'navbarCategories'));
    }

    public function postDetail($categorySlug, $postSlug)
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        $post = Post::where('slug', $postSlug)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->with('tags')
            ->first();

        $title = $post->title . ' - Telusur';
        $description = Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($post->content))),
            155
        );

        $otherArticles = Post::with(['media', 'category', 'author'])
            ->where('id', '!=', $post->id)
            ->latest('publish_time')
            ->where('category_id', $post->category_id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(3)
            ->get();

        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'approved')
            ->get();

        // dd($otherArticles[0]->category->name);
        return view('post_detail', compact('post', 'title', 'description', 'categories', 'otherArticles', 'sidebarAds', 'beritaPopulers', 'navbarCategories', 'comments'));
    }

    public function postByCategory($slug)
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        $category = PostCategory::where('slug', $slug)->firstOrFail();
        $posts = Post::with(['media'])
            ->select('id', 'title', 'slug', 'publish_time')
            ->latest('publish_time')
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->paginate(10);

        // dd($posts);
        return view('post_category', compact('category', 'posts', 'categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function postByTag($slug)
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        $category = Tag::where('slug', $slug)->firstOrFail();
        $posts = $category->posts()
            ->with(['category', 'tags'])
            ->latest('publish_time')
            ->paginate(10);

        return view('post_category', compact('category', 'posts', 'categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function kebijakan()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        return view('kebijakan', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function pedoman()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        return view('pedoman', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function disclaimer()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        return view('disclaimer', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function about()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

        return view('about', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
    }

    public function terms()
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

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
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }

    public function postSearch(Request $request)
    {
        $categories = $this->categories;
        $navbarCategories = $this->navbarCategories;
        $sidebarAds = $this->sidebarAds;
        $beritaPopulers = $this->beritaPopulers;

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
            return redirect()->route('home');
        }

        return view('post_search', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories', 'posts'));
    }
}
