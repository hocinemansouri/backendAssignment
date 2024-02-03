<?php

use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', [PostController::class, 'list']);
Route::get('/profile', [ProfileController::class, 'index']);
Route::get('/signin', [PagesController::class, 'signin']);
Route::get('/signup', [PagesController::class, 'signup']);
Route::get('/post/{id}', [PostController::class, 'details']);
