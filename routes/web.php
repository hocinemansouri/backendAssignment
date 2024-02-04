<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PagesController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'list']);

Route::get('/posts', [PostController::class, 'list']);
Route::get('/profile', [ProfileController::class, 'index']);
Route::view('/signin', 'signin');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/post/{post_id}/toggleLike', [PostController::class, 'toggle_like'])->name('toggleLike');
Route::view('/signup', 'signup');
Route::get('/post/{id}', [PostController::class, 'details']);
