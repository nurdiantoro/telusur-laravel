<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    public function index()
    {
        $title = 'Telusur - Jelajahi Dunia dengan Mudah';
        $description = 'Telusur adalah platform pencarian yang membantu Anda menemukan informasi, tempat, dan layanan dengan mudah. Jelajahi dunia dengan Telusur!';
        $categories = PostCategory::limit(12)->get();

        $post = Post::with(['media', 'postCategories'])->find(25);

        $beritaPopulers = Post::with(['media', 'main_category', 'author',])
            ->latest()
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(6)
            ->get();

        $beritaTerbaru = Post::with(['media', 'main_category', 'author',])
            ->latest()
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(12)
            ->get();

        // dd($beritaPopulers);

        return view('index', compact('title', 'description', 'categories', 'post', 'beritaPopulers', 'beritaTerbaru'));
    }

    public function postDetail($categorySlug, $postSlug)
    {
        $post = Post::where('slug', $postSlug)->first();

        $title = $post->title . ' - Telusur';
        $description = Str::limit(
            trim(preg_replace('/\s+/', ' ', strip_tags($post->content))),
            155
        );
        $categories = PostCategory::limit(12)->get();

        $otherArticles = Post::with(['media', 'main_category', 'author'])
            ->where('id', '!=', $post->id)
            ->latest()
            ->where('category_id', $post->category_id)
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(3)
            ->get();

        // dd($otherArticles[0]->main_category->name);
        return view('post_detail', compact('post', 'title', 'description', 'categories', 'otherArticles'));
    }
}
