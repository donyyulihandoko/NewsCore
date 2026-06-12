<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentService
{
    public function getComments(): LengthAwarePaginator;

    public function removeComment(Comment $comment):int;

    public function addComment(Post $post, array $data):Comment;

    public function totalComments(): int;

    public function deleteCommentByUser(Comment $comment): int;

    public function deleteCommentByAuthor(Comment $comment): int;

}
