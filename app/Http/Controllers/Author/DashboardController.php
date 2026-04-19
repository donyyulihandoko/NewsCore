<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $author = Auth::user();
        $totalPosts = Post::where('user_id', $author->id)->count();
        $pendingPosts = Post::where('user_id', $author->id)->where('is_published', false)->count();

        // Ambil 5 artikel terbaru
        $recentPosts = Post::where('user_id', $author->id)
            ->latest()
            ->take(5)
            ->get();

        return view('author.dashboard', compact('totalPosts', 'pendingPosts', 'recentPosts'));
    }
}
