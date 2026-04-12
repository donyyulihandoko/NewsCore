<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return response()->view('admin.category.index', [
            'categories' => $this->categoryService->getCategories()
        ]);
    }

    public function create(): Response
    {
        return response()->view('admin.category.create');
    }

    public function store(PostCategoryRequest $request): RedirectResponse
    {
        try {
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
        return response()->view('admin.category.edit', [
            'category' => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        try {
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
            Log::info('Category deleted successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $category->id
            ]);
            $this->categoryService->removeCategory($category);
            return redirect()->back()->with('success', 'Category deleted successfully!');
        } catch (Exception $e) {
            Log::error('Category update failed! : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Category delete failed!');
        }
    }
}
