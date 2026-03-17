<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // $this->clearCache();
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        // $this->clearCache();
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        // $this->clearCache();
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    protected function clearCache()
    {
        /*
    |--------------------------------------------------------------------------
    | Clear All Related Cache
    |--------------------------------------------------------------------------
    |
    | Menghapus semua cache yang berkaitan dengan Post
    | agar data selalu fresh setelah create/update/delete
    |
    */

        // foreach (config('cache_keys.posts') as $key) {
        //     Cache::forget($key);
        // }
    }
}
