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

        $post = Post::find('25');
        $firstCategory = $post->postCategories()->first();
        $coverWebp = $post->getFirstMediaUrl('preview', 'webp'); // versi conversion 'webp'
        $coverThumb = $post->getFirstMediaUrl('preview', 'thumb'); // versi thumbnail

        $beritaPopulers = Post::with('media')
            ->latest()
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(6)
            ->get();

        $beritaTerbaru = Post::with('media')
            ->latest()
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(12)
            ->get();

        // dd($beritaPopulers);

        return view('index', compact('title', 'description', 'categories', 'post', 'coverWebp', 'coverThumb', 'firstCategory', 'beritaPopulers', 'beritaTerbaru'));
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

        // dd($post);
        return view('post_detail', compact('post', 'title', 'description', 'categories'));
    }
}
