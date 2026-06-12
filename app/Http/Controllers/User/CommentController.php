<?php

namespace App\Http\Controllers\User;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
        // Constructor with dependency injection for CommentService
    }

    public function store(Post $post, StoreCommentRequest $request): RedirectResponse
    {
        $this->authorize('create', Comment::class);
        Log::info('Storing comment with data: ' . json_encode($request->validated()));
        $this->commentService->addComment($post, $request->validated());
        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('forceDelete', $comment);
        Log::info('Deleting comment with ID: ' . $comment->id);
        $this->commentService->deleteCommentByUser($comment);
        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
