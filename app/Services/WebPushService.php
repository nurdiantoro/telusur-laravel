<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    private WebPush $webPush;

    public function __construct()
    {
        $auth = [
            'VAPID' => [
                'subject'    => config('webpush.vapid.subject'),
                'publicKey'  => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setDefaultOptions([
            'TTL'     => 3600,   // notif berlaku 1 jam jika browser offline
            'urgency' => 'normal',
        ]);
    }

    /**
     * Kirim notifikasi ke semua subscriber ketika ada post baru.
     */
    public function sendNewPostNotification(Post $post): void
    {
        $subscriptions = PushSubscription::all();

        if ($subscriptions->isEmpty()) {
            Log::channel('post_log')->info("No push subscribers found.");
            return;
        }

        // ─── Payload Notifikasi ──────────────────────────────────────────────
        // Ambil excerpt: gunakan kolom excerpt jika ada,
        // fallback ke 100 karakter pertama dari content/body
        $excerpt = $post->excerpt
            ?? Str::limit(strip_tags($post->content ?? $post->body ?? ''), 100);

        // Sesuaikan nama route dengan project kamu
        $postUrl = route('post.detail', [$post->category->slug, $post->slug]); // ← sesuaikan ini

        $payload = json_encode([
            'title'   => $post->title,        // judul artikel
            'body'    => $excerpt,             // ringkasan / excerpt
            'icon'    => asset('img/icon-telusur.webp'), // ← sesuaikan path
            'badge'   => asset('img/logo-telusur-new.png'),  // ← sesuaikan path
            'url'     => $postUrl,             // link langsung ke artikel
            'tag'     => 'post-' . $post->id, // mencegah duplikat notif
        ]);
        // ────────────────────────────────────────────────────────────────────

        $failedEndpoints = [];

        foreach ($subscriptions as $sub) {
            try {
                $subscription = Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'keys'            => [
                        'p256dh' => $sub->p256dh_key,
                        'auth'   => $sub->auth_token,
                    ],
                ]);

                $this->webPush->queueNotification($subscription, $payload);
            } catch (\Exception $e) {
                Log::error("Failed to queue notification for endpoint {$sub->endpoint}: " . $e->getMessage());
            }
        }

        // Kirim semua notifikasi dan tangani response
        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                Log::channel('post_log')->info("Push sent successfully to: {$endpoint}");
            } else {
                $reason = $report->getReason();
                Log::channel('post_log')->warning("Push failed to: {$endpoint}, reason: {$reason}");

                // Hapus subscription yang sudah expired/invalid (410 Gone)
                if ($report->isSubscriptionExpired()) {
                    $failedEndpoints[] = $endpoint;
                    Log::channel('post_log')->info("Removed expired subscription: {$endpoint}");
                }
            }
        }

        // Bersihkan subscription yang sudah tidak valid
        if (!empty($failedEndpoints)) {
            PushSubscription::whereIn('endpoint', $failedEndpoints)->delete();
            Log::channel('post_log')->info("Cleaned up " . count($failedEndpoints) . " expired subscriptions.");
        }

        Log::channel('post_log')->info("Push notifications sent for post: {$post->title}");
    }
}
