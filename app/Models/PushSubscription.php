<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'p256dh_key',
        'auth_token',
        'user_agent',
    ];

    /**
     * Simpan atau update subscription berdasarkan endpoint.
     * Karena anonymous (tidak perlu login), identifikasi via endpoint.
     */
    public static function saveSubscription(array $data): self
    {
        return self::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'p256dh_key' => $data['p256dh_key'] ?? null,
                'auth_token' => $data['auth_token'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
            ]
        );
    }

    /**
     * Hapus subscription berdasarkan endpoint.
     */
    public static function removeSubscription(string $endpoint): void
    {
        self::where('endpoint', $endpoint)->delete();
    }
}
