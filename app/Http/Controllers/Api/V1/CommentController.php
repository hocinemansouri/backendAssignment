<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function create(Request $request, $post_id)
    {
        $post = Post::where('id', $post_id)->first();

        if ($post) {
            $validator = Validator::make($request->all(), [
                'content' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $post->id,
                'user_id' => $request->user()->id
            ]);
            $comment->load('user');
            return response()->json([
                'message' => 'Comment added succesfully',
                'content' => $comment
            ], 200);
        } else {
            return response()->json([
                'message' => 'Post not found'
            ], 422);
        }
    }

    public function list(Request $request, $post_id)
    {
        $post = Post::where('id', $post_id)->first();

        if ($post) {
            $perPage = ($request->perPage) ? $request->perPage : 5;
            $comments = Comment::with(['user', 'post'])->where('post_id', $post_id)->orderBy('id', 'desc')->paginate($perPage);
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
    public function update(Request $request, $comment_id)
    {
        $comment = Comment::with(['user'])->where('id', $comment_id)->first();
        if ($comment) {
            if ($comment->user_id == $request->user()->id || $request->user()->role == 'admin') {
                $validator = Validator::make($request->all(), [
                    'content' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validation errors',
                        'errors' => $validator->errors()
                    ], 422);
                }
                $comment->update([
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

    public function delete(Request $request, $comment_id)
    {
        $comment = Comment::where('id', $comment_id)->first();
        if ($comment) {
            if ($comment->user_id == $request->user()->id || $request->user()->role == 'admin') {
                $comment->delete();
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

    public function restoreComment(Request $request, $id)
    {
        if ($request->user()->role == 'admin') {
            $comment = Comment::withTrashed()->find($id);
            if ($comment) {
                $comment->restore();
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

    public function deletedComments(Request $request)
    {
        if ($request->user()->role == 'admin') {
            $trashedComments = Comment::onlyTrashed()->get();
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
