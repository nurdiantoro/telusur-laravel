<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /*
    |
    |
    | Jalankan ini kalau ada publis post
    | 1. Cari post yang statusnya pending
    | 2. Cari post yang publish_time <= sekarang
    | 3. Update statusnya jadi published
    | 4. index ke searchable()
    | 5. hapus cache
    */
    public function handle()
    {
        $posts = Post::where('status', 'pending')
            ->whereNotNull('publish_time')
            ->where('publish_time', '<=', now())
            ->get();

        Log::info("Found {$posts->count()} posts to publish");

        $index = 1;
        foreach ($posts as $post) {
            Log::info("#{$index} Title Post : {$post->title}");
            Log::info("#{$index} publish_time : {$post->publish_time}");

            $post->update(['status' => 'published',]);
            $post->searchable();
            Cache::tags(['posts'])->flush();

            $index++;
        }

        Log::info("published done!");
    }
}
