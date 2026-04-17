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
        return response()->view('user.category.index', [
            'categories' => $this->categoryService->getCategoriesPagination()
        ]);
    }

    public function show(Category $category): Response
    {
        // dd($category->toArray());
        return response()->view('user.category.show', [
            'category' => $category,
            'posts' => $this->postService->getPostsByCategoryPagination($category->id, 9)
        ]);
    }
}
