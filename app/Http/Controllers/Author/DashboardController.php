<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private PostService $postService)
    {
        // Constructor with dependency injection for PostService
    }

    public function index(): Response
    {
        $author = Auth::user();
        return response()->view('author.dashboard', [
            'totalPost' =>  $this->postService->totalPostsByAuthor($author->id),
            'recentPosts' => $this->postService->getRecentPostsByAuthor($author->id),
            'pendingPosts' => $this->postService->totalPendingPostsByAuthor($author->id),
            'publishedPost' => $this->postService->totalPublishedPostsByAuthor($author->id)
        ]);
    }

    public function pendingPost(): Response
    {
        $this->authorize('viewAny', Post::class);
        $author = Auth::user();
        return response()->view('author.post.index', [
            'posts' => $this->postService->getPendingPostsByAuthor($author->id, 9)
        ]);
    }

    public function publishedPost(): Response
    {
        $this->authorize('viewAny', Post::class);
        $author = Auth::user();
        return response()->view('author.post.index', [
            'posts' => $this->postService->getPublishedPostsByAuthor($author->id, 9)
        ]);
    }
}
