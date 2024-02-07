<?php

namespace App\Repositories\Eloquent;

use App\Models\PostLike;
use App\Models\User;
use App\Repositories\Contracts\LikeRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\AuthenticationService;

class EloquentLikeRepository implements LikeRepositoryInterface
{
    public function getByPostIdAndUserId($postId,$userId): ?PostLike
    {
        return PostLike::where('post_id', $postId)->where('user_id', $userId)->first();
    }

    public function toggleLike($postId,$userId, PostRepositoryInterface $postRepository,AuthenticationService $auth,$request)
    {   
        $user = $auth->getUserIfAuthenticated($request);
        $post = $postRepository->getById($postId,$userId=$user->id);// Post::where('id', $postId)->first();
                
        if ($post) {
            $post_like = PostLike::where('post_id', $postId)->where('user_id', $userId)->first();
            if ($post_like) {
        
                $post_like->delete();

                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'like deletes succesfully',
                    ], 200);
                } 
                return true;
            } else {
                PostLike::create([
                    'post_id' => $postId,
                    'user_id' => $userId
                ]);
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'like created succesfully',
                    ], 200);
                }
                return true; 
            }
        } else {
            return response()->json([
                'message' => 'Posts not found'
            ], 400);
        }
    }
}