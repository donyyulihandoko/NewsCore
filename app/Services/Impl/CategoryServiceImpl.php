<?php

namespace App\Services\Impl;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CategoryServiceImpl implements CategoryService
{

    public function __construct(private CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories(): LengthAwarePaginator
    {
        return $this->categoryRepository->getCategories();
    }

    public function createCategory(array $data): Category
    {
        return DB::transaction(function ()  use ($data) {
            return $this->categoryRepository->createCategory($data);
        });
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return DB::transaction(function () use ($category, $data) {
            return $this->categoryRepository->updateCategory($category, $data);
        });
    }

    public function removeCategory(Category $category): bool
    {
        return DB::transaction(function () use ($category) {
            return $this->categoryRepository->removeCategory($category);
        });
    }
}
