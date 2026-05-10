<?php

// config/webpush.php
// Publish config ini dengan: php artisan vendor:publish --tag="webpush-config"
// Atau buat manual file ini.

return [

    /*
    |--------------------------------------------------------------------------
    | VAPID Keys
    |--------------------------------------------------------------------------
    |
    | VAPID (Voluntary Application Server Identification) digunakan untuk
    | mengidentifikasi server kamu ke push service (seperti FCM untuk Chrome).
    |
    | Generate keys dengan command:
    |   php artisan webpush:vapid
    |
    | Kemudian simpan hasilnya di .env:
    |   VAPID_PUBLIC_KEY=...
    |   VAPID_PRIVATE_KEY=...
    |   VAPID_SUBJECT=mailto:email@domain.com
    |
    */
    'vapid' => [
        'subject'     => env('VAPID_SUBJECT', 'mailto:admin@telusur.com'),
        'public_key'  => env('VAPID_PUBLIC_KEY', 'BKKEhlnQdBd8Mb7VvFIuaCj86TgunHmIQp7q3Dohu7BTxZLY2-VOBDIY_MbWSAVbAaaesUN5T7aiQ_b04av3PB4'),
        'private_key' => env('VAPID_PRIVATE_KEY', 'Gcard9QO0tN2O0-MUMttzr9UtI2yYSsKftUMv7e8JJo'),
    ],

];
