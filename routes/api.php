<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\PushNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', [ApiController::class, 'test']);

Route::get('/galleries', [ApiController::class, 'index']);
Route::post('/galleries/upload', [ApiController::class, 'upload']);
Route::get('/galleries/id/{id}', [ApiController::class, 'show']);

// API untuk Berita
Route::get('/berita-utama', [ApiController::class, 'berita_utama']);
Route::get('/berita-terbaru/tanpa-pagination/{limit?}', [ApiController::class, 'berita_terbaru_tanpa_pagination']);
Route::get('/berita-terbaru/{page?}', [ApiController::class, 'berita_terbaru']);
Route::get('/berita-populer/{limit?}', [ApiController::class, 'berita_populer']);
Route::get('/berita-video/{limit?}', [ApiController::class, 'berita_video']);
Route::get('/berita-opini/{limit?}', [ApiController::class, 'berita_opini']);

Route::prefix('push')->name('push.')->group(function () {

    // Ambil VAPID public key (tidak butuh auth)
    Route::get('vapid-key', [PushNotificationController::class, 'vapidPublicKey'])
        ->name('vapid-key');

    // Subscribe — simpan subscription baru
    Route::post('subscribe', [PushNotificationController::class, 'subscribe'])
        ->name('subscribe');

    // Unsubscribe — hapus subscription
    Route::post('unsubscribe', [PushNotificationController::class, 'unsubscribe'])
        ->name('unsubscribe');
});
