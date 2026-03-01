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
        $post = Post::find('216'); // atau lewat route model binding
        $cover = $post->getFirstMediaUrl('cover'); // default file asli
        $coverWebp = $post->getFirstMediaUrl('cover', 'webp'); // versi conversion 'webp'
        $coverThumb = $post->getFirstMediaUrl('cover', 'thumb'); // versi thumbnail

        // dd($post->getFirstMedia('cover')->getPath('webp'));

        return view('index', compact('title', 'description', 'categories', 'post', 'cover', 'coverWebp', 'coverThumb'));
    }
}
