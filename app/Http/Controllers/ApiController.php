<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function index(Request $request)
    {
        $search = $request->get('search');

        $galleries = Gallery::with('media')
            ->when(
                $search,
                fn($q) =>
                $q->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(12);

        return response()->json([
            'data' => $galleries->map(fn($g) => [
                'id' => $g->id,
                'title' => $g->title,
                'thumbnail' => $g->spatie_thumbnail,
            ]),
            'meta' => [
                'current_page' => $galleries->currentPage(),
                'last_page' => $galleries->lastPage(),
            ]
        ]);
    }

    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'thumbnail' => $gallery->spatie_thumbnail,
            ]
        ]);
    }

    function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2480'
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->file('file')->getClientOriginalName();
        $gallery->save();

        $gallery
            ->addMediaFromRequest('file')
            ->toMediaCollection('imagesCollection');

        return response()->json([
            'data' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'thumbnail' => $gallery->spatie_thumbnail,
            ]
        ]);
    }
}
