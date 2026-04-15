<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
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
        $this->postService = $postService;
        $this->categoryService = $categoryService;
    }

    public function index(): Response
    {
        return response()->view('admin.post.index', [
            'posts' => $this->postService->getPostsPagination(),
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
        try {
            Log::info('Post created successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->except('body')
            ]);
            $this->postService->createPost($request->validated());
            return to_route('admin.posts.index')->with('success', 'Post created successfully!');
        } catch (Exception $e) {
            Log::error('Post create failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Post create failed!');
        }
    }

    public function show(Post $post): Response
    {
        return response()->view('admin.post.show', [
            'post' => $post->load(['author', 'category'])
        ]);
    }


    public function edit(Post $post): Response
    {
        return response()->view('admin.post.edit', [
            'post' => $post->load('category'),
            'categories' => $this->categoryService->getCategories(),
        ]);
    }


    public function update(StorePostRequest $request, Post $post): RedirectResponse
    {
        try {
            Log::info('Post update successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->except('body')
            ]);
            $this->postService->updatePost($post, $request->validated());
            return to_route('admin.posts.index')->with('success', 'Post updated successfully!');
        } catch (Exception $e) {
            Log::error('Post update failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Post update failed!');
        }
    }


    public function destroy(Post $post)
    {
        try {
            Log::info('Post deleted successfully!', [
                'user_id' => Auth::user()->id,
                'item_deleted' => $post->id
            ]);
            $this->postService->removePost($post);
            return to_route('admin.posts.index')->with('success', 'Post deleted successfully!');
        } catch (Exception $e) {
            Log::error('Post delete failed! : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Post delete failed!');
        }
    }
}
