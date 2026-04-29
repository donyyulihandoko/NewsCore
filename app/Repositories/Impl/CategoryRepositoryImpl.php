<?php

namespace App\Repositories\Impl;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepositoryImpl implements CategoryRepository
{
    // role admin
    public function getCategoriesPagination(int $perPage = 9): LengthAwarePaginator
    {
        return Category::withCount('posts')
            ->latest()
            ->paginate($perPage);
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

    public function findBySlug(Category $category): Category
    {
        return $category->load(['posts']);
    }

    public function totalCategories(): ?int
    {
        return Category::query()
            ->count();
    }

    // role admin & author
    public function getCategories(): Collection
    {
        return Category::orderBy('name', 'asc')->get();
    }

    // role user
    public function getCategoriesWithTotalPublishedPosts(int $perPage): LengthAwarePaginator
    {
        return Category::query()
            ->withCount(['posts' => function ($query) {
                $query->where('is_published', true);
            }])->latest()
            ->paginate($perPage);
    }
}
