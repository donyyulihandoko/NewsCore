<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\CategoryService;
use App\Services\PostService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Nette\Utils\Image;

class PostController extends Controller
{
    public function __construct(private PostService $postService, private CategoryService $categoryService)
    {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        $author_id = Auth::user()->id;
        return view('author.post.index', [
            'posts' => $this->postService->getPostByAuthorId($author_id, 9)
        ]);
    }

    public function create(): Response
    {
        return response()->view('author.post.create', [
            'categories' => $this->categoryService->getCategories()
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        try {
            Log::info('Post created successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->except(['body', 'image'])
            ]);
            $this->postService->createPost($request->validated());
            return to_route('author.posts.index')->with('success', 'Post create successfully!');
        } catch (Exception $e) {
            Log::error('Post create failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Post create failed!');
        }
    }

    public function edit(Post $post): Response
    {
        return response()->view('author.post.edit', [
            'categories' => $this->categoryService->getCategories(),
            'post' => $post->load(['category', 'author'])
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        try {
            Log::info('Post update successfully!', [
                'user_id' => Auth::user()->id,
                'item_updated' => $post->id,
                'payload' => $request->except(['body', 'image'])
            ]);
            $this->postService->updatePost($post, $request->validated());
            return to_route('author.posts.index')->with('success', 'Post updated successfully!');
        } catch (Exception $e) {
            Log::error('Post update failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Post update failed!');
        }
    }

    // public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    // {
    //     try {
    //         Log::info('Post update successfully!', [
    //             'user_id' => Auth::user()->id,
    //             'payload' => $request->except('body')
    //         ]);
    //         $this->postService->updatePost($post, $request->validated());
    //         return to_route('admin.posts.index')->with('success', 'Post updated successfully!');
    //     } catch (Exception $e) {
    //         Log::error('Post update failed! : ' . $e->getMessage());
    //         return redirect()->back()->withInput()->with('error', 'Post update failed!');
    //     }
    // }

    public function destroy(Post $post): RedirectResponse
    {
        try {
            Log::info('Post deleted', [
                'user_id' => Auth::user()->id,
                'item_deleted' => $post->id
            ]);
            $this->postService->removePost($post);

            return redirect()->back()->with('success', 'Post delete successfully!');
        } catch (Exception $e) {
            Log::error('Post delete failed! : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Post delete failed!');
        }
    }
}
