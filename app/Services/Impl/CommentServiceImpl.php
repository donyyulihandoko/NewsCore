<?php

namespace App\Services\Impl;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;
use App\Services\CommentService;
use Illuminate\Pagination\LengthAwarePaginator;
use Override;

class CommentServiceImpl implements CommentService
{
    public function __construct(private CommentRepository $commentRepository)
    {
        // inject repository
    }

    #[Override]
    public function getComments(): LengthAwarePaginator
    {
        return $this->commentRepository->getComments();
    }

    #[Override]
    public function removeComment(Comment $comment): int
    {
        return $this->commentRepository->removeComment($comment);
    }

    #[Override]
    public function addComment(Post $post, array $data): Comment
    {
        return $this->commentRepository->addComment($post, $data);
    }

    public function totalComments(): int
    {
        return $this->commentRepository->totalComments();
    }

    public function deleteCommentByUser(Comment $comment): int
    {
        return $this->commentRepository->deleteCommentByUser($comment);
    }

    public function deleteCommentByAuthor(Comment $comment): int
    {
        return $this->commentRepository->deleteCommentByAuthor($comment);
    }

}
