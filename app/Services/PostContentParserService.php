<?php

namespace App\Services;

use App\Models\Post;

class PostContentParserService
{
    public function parse(Post $post): string
    {
        // setiap berapa paragraf muncul "Baca juga"
        $insertEvery = 4;

        // pecah content per paragraf
        $contentParts = collect(explode('</p>', $post->content))
            ->filter()
            ->values();

        // hitung kebutuhan related post
        $relatedNeeded = max(1, floor($contentParts->count() / $insertEvery));
        // dd($relatedNeeded);

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $relatedQuery = Post::query()->where('id', '!=', $post->id);

        /*
        |--------------------------------------------------------------------------
        | Logic berdasarkan type post
        |--------------------------------------------------------------------------
        */

        if ($post->type == 'post') {
            $relatedQuery->post()
                ->where('category_id', $post->category_id);
        } elseif ($post->type == 'opini') {
            $relatedQuery->opini();
        } elseif ($post->type == 'video') {
            $relatedQuery->video();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil related posts
        |--------------------------------------------------------------------------
        */

        $relatedPosts = $relatedQuery
            ->latest('publish_time')
            ->take($relatedNeeded)
            ->get()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Generate final content
        |--------------------------------------------------------------------------
        */

        $finalContent = '';
        $relatedIndex = 0;
        foreach ($contentParts as $index => $part) {

            // kembalikan </p>
            $finalContent .= $part . '</p>';

            // setiap 4 paragraf inject related post
            if (
                ($index + 1) % $insertEvery == 0 &&
                isset($relatedPosts[$relatedIndex])
            ) {
                $related = $relatedPosts[$relatedIndex];
                $finalContent .= view('components.related-post', compact('related'))->render();
                $relatedIndex++;
            }
        }

        return $finalContent;
    }
}
