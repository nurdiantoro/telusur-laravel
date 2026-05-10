<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushNotificationController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint'    => 'required|url',
            'p256dh_key'  => 'nullable|string',
            'auth_token'  => 'nullable|string',
        ]);

        try {
            PushSubscription::saveSubscription([
                'endpoint'    => $validated['endpoint'],
                'p256dh_key'  => $validated['p256dh_key'] ?? null,
                'auth_token'  => $validated['auth_token'] ?? null,
                'user_agent'  => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil berlangganan notifikasi.',
            ]);
        } catch (\Exception $e) {
            Log::error('Push subscribe error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan subscription.',
            ], 500);
        }
    }

    /**
     * Hapus subscription (user unsubscribe).
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|url',
        ]);

        try {
            PushSubscription::removeSubscription($validated['endpoint']);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil berhenti berlangganan notifikasi.',
            ]);
        } catch (\Exception $e) {
            Log::error('Push unsubscribe error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus subscription.',
            ], 500);
        }
    }

    /**
     * Kembalikan VAPID public key ke frontend
     * (dibutuhkan browser untuk subscribe).
     */
    public function vapidPublicKey(): JsonResponse
    {
        $key = env('VAPID_PUBLIC_KEY')
            ?? config('webpush.vapid.public_key')
            ?? 'ISI_LANGSUNG_PUBLIC_KEY_KAMU_DISINI';

        return response()->json([
            'public_key' => $key,
        ]);
    }
}
