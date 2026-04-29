<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\CategoryService;
use App\Services\PostService;
use Exception;
use GuzzleHttp\Promise\Create;
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
        $this->authorize('viewAny', Post::class);

        $author_id = Auth::user()->id;
        return view('author.post.index', [
            'posts' => $this->postService->getPostsByAuthorId($author_id, 9)
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
            $this->authorize('create', Post::class);

            Log::info('Post created successfully!', [
                'user_id' => Auth::user()->id,
                'payload' => $request->except(['body', 'image'])
            ]);

            $this->postService->createPost($request->validated());
            return to_route('author.posts.index')->with('success', 'Post created successfully!');
        } catch (Exception $e) {
            Log::error('Post create failed! : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Post create failed!');
        }
    }

    public function show(Post $post): Response
    {
        $this->authorize('view', $post);

        return response()->view('author.post.show', [
            'post' => $this->postService->findBySlug($post)
        ]);
    }

    public function edit(Post $post): Response
    {
        $this->authorize('view', $post);

        return response()->view('author.post.edit', [
            'categories' => $this->categoryService->getCategories(),
            'post' => $this->postService->findBySlug($post)
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        try {
            $this->authorize('update', $post);

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

    public function destroy(Post $post): RedirectResponse
    {
        try {
            $this->authorize('forceDelete', $post);

            Log::info('Post deleted', [
                'user_id' => Auth::user()->id,
                'item_deleted' => $post->id
            ]);

            $this->postService->removePost($post);
            return redirect()->back()->with('success', 'Post deleted successfully!');
        } catch (Exception $e) {
            Log::error('Post delete failed! : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Post delete failed!');
        }
    }
}
