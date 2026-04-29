<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService, private PostService $postService)
    {
        $this->categoryService = $categoryService;
        $this->postService = $postService;
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Category::class);

        return response()->view('user.category.index', [
            'categories' => $this->categoryService->getCategoriesWithTotalPublishedPosts(9)
        ]);
    }

    public function show(Category $category): Response
    {
        $this->authorize('view', $category);

        return response()->view('user.category.show', [
            'category' => $this->categoryService->findBySlug($category),
            'posts' => $this->postService->getPublishedPostsByCategory($category->id, 6)
        ]);
    }
}
