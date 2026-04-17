<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostService
{
    public function getPostsPagination(int $page): LengthAwarePaginator;

    public function createPost(array $data): Post;

    public function updatePost(Post $post, array $data): bool;

    public function removePost(Post $post): bool;

    public function getPostsByCategoryPagination(int $categoryId, int $page = 9): LengthAwarePaginator;
}
