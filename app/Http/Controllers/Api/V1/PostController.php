<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\LikeRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\AuthenticationService;
use Exception;

use function PHPUnit\Framework\isEmpty;

class PostController extends Controller
{
    protected $postRepository;
    protected $categoryRepository;
    
    public function __construct(PostRepositoryInterface  $postRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(CreatePostRequest $request, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);

        if ($user->role == 'moderator' || $user->role == 'admin') {
            $image_name = time() . '.' . $request->image->extension(); // naming images
            $request->image->move(public_path('/upload/blog_images'), $image_name); // move uploaded image to blog_images folder

            $post = $this->postRepository->create([ // create post
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'image' => $image_name,
                'user_id' => $user->id,
                'category_id' => $request->category_id,
            ])->load('user:id,name,email', 'category:id,name');


            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Post succesfully created',
                    'data' => $post
                ], 200);
            } else {
                return back();
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function list(Request $request)
    {
        // Handle filters
        $filters = [];

        isEmpty($request->pagination) ? $filters['pagination'] = 10 : $filters['pagination'] = $request->pagination;
        $request->keyword ?? $filters['keyword'] = $request->keyword;
        $request->category ?? $filters['category'] = $request->category;
        $request->user_id ?? $filters['user_id'] = $request->user_id;

        $sortBy = 'comments_count';
        if ($request->sortBy && in_array($request->sortBy, ['id', 'created_at', 'comments_count', 'postlikes_count'])) {
            $sortBy = $request->sortBy;
        }

        $request->sortBy ?? $filters['sortBy'] = $sortBy;

        $sortOrder = 'desc';
        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        }

        $request->sortOrder ?? $filters['sortOrder'] = $sortOrder;

        // get All posts
        $index_query = $this->postRepository->getAll($filters);

        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Posts are successfully fetched',
                'data' => $index_query,
            ], 200);
        } else {
            $categories = $this->categoryRepository->getAll();
            return view('/posts', ['data' => $index_query,'categories'=>$categories]);
        }
    }

    public function details(Request $request, $id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);

        $post = $this->postRepository->getById(
            $id,
            $withCount = ['comments', 'postlikes'],
            $with = ['user', 'category', 'comments', 'postlikes'],
            $userId = $user ? $user->id : null
        );
        if ($post) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Posts are successfully fetched',
                    'data' => $post,
                ], 200);
            } else {
                $categories = $this->categoryRepository->getAll();
                return view('/post', ['data' => $post,'categories'=>$categories, 'comments' => $post->comments()->paginate(5)]);
            }
        } else {
            return view('/404');
        }
    }

    public function listDeletedPosts(Request $request, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);

        if ($user->role == 'admin') {
            $trashedPosts = $this->postRepository->getDeletedPosts();
            if ($trashedPosts) {
                
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'trashed Posts are successfully fetched',
                        'data' => $trashedPosts,
                    ], 200);
                } else {
                    $categories = $this->categoryRepository->getAll();
                    return view('/deletedposts', ['data' => $trashedPosts,'categories'=>$categories]);
                }

            } else {
                return response()->json([
                    'message' => 'trashed Posts not found'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
        }
    }

    public function update(UpdatePostRequest $request, $id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
            $post = $this->postRepository->getById($id);
            if ($post) {
                
                if ($post->user_id == $user->id || $user->role == 'admin') {
                    $image_name = $post->image;
                    if ($request->hasFile('image')) {
                        $image_name = time() . '.' . $request->image->extension();
                        $request->image->move(public_path('/upload/blog_images'), $image_name);
                        $old_path = public_path() . 'upload/blog_images/' . $post->image;
                        if (Storage::exists($old_path)) {
                            Storage::delete($old_path);
                        }
                    }
                    
                    $this->postRepository->update($post->id, [
                        'title' => $request->title,
                        'description' => $request->description,
                        'content' => $request->content,
                        'image' => $image_name,
                        'category_id' => $request->category_id,
                    ]);
                    return response()->json([
                        'message' => 'Post succesfully updated',
                        'data' => $post
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
    }

    public function delete(Request $request, $id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        
        if ($user->role == 'admin') {
            try {
                $post = $this->postRepository->getById($id);
                if ($post->user_id == $user->id || $user->role == 'admin') {
                    $old_path = public_path() . 'upload/blog_images/' . $post->image;
                    if (Storage::exists($old_path)) {
                        Storage::delete($old_path);
                    }
                    $this->postRepository->delete($post);
                    //return true;
                    return response()->json([
                        'message' => 'Post are successfully deleted'
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Unauthorized access'
                    ], 403);
                }
            } catch (Exception $e) {
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

    public function restorePost(Request $request, $id, AuthenticationService $auth)
    {
        $user = $auth->getUserIfAuthenticated($request);
        if ($user->role == 'admin') {
            $restoredPost = $this->postRepository->restoreDeletePost($id);
            if ($restoredPost) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => 'Post are successfully restored'
                    ], 200);
                } else {
                    return back();
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

    public function toggle_like(Request $request, $post_id, AuthenticationService $auth, LikeRepositoryInterface $likeRepository)
    {
        $user = $auth->getUserIfAuthenticated($request);
        $likeRepository->toggleLike($post_id,$user->id, $this->postRepository,$auth, $request);
        return back();
    }
}
