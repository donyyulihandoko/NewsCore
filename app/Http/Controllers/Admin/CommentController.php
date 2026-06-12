<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
        // Constructor injection for CommentService
    }    

    public function index():Response
    {
        $this->authorize('viewAny', Comment::class );
        return response()->view('admin.comment.index', [
            'comments' => $this->commentService->getComments()
        ]);
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('forceDelete', $comment);
        Log::info('Deleting comment with data: ' . json_encode($comment->toArray()));
        $this->commentService->removeComment($comment);
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}