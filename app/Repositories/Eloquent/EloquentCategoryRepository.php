<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function getAll()
    {
        return Category::all();
    }
    
    public function getById($categoryId): ?Category
    {
        $category = Category::where('id',$categoryId)->first();
        return $category;
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, $categoryName): Category
    {
        $category->update(['name',$categoryName]);
        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
