<?php

namespace App\Repositories\Impl;

use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostRepositoryImpl implements PostRepository
{
    // crud

    public function createPost(array $data): Post
    {
        // $data['user_id'] = Auth::user()->id;
        // return Post::query()->create($data);

        $user = User::query()->findOrFail(Auth::user()->id);
        return $user->posts()->create($data);

        // return Auth::user()->posts()->create($data);
    }

    public function updatePost(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    public function removePost(Post $post): bool
    {
        return $post->delete();
    }

    public function findBySlug(Post $post): Post
    {
        return $post->load(['category', 'author']);
    }

    // role admin
    public function totalAllPosts(): ?int
    {
        return Post::query()
            ->with(['author', 'category'])
            ->count();
    }

    public function totalPublishedPosts(): ?int
    {
        return Post::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->count();
    }

    public function totalPendingPosts(): ?int
    {
        return Post::query()
            ->with(['author', 'category'])
            ->where('is_published', false)
            ->count();
    }

    public function getPostsPagination(int $page): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->latest()
            ->paginate($page);
    }

    public function getPublishedPosts(int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->withCount(['author', 'category'])
            ->where('is_published', true)
            ->paginate($perPage);
    }

    public function getPendingPosts(int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->withCount(['author', 'category'])
            ->where('is_published', false)
            ->paginate($perPage);
    }

    public function approvalPendingPost(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    public function recentActivity(): Collection
    {
        return Post::query()
            ->with(['author', 'category'])
            ->where('is_published', true)
            ->latest('updated_at')
            ->take(4)
            ->get();
    }

    // role author
    public function getPostsByAuthorId(int $authorId, int $perPage = 9): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->author($authorId)
            ->latest()
            ->paginate($perPage);
    }

    public function totalPostsByAuthor(int $authorId): ?int
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->count();
    }

    public function getRecentPostsByAuthor(int $authorId): Collection
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->take(5)
            ->latest()
            ->get();
    }

    public function totalPendingPostsByAuthor(int $authorId): ?int
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->where('is_published', false)
            ->count();
    }

    public function getPendingPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->where('is_published', false)
            ->paginate($perPage);
    }

    public function totalPublishedPostsByAuthor(int $authorId): ?int
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->where('is_published', true)
            ->count();
    }

    public function getPublishedPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('user_id', $authorId)
            ->where('is_published', true)
            ->paginate($perPage);
    }


    // role user
    public function getUserRecentPosts(): Collection
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('is_published', true)
            ->take(3)
            ->latest()
            ->get();
    }

    public function getUserPublishedPosts(int $perPage = 9): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('is_published', true)
            ->latest()
            ->paginate($perPage);
    }

    public function getPublishedPostsByCategory(int $categoryId, int $perPage = 9): LengthAwarePaginator
    {
        return Post::query()
            ->with(['category', 'author'])
            ->where('is_published', true)
            ->where('category_id', $categoryId)
            ->latest()
            ->paginate($perPage);
    }
}
