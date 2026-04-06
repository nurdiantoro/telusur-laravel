<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        // return 'test';
        $data = Post::where('category_id', null)->select('id', 'title', 'type')->get()->count();

        return response()->json([
            'data' => $data
        ], 200);
    }
}
