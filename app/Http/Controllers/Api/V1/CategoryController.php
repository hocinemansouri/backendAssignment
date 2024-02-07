<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $authService;

    public function __construct(CategoryRepositoryInterface  $categoryRepository,AuthenticationService $authService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->authService = $authService;
    }

    public function create(CreateCategoryRequest $request)
    {
        $category = $this->categoryRepository->create([
            'name' => $request->name,
            'is_archived' => $request->is_archived
        ]);

        return response()->json([
            'message' => 'Category succesfully created',
            'data' => $category
        ], 200);
    }

    public function index()
    {
        return $this->categoryRepository->getAll();
    }

    public function getById($categoryId)
    {
        return $this->categoryRepository->getById($categoryId);
    }

    public function update(CreateCategoryRequest $request)
    {
        $user = $this->authService->getUserIfAuthenticated($request);
        if($user->role == 'admin')
            $category = $this->getById($request->category_id);
            if($category)$this->categoryRepository->update($category,$request->name);
            else return response()->json(['message'=>'Category not found!']);
    }

    public function delete(Request $request){
        $user = $this->authService->getUserIfAuthenticated($request);
        //dd($request->category_id);
        if($user->role == 'admin')
            $category = $this->categoryRepository->getById($request->category_id);
            if ($category)$this->categoryRepository->delete($category);
            else return response()->json(['message'=>'Category not found!']);
    }
}
