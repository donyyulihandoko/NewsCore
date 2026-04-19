<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostService
{
    public function getUserRecentPost(): Collection;

    public function getPostsPagination(int $page): LengthAwarePaginator;

    public function createPost(array $data): Post;

    public function updatePost(Post $post, array $data): bool;

    public function removePost(Post $post): bool;

    public function getPostsByCategoryPagination(int $categoryId, int $page = 9): LengthAwarePaginator;

    public function getPostByAuthorId(int $author_id, int $perPage): LengthAwarePaginator;
}
