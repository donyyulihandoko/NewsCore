<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): Response
    {
        return response()->view('user.post.index', [
            'posts' => $this->postService->getPostsPagination(9)
        ]);
    }


    public function show(Post $post): Response
    {
        return response()->view('user.post.show', [
            'post' => $post->load(['author', 'category'])
        ]);
    }
}
