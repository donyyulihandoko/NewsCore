<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\CategoryService;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function __construct(private CategoryService $categoryService, private PostService $postService)
    {
        // 
    }

    public function index(): Response
    {
        return response()->view('admin.dashboard', [
            'title' => 'Halaman Admin Dashboard',
            'totalCategories' => $this->categoryService->totalCategories(),
            'recentActivity' => $this->postService->recentActivity(),
            'totalPosts' => $this->postService->totalAllPosts(),
            'totalPublishedPosts' => $this->postService->totalPublishedPosts(),
            'totalPendingPosts' => $this->postService->totalPendingPosts()
        ]);
    }

    public function publishedPost(): Response
    {
        $this->authorize('viewAny', Post::class);

        return response()->view('admin.post.index', [
            'posts' => $this->postService->getPublishedPosts(10),
        ]);
    }

    public function pendingPost(): Response
    {
        $this->authorize('viewAny', Post::class);

        return response()->view('admin.post.index', [
            'posts' => $this->postService->getPendingPosts(10),
            'title' => 'Haalaman Admin Post'
        ]);
    }
}
