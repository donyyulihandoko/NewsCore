<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryService
{
    // role admin
    public function getCategoriesPagination(int $perPage = 9): LengthAwarePaginator;

    public function createCategory(array $data): Category;

    public function updateCategory(Category $category, array $data): bool;

    public function removeCategory(Category $category): bool;

    public function findBySlug(Category $category): Category;

    public function totalCategories(): ?int;

    // role admin & author
    public function getCategories(): Collection;

    // role user
    public function getCategoriesWithTotalPublishedPosts(int $perPage): LengthAwarePaginator;
}
