<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/galleries', [ApiController::class, 'index']);
Route::post('/galleries/upload', [ApiController::class, 'upload']);
Route::get('/galleries/id/{id}', [ApiController::class, 'show']);
