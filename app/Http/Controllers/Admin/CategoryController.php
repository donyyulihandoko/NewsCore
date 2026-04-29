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
        $this->categoryService = $categoryService;
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
        try {
            $this->authorize('create', Category::class);

            Log::info('Category created successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->all()
            ]);

            $this->categoryService->createCategory($request->validated());
            return to_route('admin.categories.index')->with('success', 'Category created successfully!');
        } catch (Exception $e) {
            Log::error('Category create failed! : ' .  $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Category create failed!');
        }
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
        try {
            $this->authorize('update', $category);

            Log::info('Category updated successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->all()
            ]);

            $this->categoryService->updateCategory($category, $request->validated());
            return to_route('admin.categories.index')->with('success', 'Category updated successfully!');
        } catch (Exception $e) {
            Log::error('Category update failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Category update failed!');
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->authorize('forceDelete', $category);

            Log::info('Category deleted successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $category->id
            ]);

            $this->categoryService->removeCategory($category);
            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (Exception $e) {
            Log::error('Category update failed! : ' . $e->getMessage());

            if ($e->getCode() === '23000' || str_contains($e->getMessage(), '23000')) {
                return redirect()->back()->with('error', 'The category cannot be deleted because it still contains associated post.');
            }

            return redirect()->back()->with('error', 'Category delete failed!');
        }
    }
}
