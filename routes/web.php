<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Route::get('/test', [TestController::class, 'index'])->name('test');

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/kebijakan', [FrontendController::class, 'kebijakan'])->name('kebijakan');
Route::get('/pedoman', [FrontendController::class, 'pedoman'])->name('pedoman');
Route::get('/disclaimer', [FrontendController::class, 'disclaimer'])->name('disclaimer');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/search', [FrontendController::class, 'postSearch'])->name('search');

Route::get('/index', [FrontendController::class, 'index'])->name('index');
Route::get('/opini', [FrontendController::class, 'opini'])->name('opini');

// Prioritas untuk kategori, jika tidak ada maka akan mencari berdasarkan slug
Route::get('/tag/{slug}', [FrontendController::class, 'postByTag'])->name('post.tag');
Route::get('/{category}', [FrontendController::class, 'postByCategory'])->name('post.category');
Route::get('/{category}/{slug}', [FrontendController::class, 'postDetail'])->name('post.detail');


// Method POST
Route::post('/comment/{post_id}', [FrontendController::class, 'postComment'])->name('post.comment');
Route::post('/subscriber', [FrontendController::class, 'postSubscriber'])->name('post.subscriber');
