<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostService
{
    public function getPostsPagination(): LengthAwarePaginator;

    public function createPost(array $data): Post;

    public function updatePost(Post $post, array $data): bool;

    public function removePost(Post $post): bool;
}
