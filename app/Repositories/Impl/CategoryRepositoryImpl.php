<?php

namespace App\Repositories\Impl;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepositoryImpl implements CategoryRepository
{
    public function getCategoriesPagination(): LengthAwarePaginator
    {
        return Category::withCount('posts')->latest()->paginate(9);
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

    public function getCategories(): Collection
    {
        return Category::orderBy('name', 'asc')->get();
    }
}
