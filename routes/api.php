<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'],  function () {
    // User Auth Apis
    Route::post('auth/register', [UserController::class,'register']);
    Route::post('auth/login', [UserController::class,'login']); 
    Route::middleware('auth:sanctum')->post('auth/logout', [UserController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('auth/user', [UserController::class, 'user']); 
    
    // Category Apis
    Route::middleware('auth:sanctum')->post('category/create', [CategoryController::class, 'create']);
    Route::middleware('auth:sanctum')->get('category', [CategoryController::class, 'index']);
    Route::middleware('auth:sanctum')->get('category/{id}', [CategoryController::class, 'getById']);
    Route::middleware('auth:sanctum')->delete('category/{category_id}/delete', [CategoryController::class, 'delete']);
    Route::middleware('auth:sanctum')->put('category/{category_id}/update', [CategoryController::class, 'update']);

    // Post Apis
    Route::middleware('auth:sanctum')->post('post/create', [PostController::class, 'create']);
    Route::get('/post', [PostController::class, 'list']);
    Route::middleware('auth:sanctum')->get('/post/{id}', [PostController::class, 'details']);
    Route::middleware('auth:sanctum')->put('post/{id}/update', [PostController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('post/{id}/delete', [PostController::class, 'delete']);
    
    
    Route::get('/showuser/{userId}', [UserController::class, 'showUserProfile']);
    
    // Comment Apis
    Route::middleware('auth:sanctum')->post('post/{post_id}/comments/create', [CommentController::class, 'create']);
    Route::get('post/{post_id}/comments', [CommentController::class, 'list']);
    Route::middleware('auth:sanctum')->put('/comments/{comment_id}/update', [CommentController::class, 'update']);
    Route::middleware('auth:sanctum')->delete('/comments/{comment_id}/delete', [CommentController::class, 'delete']);
    Route::middleware('auth:sanctum')->post('/comments/{comment_id}/restoreComment', [CommentController::class, 'restoreComment']);

    // Trash bin Apis
    Route::middleware('auth:sanctum')->get('deleted/comments', [CommentController::class, 'deletedComments']);
    //Route::middleware('auth:sanctum')->post('post/{id}/restorePost', [PostController::class, 'restorePost']);
    Route::middleware('auth:sanctum')->post('comment/{id}/restoreComment', [CommentController::class, 'restoreComment']);
    Route::middleware('auth:sanctum')->get('/deletedposts', [PostController::class, 'listDeletedPosts']);

    // Like Apis
    Route::middleware('auth:sanctum')->post('post/{post_id}/toggle-like', [PostController::class, 'toggle_like']);

    // Profile Apis
    Route::middleware('auth:sanctum')->post('profile/change-password', [ProfileController::class, 'change_password']);
    Route::middleware('auth:sanctum')->post('profile/update-profile', [ProfileController::class, 'update_profile']);
});

