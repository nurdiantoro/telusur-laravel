<?php

use App\Http\Controllers\ApiController;
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
Route::get('/berita-populer', [ApiController::class, 'berita_populer']);
Route::get('/post/video/{limit}', [ApiController::class, 'berita_video']);
Route::get('/post/opini/{limit}', [ApiController::class, 'berita_opini']);
Route::get('/artikel-terbaru', [ApiController::class, 'artikel_terbaru']);
