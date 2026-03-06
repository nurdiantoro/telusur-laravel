<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', function () {
    return '<h1>About Page</h1><p>This is the about page.</p>';
})->name('about');

// Wajib dijalankan saat migrasi
Route::get('/ubahstatus', [MigrationController::class, 'ubahstatus'])->name('home');
Route::get('/ubahtype', [MigrationController::class, 'ubahtype'])->name('home');

// Prioritas untuk kategori, jika tidak ada maka akan mencari berdasarkan slug
Route::get('/{category}', [FrontendController::class, 'postByCategory'])->name('post.category');
Route::get('/{category}/{slug}', [FrontendController::class, 'postDetail'])->name('post.detail');
