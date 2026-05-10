<?php

namespace App\Jobs;

use App\Models\AnonymousPushSubscriber;
use App\Models\Post;
use App\Notifications\PostsNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendPostNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        AnonymousPushSubscriber::chunk(100, function ($subscribers) {

            Log::channel('post_log')->info("Mulai kirim notifikasi: {$this->post->title}");

            foreach ($subscribers as $subscriber) {
                try {

                    Log::channel('post_log')->info("Kirim ke subscriber ID: {$subscriber->id}");

                    $subscriber->notify(
                        new PostsNotification(
                            $this->post->title,
                            route(
                                'post.detail',
                                [
                                    'category' => $this->post->category->slug,
                                    'slug' => $this->post->slug
                                ]
                            )
                        )
                    );

                    Log::channel('post_log')->info("Berhasil kirim ke subscriber ID: {$subscriber->id}");
                } catch (\Throwable $e) {

                    Log::channel('post_log')->error("Gagal kirim notif", [
                        'subscriber_id' => $subscriber->id,
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            Log::channel('post_log')->info("Selesai kirim notifikasi: {$this->post->title}");
        });
    }
}
