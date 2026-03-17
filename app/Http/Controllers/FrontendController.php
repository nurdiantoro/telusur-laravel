<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SidebarAds;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class FrontendController extends Controller
{

    private function getOtherArticles($post, $limit)
    {
        /*
        |--------------------------------------------------------------------------
        | Other Articles
        |--------------------------------------------------------------------------
        |
        | Other Articles dicari berdasarkan tag dulu
        | kalau gak ada atau kurang, tambahin dari category
        | kalau masih kurang, tambahin dari latest
        |
        */
        $tagIds = $post->tags->pluck('id');

        $tagArticles = Post::with(['media', 'category', 'author'])
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tags.id', $tagIds);
            })
            ->latest('publish_time')
            ->limit($limit)
            ->get();

        $otherArticles = $tagArticles;

        if ($otherArticles->count() < $limit) {

            $remaining = $limit - $otherArticles->count();

            $categoryArticles = Post::with(['media', 'category', 'author'])
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->whereNotIn('id', $otherArticles->pluck('id'))
                ->where('status', 'published')
                ->where('publish_time', '<=', now())
                ->latest('publish_time')
                ->limit($remaining)
                ->get();

            $otherArticles = $otherArticles->merge($categoryArticles);
        }

        if ($otherArticles->count() < $limit) {

            $remaining = $limit - $otherArticles->count();

            $latestArticles = Post::with(['media', 'category', 'author'])
                ->where('id', '!=', $post->id)
                ->whereNotIn('id', $otherArticles->pluck('id'))
                ->where('status', 'published')
                ->where('publish_time', '<=', now())
                ->latest('publish_time')
                ->limit($remaining)
                ->get();

            $otherArticles = $otherArticles->merge($latestArticles);
        }

        return $otherArticles;
    }

    public function index()
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

        $post = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->latestPublished()
            ->first();

        $beritaTerbaru = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->post()
            ->latestPublished()
            ->limit(12)
            ->get();

        $beritaUtama = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->post()
            ->where('publish_time', '>=', now()->subDays(30))
            ->popular()
            ->limit(10)
            ->get()
            ->sortByDesc('publish_time')
            ->values();

        $beritaPopulers = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->popular()
            ->limit(6)
            ->get();

        $opinions = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->opini()
            ->latestPublished()
            ->limit(9)
            ->get();

        $videos = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->video()
            ->latestPublished()
            ->limit(8)
            ->get();

        $suggestTags = Tag::inRandomOrder()
            ->limit(5)
            ->get();

        return view('index', compact(
            'categories',
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

        $beritaPopulers = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->popular()
            ->limit(6)
            ->get();

        $post = Post::with(['tags', 'media', 'category', 'author'])
            ->where('slug', $postSlug)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->firstOrFail();

        $title = $post->title . ' - Telusur';

        $description = Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($post->content))),
            155
        );

        $otherArticles = Post::with(['media', 'category'])
            ->published()
            ->where('id', '!=', $post->id)
            ->latestPublished()
            ->limit(10)
            ->get();

        $comments = Comment::where('post_id', $post->id)
            ->where('status', 'approved')
            ->get();

        return view('post_detail', compact(
            'post',
            'title',
            'description',
            'categories',
            'otherArticles',
            'sidebarAds',
            'beritaPopulers',
            'navbarCategories',
            'comments'
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

        $beritaPopulers = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->popular()
            ->limit(6)
            ->get();

        $category = PostCategory::where('slug', $slug)
            ->firstOrFail();

        $posts = Post::with(['media', 'category', 'author'])
            ->published()
            ->where('category_id', $category->id)
            ->latestPublished()
            ->paginate(10);

        return view('post_category', compact(
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

        $beritaPopulers = Post::with([
            'media',
            'category',
            'author',
        ])
            ->published()
            ->popular()
            ->limit(6)
            ->get();

        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->with(['media', 'category', 'tags', 'author'])
            ->published()
            ->latestPublished()
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
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian.');
        }

        return view('post_search', compact('categories', 'sidebarAds', 'beritaPopulers', 'navbarCategories', 'posts'));
    }
}
