<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [FrontendController::class, 'index'])->name('home');

// Static Pages
Route::get('/kebijakan', [FrontendController::class, 'kebijakan'])->name('kebijakan');
Route::get('/pedoman', [FrontendController::class, 'pedoman'])->name('pedoman');
Route::get('/disclaimer', [FrontendController::class, 'disclaimer'])->name('disclaimer');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/search', [FrontendController::class, 'postSearch'])->name('search');

// Type Post
Route::get('/index', [FrontendController::class, 'index_post'])->name('index_post');
Route::get('/opini', [FrontendController::class, 'opini'])->name('opini');
Route::get('/video', [FrontendController::class, 'video'])->name('video');

// Tag, Category dan Detail Post
Route::get('/tag/{slug}', [FrontendController::class, 'postByTag'])->name('post.tag');
Route::get('/{category}', [FrontendController::class, 'postByCategory'])->name('post.category');
Route::get('/{category}/{slug}', [FrontendController::class, 'postDetail'])->name('post.detail');

// Method POST
Route::post('/comment/{post_id}', [FrontendController::class, 'postComment'])->name('post.comment');
Route::post('/subscriber', [FrontendController::class, 'postSubscriber'])->name('post.subscriber');
