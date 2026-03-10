<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SidebarAds;
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

        // dd($otherArticles[0]->category->name);
        return view('post_detail', compact('post', 'title', 'description', 'categories', 'otherArticles', 'sidebarAds', 'beritaPopulers', 'navbarCategories'));
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
}
