<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {

        $title = 'Telusur - Jelajahi Dunia dengan Mudah';
        $description = 'Telusur adalah platform pencarian yang membantu Anda menemukan informasi, tempat, dan layanan dengan mudah. Jelajahi dunia dengan Telusur!';

        $categories = PostCategory::limit(12)->get();

        $post = Post::find('1');
        $firstCategory = $post->postCategories()->first();
        $coverWebp = $post->getFirstMediaUrl('preview', 'webp'); // versi conversion 'webp'
        $coverThumb = $post->getFirstMediaUrl('preview', 'thumb'); // versi thumbnail

        $beritaPopulers = Post::with('media')
            ->latest()
            ->where('status', 'published')
            ->where('publish_time', '<=', now())
            ->limit(6)
            ->get();

        return view('index', compact('title', 'description', 'categories', 'post', 'coverWebp', 'coverThumb', 'firstCategory', 'beritaPopulers'));
    }
}
