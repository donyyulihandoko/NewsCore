<?php

namespace App\Services\Impl;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PostServiceImpl implements PostService
{

    public function __construct(private PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPostsPagination(int $page): LengthAwarePaginator
    {
        return $this->postRepository->getPostsPagination($page);
    }

    public function createPost(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            return $this->postRepository->createPost($data);
        });
    }

    public function updatePost(Post $post, array $data): bool
    {
        return DB::transaction(function () use ($post, $data) {
            return $this->postRepository->updatePost($post, $data);
        });
    }

    public function removePost(Post $post): bool
    {
        return DB::transaction(function () use ($post) {
            return $this->postRepository->removePost($post);
        });
    }

    public function getPostsByCategoryPagination(int $categoryId, int $page = 9): LengthAwarePaginator
    {
        return $this->postRepository->getPostsByCategoryPagination($categoryId, $page);
    }
}
