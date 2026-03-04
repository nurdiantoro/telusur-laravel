<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/{category}/{slug}', [FrontendController::class, 'postDetail'])->name('post.detail');

// Wajib dijalankan saat migrasi
Route::get('/ubahstatus', [MigrationController::class, 'ubahstatus'])->name('home');
Route::get('/ubahtype', [MigrationController::class, 'ubahtype'])->name('home');
