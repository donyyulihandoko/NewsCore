<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalPendingPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\CategoryService;
use App\Services\PostService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function __construct(private PostService $postService, private CategoryService $categoryService)
    {
        // Constructor injection for services
    }

    public function index(): Response
    {
        $this->authorize('view', Post::class);
        return response()->view('admin.post.index', [
            'posts' => $this->postService->getPostsPagination(10),
            'title' => 'Haalaman Admin Post'
        ]);
    }


    public function create(): Response
    {
        return response()->view('admin.post.create', [
            'categories' => $this->categoryService->getCategories(),
            'title' => 'Halaman Admin Create Post'
        ]);
    }


    public function store(StorePostRequest $request): RedirectResponse
    {
        $this->authorize('create', Post::class);
        Log::info('Storing post with data: ' . json_encode($request->validated()));
        $this->postService->createPost($request->validated());
        return to_route('admin.posts.index')->with('success', 'Post created successfully!');
    }

    public function show(Post $post): Response
    {
        $this->authorize('view', $post);
        return response()->view('admin.post.show', [
            'post' => $this->postService->findBySlug($post)
        ]);
    }


    public function edit(Post $post): Response
    {
        $this->authorize('view', $post);
        return response()->view('admin.post.edit', [
            'post' => $this->postService->findBySlug($post),
            'categories' => $this->categoryService->getCategories(),
        ]);
    }


    public function update(ApprovalPendingPostRequest $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);
        Log::info('Updating post with data: ' . json_encode($request->validated()));
        $this->postService->approvalPendingPost($post, $request->validated());
        return redirect()->back()->with('success', 'Post approved successfully!');
    }


    public function destroy(Post $post)
    {
        $this->authorize('forceDelete', $post);
        Log::info('Deleting post with data: ' . json_encode($post->toArray()));
        $this->postService->removePost($post);
        return to_route('admin.posts.index')->with('success', 'Post deleted successfully!');
    }
}
