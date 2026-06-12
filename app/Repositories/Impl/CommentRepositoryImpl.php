<?php

namespace App\Repositories\Impl;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Override;

class CommentRepositoryImpl implements CommentRepository
{
    #[Override]
    public function getComments(): LengthAwarePaginator
    {
        return Comment::with('user', 'post')
            ->latest()
            ->paginate(10);
    }

    #[Override]
    public function removeComment(Comment $comment): int
    {
        return $comment->delete();
    }

    #[Override]
    public function addComment(Post $post, array $data): Comment
    {
        $data['user_id'] = Auth::id();
        return $post->comments()->create($data);
    }

    public function totalComments(): int
    {
        return Comment::query()->count();
    }

    public function deleteCommentByUser(Comment $comment): int
    {
        if ($comment->user_id === Auth::id()) {
            return $comment->delete();
        }
        return 0; // Return 0 if the user is not authorized to delete the comment
    }
    

    public function deleteCommentByAuthor(Comment $comment): int
    {
        $post = $comment->post;
        if ($post && $post->user_id === Auth::id()) {
            return $comment->delete();
        }
        return 0; // Return 0 if the author is not authorized to delete the comment
    }

}
