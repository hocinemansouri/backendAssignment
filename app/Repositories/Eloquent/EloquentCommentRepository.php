<?php

namespace App\Repositories\Eloquent;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Exception;

use function PHPUnit\Framework\isEmpty;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function getAll()
    {
        return Comment::all();
    }
    
    public function getById($commentId,$with=null): ?Comment
    {
        isEmpty($with)? $with=['user', 'post']:$with;  
        return Comment::where('id',$commentId)->with($with)->first();
    }

    public function getByPostId($postId, $with=null){
        isEmpty($with)? $with=['comments']:$with;   
        $comments = Post::with($with)->where('id', $postId)->orderBy('id', 'desc')->paginate(10);
        return $comments;
    }

    public function getDeletedComments()
    {
        return Comment::onlyTrashed()->get();
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);
        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function restoreDeletedComment($commentId): bool
    {
        try{Comment::withTrashed()->find($commentId)->restore();return true;}catch(Exception){return false;}
    }
}
