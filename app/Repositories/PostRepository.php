<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostRepository
{
    // Crud

    public function createPost(array $data): Post;

    public function updatePost(Post $post, array $data): bool;

    public function removePost(Post $post): bool;

    public function findBySlug(Post $post): Post;


    // role admin
    public function totalAllPosts(): ?int;

    public function totalPublishedPosts(): ?int;

    public function totalPendingPosts(): ?int;

    public function getPostsPagination(int $page): LengthAwarePaginator;

    public function getPublishedPosts(int $perPage): LengthAwarePaginator;

    public function getPendingPosts(int $perPage): LengthAwarePaginator;

    public function approvalPendingPost(Post $post, array $data): bool;

    public function recentActivity(): Collection;


    // role author
    public function getPostsByAuthorId(int $authorId, int $perPage = 9): LengthAwarePaginator;

    public function totalPostsByAuthor(int $authorId): ?int;

    public function getRecentPostsByAuthor(int $authorId): Collection;

    public function totalPendingPostsByAuthor(int $authorId): ?int;

    public function getPendingPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator;

    public function totalPublishedPostsByAuthor(int $authorId): ?int;

    public function getPublishedPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator;


    // role user
    public function getUserRecentPosts(): Collection;

    public function getUserPublishedPosts(int $perPage = 9): LengthAwarePaginator;

    public function getPublishedPostsByCategory(int $categoryId, int $perPage = 9): LengthAwarePaginator;
}
