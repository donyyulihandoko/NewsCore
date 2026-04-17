<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepository
{
    public function getPostsPagination(int $page): LengthAwarePaginator;

    public function createPost(array $data): Post;

    public function updatePost(Post $post, array $data): bool;

    public function removePost(Post $post): bool;

    public function getPostsByCategoryPagination(int $categoryId, int $page = 9): LengthAwarePaginator;
}
