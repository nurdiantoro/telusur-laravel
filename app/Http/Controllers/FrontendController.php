<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    protected $categories;

    public function __construct()
    {
        $this->categories = PostCategory::limit(12)->get();
    }

    public function index()
    {
        $categories = $this->categories;
        $title = 'Telusur - Jelajahi Dunia dengan Mudah';
        $description = 'Telusur adalah platform pencarian yang membantu Anda menemukan informasi, tempat, dan layanan dengan mudah. Jelajahi dunia dengan Telusur!';

        $post = Post::with(['media', 'postCategories'])->find(25);

        $beritaPopulers = Post::with(['media', 'main_category', 'author',])
            ->latest('publish_time')
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(6)
            ->get();

        $beritaTerbaru = Post::with(['media', 'main_category', 'author',])
            ->latest('publish_time')
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(12)
            ->get();

        // dd($beritaPopulers);

        return view('index', compact('title', 'description', 'categories', 'post', 'beritaPopulers', 'beritaTerbaru'));
    }

    public function postDetail($categorySlug, $postSlug)
    {
        $categories = $this->categories;
        $post = Post::where('slug', $postSlug)->first();

        $title = $post->title . ' - Telusur';
        $description = Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($post->content))),
            155
        );

        $otherArticles = Post::with(['media', 'main_category', 'author'])
            ->where('id', '!=', $post->id)
            ->latest('publish_time')
            ->where('category_id', $post->category_id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(3)
            ->get();

        // dd($otherArticles[0]->main_category->name);
        return view('post_detail', compact('post', 'title', 'description', 'categories', 'otherArticles'));
    }

    public function postByCategory($slug)
    {
        $categories = $this->categories;
        $category = PostCategory::where('slug', $slug)->firstOrFail();
        $posts = Post::with(['media'])
            ->select('id', 'title', 'slug', 'publish_time')
            ->latest('publish_time')
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->paginate(10);

        // dd($posts);
        return view('post_category', compact('category', 'posts', 'categories'));
    }
}
