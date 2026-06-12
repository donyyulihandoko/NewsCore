<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    
    public function __construct(private CategoryService $categoryService)
    {
        // Constructor injection for CategoryService
    }

    public function index(): Response
    {
        $this->authorize('viewAny', Category::class);
        return response()->view('admin.category.index', [
            'categories' => $this->categoryService->getCategoriesPagination(10)
        ]);
    }

    public function create(): Response
    {
        return response()->view('admin.category.create');
    }
    
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);
        Log::info('Storing category with data: ' . json_encode($request->validated()));
        $this->categoryService->createCategory($request->validated());
        return to_route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category): Response
    {
        $this->authorize('view', $category);
        return response()->view('admin.category.edit', [
            'category' => $this->categoryService->findBySlug($category)
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);
        Log::info('Updating category with data: ' . json_encode($request->validated()));
        $this->categoryService->updateCategory($category, $request->validated());
        return to_route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->authorize('forceDelete', $category);
            Log::info('Deleting category with data: ' . json_encode($category->toArray()));
            $this->categoryService->removeCategory($category);
            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (Exception $e) {
            Log::error('Failed to delete category: ' . $e->getMessage());
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), '23000')) {
                return redirect()->back()->with('error', 'The category cannot be deleted because it still contains associated post.');
            }
            return redirect()->back()->with('error', 'Category delete failed!');
        }

    }
}
