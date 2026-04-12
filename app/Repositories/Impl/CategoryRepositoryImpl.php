<?php

namespace App\Repositories\Impl;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepositoryImpl implements CategoryRepository
{
    public function getCategories(): LengthAwarePaginator
    {
        return Category::latest()->paginate(10);
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function removeCategory(Category $category): bool
    {
        return $category->delete();
    }
}
