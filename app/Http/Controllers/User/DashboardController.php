<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{

    public function __construct(private PostService $postService)
    {
        // 
    }

    public function index(): Response
    {
        return response()->view('user.dashboard', [
            'posts' => $this->postService->getUserRecentArticle()
        ]);
    }
}
