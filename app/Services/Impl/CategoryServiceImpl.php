<?php

namespace App\Services\Impl;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryServiceImpl implements CategoryService
{

    public function __construct(private CategoryRepository $categoryRepository)
    {
        //  
    }
    // handle image
    private function handleImage(?UploadedFile $file, ?string $oldPath = null)
    {
        // no upload file
        if (!$file) return $oldPath;

        // jika ada file lama (update file)
        if ($oldPath) Storage::disk('public')->delete($oldPath);

        // simpan file baru
        return $file->store('category-images', 'public');
    }

    // role admin
    public function getCategoriesPagination(int $perPage = 9): LengthAwarePaginator
    {
        return $this->categoryRepository->getCategoriesPagination($perPage);
    }

    public function createCategory(array $data): Category
    {
        return DB::transaction(function ()  use ($data) {
            $data['image'] = $this->handleImage($data['image'] ?? null);
            $result = $this->categoryRepository->createCategory($data);

            return $result;
        });
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return DB::transaction(function () use ($category, $data) {
            $data['image'] = $this->handleImage($data['image'] ?? null, $category->image);
            $result = $this->categoryRepository->updateCategory($category, $data);

            return $result;
        });
    }

    public function removeCategory(Category $category): bool
    {
        // if ($category->posts()->exists()) {
        //     return false; // Gagal menghapus karena masih ada relasi
        // }

        return DB::transaction(function () use ($category) {
            $pathImage = $category->image;
            $delete = $this->categoryRepository->removeCategory($category);

            if ($pathImage && $delete) Storage::disk('public')->delete($pathImage);

            return $delete;
        });
    }

    public function findBySlug(Category $category): Category
    {
        return $this->categoryRepository->findBySlug($category);
    }

    public function totalCategories(): ?int
    {
        return $this->categoryRepository->totalCategories();
    }

    // role admin & author
    public function getCategories(): Collection
    {
        return $this->categoryRepository->getCategories();
    }

    // role user
    public function getCategoriesWithTotalPublishedPosts(int $perPage): LengthAwarePaginator
    {
        return $this->categoryRepository->getCategoriesWithTotalPublishedPosts($perPage);
    }
}
