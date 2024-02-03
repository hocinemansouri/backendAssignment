<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create(Request $request)
    {
        if ($request->user()->role == 'moderator' || $request->user()->role == 'admin') {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:64',
                'description' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' => 'required|image|mimes:jpg,jpeg,png'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation errors',
                    'errors' => $validator->messages()
                ], 422);
            }

            $image_name = time() . '.' . $request->image->extension();
            $request->image->move(public_path('/upload/blog_images'), $image_name);

            $post = Post::create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'content' => $request->content,
                    'image' => $image_name,
                    'user_id' => $request->user()->id,
                    'category_id' => $request->category_id,
                ]
            );
            $post->load('user:id,name,email', 'category:id,name');
            return response()->json([
                'message' => 'Post succesfully created',
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function list(Request $request)
    {
        $post_query = Post::withCount(['comments', 'likes'])->with(['user:id,name,surname,profile_photo', 'category:id,name']);

        // Filter by
        // Title keyword
        if ($request->keyword) {
            $post_query->where('title', 'like', '%' . $request->keywork . '%');
        }
        // Category
        if ($request->category) {
            $post_query->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }
        // User_id
        if ($request->user_id) {
            $post_query->where('user_id', $request->user_id);
        }
        // Sorting
        // By
        if ($request->sortBy && in_array($request->sortBy, ['id', 'created_at', 'comments_count', 'likes_count'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'comments_count';
        }
        // Order
        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }
        // Pagination
        // Nb of items per page
        if ($request->perPage) {
            $perPage = $request->perPage;
        } else {
            $perPage = 10;
        }
        // Paginate 1, no pagination 0
        if ($request->paginate) {
            $posts = $post_query->orderBY($sortBy, $sortOrder)->paginate($perPage);
        } else {
            $posts = $post_query->orderBY($sortBy, $sortOrder)->get();
        }
        if($request->is('api/*')){
            return response()->json([
                'message' => 'Posts are successfully fetched',
                'data' => $posts,
            ], 200);}
        else{
            return view('/posts', ['data'=>$posts]);
        }
    }

    public function details(Request $request, $id)
    {
        $post = Post::withCount('comments')->with(['user', 'category', 'comments','likes'])->where('id', $id)->first();
        if ($post) {
            $user = auth('sanctum')->user();
            if ($user) {
                $post_like = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();
                if ($post_like) {
                    $post->liked_by_current_user = true;
                } else {
                    $post->liked_by_current_user = false;
                }
            } else {
                $post->liked_by_current_user = false;
            }

            if($request->is('api/*')){
                return response()->json([
                    'message' => 'Posts are successfully fetched',
                    'data' => $post
                ], 200);
            }else{
                return view('/post', ['data'=>$post]);
            }
        } else {
            return response()->json([
                'message' => 'Posts not found'
            ], 400);
        }
    }

    public function listDeletedPosts(Request $request)
    {
        if ($request->user()->role == 'admin') {
            $trashedPosts = Post::onlyTrashed()->get();
            return $trashedPosts;
            if ($trashedPosts) {
                return response()->json([
                    'message' => 'trashed Posts are successfully fetched',
                    'data' => $trashedPosts
                ], 200);
            } else {
                return response()->json([
                    'message' => 'trashed Posts not found'
                ], 400);
            }
            return response()->json([
                'message' => 'trashed Posts not found'
            ], 400);
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->role == 'moderator' || $request->user()->role == 'admin') {
            $post = Post::with(['user', 'category'])->where('id', $id)->first();

            if ($post) {
                if ($post->user_id == $request->user()->id || $request->user()->role == 'admin') {
                    $validator = Validator::make($request->all(), [
                        'title' => 'required|max:250',
                        'description' => 'required',
                        'content' => 'required',
                        'category_id' => 'required',
                        'image' => 'nullable|image|mimes:jpg,jpeg,png'
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Validation errors',
                            'errors' => $validator->messages()
                        ], 422);
                    }
                    if ($request->hasFile('image')) {
                        $image_name = time() . '.' . $request->image->extension();
                        $request->image->move(public_path('/upload/blog_images'), $image_name);
                        $old_path = public_path() . 'upload/blog_images/' . $post->image;
                        if (Storage::exists($old_path)) {
                            Storage::delete($old_path);
                        }
                    } else {
                        $image_name = $post->image;
                    }

                    $post->update(
                        [
                            'title' => $request->title,
                            'description' => $request->description,
                            'content' => $request->content,
                            'image' => $image_name,
                            'category_id' => $request->category_id,
                        ]
                    );
                    return response()->json([
                        'message' => 'Post succesfully updated',
                        'data' => $post
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Unauthorized access'
                    ], 403);
                }
                return response()->json([
                    'message' => 'Posts are successfully fetched',
                    'data' => $post
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Posts not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->role == 'moderator' || $request->user()->role == 'admin') {

            $post = Post::where('id', $id)->first();

            if ($post) {
                if ($post->user_id == $request->user()->id || $request->user()->role == 'admin') {
                    $old_path = public_path() . 'upload/blog_images/' . $post->image;
                    if (Storage::exists($old_path)) {
                        Storage::delete($old_path);
                    }
                    $post->delete();
                    return response()->json([
                        'message' => 'Post are successfully deleted'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Unauthorized access'
                    ], 403);
                }
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function restorePost(Request $request, $id)
    {
        if ($request->user()->role == 'admin') {
            $post = Post::withTrashed()->find($id);
            if ($post) {
                $post->restore();
                return response()->json([
                    'message' => 'Post are successfully restored'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function toggle_like(Request $request, $post_id)
    {
        $post = Post::where('id', $post_id)->first();
        if ($post) {
            $user = $request->user();
            $post_like = PostLike::where('post_id', $post->id)->where('user_id', $user->id)->first();

            if ($post_like) {
                $post_like->delete();
                return response()->json([
                    'message' => 'Like succesfully removed'
                ], 200);
            } else {
                PostLike::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id
                ]);
                return response()->json([
                    'message' => 'Post succesfully liked'
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Posts not found'
            ], 400);
        }
    }
}
