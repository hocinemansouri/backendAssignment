<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Models\PostLike;
use App\Repositories\Contracts\PostRepositoryInterface;
use Exception;

use function PHPUnit\Framework\isEmpty;

class EloquentPostRepository implements PostRepositoryInterface

{
    
    public function getAll($filters = null)
    {
        $pagination = $filters['pagination'];
        $keyword = $filters['keyword'];
        $category = $filters['category'];
        $user_id = $filters['user_id'];
        $sortBy = $filters['sortBy'];
        $sortOrder = $filters['sortOrder'];

        $post = Post::withCount(['comments', 'postlikes'])
            ->with(['user:id,name,surname,profile_photo', 'category:id,name'])
            ->orderBY($sortBy, $sortOrder)->paginate($pagination);

        $keyword ?? $post->where('title', 'like', '%' . $keyword . '%');

        $user_id ?? $post->where('user_id', $user_id);

        $category ?? $post->where('category', function ($query) use ($category) {
            $query->where('name', $category);
        });

        return $post ?? [];
    }

    public function getById($postId, $withCount=null, $with=null, $userId = null): ?Post
    {
            isEmpty($withCount)? $withCount=['comments', 'postlikes']:$withCount;
            isEmpty($with)? $with=['user', 'category', 'comments', 'postlikes']:$with;
            $post = Post::withCount($withCount)->with($with)->where('id', $postId)->first();
            if($post) $post->liked_by_current_user = PostLike::where('user_id', $userId)->where('post_id', $postId)->first() ?? false;
            return $post;
    }

    public function getDeletedPosts()
    {
        return Post::onlyTrashed()->with(['user','category'])->get();
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update($postId,$data): Post
    {
        $post = Post::where('id',$postId)->first();
        $post->update($data);
        return $post;
    }

    public function delete($post)
    {   
            if($post) {
                return $post->delete();
            }else{
                return response()->json([
                    'message'=>'Post not found!',
                ]);
            }
    }

    public function restoreDeletePost($postId): ?bool
    {
        try{Post::withTrashed()->find($postId)->restore();return true;}catch(Exception){return false;}
    }
    
}
