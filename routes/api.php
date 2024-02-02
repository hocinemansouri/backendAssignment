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
    Route::post('auth/register',[AuthController::class, 'register']); // user registration
    Route::post('auth/login',[AuthController::class, 'login']); // user login
    Route::middleware('auth:sanctum')->post('auth/logout',[AuthController::class, 'logout']); // user logout (currentAccessToken->delete)
    Route::middleware('auth:sanctum')->get('auth/user', [AuthController::class, 'user']); // get current user info by token
    
    // add category
    Route::middleware('auth:sanctum')->post('category/create',[CategoryController::class,'create']);
    
    // *****    Blog post    *****
    // create  (authenticated only, admin or moderator only)
    Route::middleware('auth:sanctum')->post('post/create',[PostController::class, 'create']);
    // select all (public access)
    Route::get('/post',[PostController::class, 'list']);
    // select one (public access)
    Route::get('/post/{id}',[PostController::class, 'details']);
    // edit (authenticated only, admin or moderator only)
    Route::middleware('auth:sanctum')->put('post/{id}/update',[PostController::class, 'update']);
    // delete (authenticated only, admin or moderator only)
    Route::middleware('auth:sanctum')->delete('post/{id}/delete',[PostController::class, 'delete']);
    // restore deleted post  (admin only)
    Route::middleware('auth:sanctum')->post('post/{id}/restorePost',[PostController::class, 'restorePost']);
    
    
    // *****    Comment    *****
    // create (authenticated only)
    Route::middleware('auth:sanctum')->post('post/{post_id}/comments/create',[CommentController::class, 'create']);
    // select all (public access)
    Route::get('post/{post_id}/comments',[CommentController::class, 'list']);
    // update (authenticated only)
    Route::middleware('auth:sanctum')->put('/comments/{comment_id}/update',[CommentController::class, 'update']);
    // delete (authenticated only)
    Route::middleware('auth:sanctum')->delete('/comments/{comment_id}/delete',[CommentController::class, 'delete']);
    // restore deleted comment (Admin only)
    Route::middleware('auth:sanctum')->post('/comments/{comment_id}/restoreComment',[CommentController::class, 'restoreComment']);
    
    // *****    Trash bin (admin only)    *****
    // list deleted posts
    Route::middleware('auth:sanctum')->get('deleted/posts',[PostController::class, 'deletedPosts']);
    // list deleted comments
    Route::middleware('auth:sanctum')->get('deleted/comments',[CommentController::class, 'deletedComments']);

    // *****    Like    *****
    // create (authenticated only)
    Route::middleware('auth:sanctum')->post('post/{post_id}/toggle-like',[PostController::class, 'toggle_like']);

    // *****    Profile    *****
    // change password  (authenticated only)
    Route::middleware('auth:sanctum')->post('profile/change-password',[ProfileController::class, 'change_password']);
    // update
    Route::middleware('auth:sanctum')->post('profile/update-profile',[ProfileController::class, 'update_profile']);
});
