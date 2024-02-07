<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PagesController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'list']);
Route::get('/posts', [PostController::class, 'list']);
Route::get('/post/{post_id}/toggleLike', [PostController::class, 'toggle_like'])->name('toggleLike');
Route::get('/post/{id}', [PostController::class, 'details']);
Route::get('/deletedposts', [PostController::class, 'listDeletedPosts']);
Route::get('/post/{id}/restorePost', [PostController::class, 'restorePost'])->name('restorePost');
Route::post('/post/create', [PostController::class, 'create']);

Route::post('/post/{post_id}/comments/create', [CommentController::class, 'create']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::post('/signup', [UserController::class, 'register']);

Route::get('/profile', [ProfileController::class, 'index']);
Route::post('profile/update', [ProfileController::class, 'update_profile']);

Route::view('/updateprofile', 'updateprofile');
Route::view('/signin', 'signin');
Route::view('/signup', 'signup');
Route::view('/404', '404');