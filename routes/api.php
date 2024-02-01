<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\PostLikeController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Http\Request;
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




Route::group(['prefix' => 'v1'], function () {
    // Auth 
    Route::post('auth/register',[AuthController::class, 'register']);
    Route::post('auth/login',[AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('auth/logout',[AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('auth/user', function (Request $request) {
        return $request->user();
    });

    // PostCategories
    Route::middleware('auth:sanctum')->post('category/create',[CategoryController::class,'create']);
    
    // Post
    // Post Create
    Route::middleware('auth:sanctum')->post('post/create',[PostController::class, 'create']);
    // Post List read by anyone
    Route::get('/post',[PostController::class, 'list']);
    // Get single Post (public)
    Route::get('/post/{id}',[PostController::class, 'details']);
    // Post Edit
    Route::middleware('auth:sanctum')->put('post/{id}/update',[PostController::class, 'update']);
    // Post Delete
    Route::middleware('auth:sanctum')->delete('post/{id}/delete',[PostController::class, 'delete']);
    // Restore deleted Post (Admin only)
    Route::middleware('auth:sanctum')->post('post/{id}/restorePost',[PostController::class, 'restorePost']);
    
    

    // Comments
    // Create (Authenticated only)
    Route::middleware('auth:sanctum')->post('post/{post_id}/comments/create',[CommentController::class, 'create']);
    // List all the comments of a post (public)
    Route::get('post/{post_id}/comments',[CommentController::class, 'list']);
    // Update (Authenticated only)
    Route::middleware('auth:sanctum')->put('/comments/{comment_id}/update',[CommentController::class, 'update']);
    // Delete (Authenticated only)
    Route::middleware('auth:sanctum')->delete('/comments/{comment_id}/delete',[CommentController::class, 'delete']);
    // Restore deleted Comment (Admin only)
    Route::middleware('auth:sanctum')->post('/comments/{comment_id}/restoreComment',[CommentController::class, 'restoreComment']);
    
    // Trash
    // List deleted Posts (Only Admin)
    Route::middleware('auth:sanctum')->get('deleted/posts',[PostController::class, 'deletedPosts']);

    // List deleted Comments (Only Admin)
    Route::middleware('auth:sanctum')->get('deleted/comments',[CommentController::class, 'deletedComments']);




    // Likes
    // Create (authenticated only)
    Route::middleware('auth:sanctum')->post('post/{post_id}/toggle-like',[PostController::class, 'toggle_like']);

    // Profile
    // Change password
    Route::middleware('auth:sanctum')->post('profile/change-password',[ProfileController::class, 'change_password']);
    // Update profile
    Route::middleware('auth:sanctum')->post('profile/update-profile',[ProfileController::class, 'update_profile']);

});
