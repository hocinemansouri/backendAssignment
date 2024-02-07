<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $postRepository;
    protected $commentRepository;

    public function __construct(PostRepositoryInterface  $postRepository, CommentRepositoryInterface $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }
    public function create(CreateCommentRequest $request, $post_id,AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);        
        if ($user) {
            $post = $this->postRepository->getById($post_id);
            if ($post) {
                $comment = $this->commentRepository->create([
                    'content' => $request->content,
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
                //$comment->load('user');
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'Comment added succesfully',
                        'content' => $comment
                    ], 200);
                } else {
                    return redirect()->back();
                }
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ], 422);
            }
        }
    }

    public function list($post_id)
    {
        $post = $this->postRepository->getById($post_id);
        if ($post) {
            $with = ['user', 'post'];
            
            $comments = $this->commentRepository->getByPostId($post->id, $with);
            
            return response()->json([
                'message' => 'Comment succesfully fetched',
                'data' => $comments
            ], 200);
        } else {
            return response()->json([
                'message' => 'No post found',
            ], 400);
        }
    }
    public function update(UpdateCommentRequest $request, $comment_id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        $comment = $this->commentRepository->getById($comment_id,['user']);      
        
        if ($comment) {
            if ($comment->user_id == $user->id || $user->role == 'admin') {

                $this->commentRepository->update($comment,[
                    'content' => $request->content
                ]);
                return response()->json([
                    'message' => 'Comment succesfully updated',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Unauthorized access',
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'No comment found',
            ], 400);
        }
    }

    public function delete(Request $request, $comment_id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        $comment = $this->commentRepository->getById($comment_id);
        if ($comment) {
            if ($comment->user_id == $user->id || $user->role == 'admin') {
                $this->commentRepository->delete($comment);
                return response()->json([
                    'message' => 'Comment succesfully deleted',

                ], 200);
            } else {
                return response()->json([
                    'message' => 'Unauthorized access'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'No comment found'
            ], 400);
        }
    }

    public function restoreComment(Request $request, $id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        if ($user->role == 'admin') {
            $comment = $this->commentRepository->restoreDeletedComment($id);
            if ($comment) {
                return response()->json([
                    'message' => 'Comment are successfully restored'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Comment not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function deletedComments(Request $request, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        if ($user->role == 'admin') {
            $trashedComments = $this->commentRepository->getDeletedComments();
            if ($trashedComments) {
                return response()->json([
                    'message' => 'trashed Comments are successfully fetched',
                    'data' => $trashedComments
                ], 200);
            } else {
                return response()->json([
                    'message' => 'trashed Comments not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }
}
