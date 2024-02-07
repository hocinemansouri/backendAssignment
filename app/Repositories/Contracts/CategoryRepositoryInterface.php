<?php

namespace App\Repositories\Contracts;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAll();

    public function getById($categoryId): ?Category;

    public function create(array $data): Category;

    public function update(Category $category, $data): Category;

    public function delete(Category $categoryId): void;
}