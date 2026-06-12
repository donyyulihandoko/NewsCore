<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Models\Post;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
        // Constructor with dependency injection for CommentService
    }

    public function store(Post $post, StoreCommentRequest $request)
    {
        $this->authorize('create', Comment::class);
        Log::info('Storing comment with data: ' . json_encode($request->validated()));
        $this->commentService->addComment($post, $request->validated());
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('forceDelete', $comment);
        Log::info('Deleting comment with ID: ' . $comment->id);
        $this->commentService->deleteCommentByAuthor($comment);
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
