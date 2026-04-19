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
    public function getUserRecentPost(): Collection
    {
        return Post::with(['author', 'category'])
            ->where('is_published', true)
            ->take(3)
            ->latest()
            ->get();
    }

    public function getPostsPagination(int $page): LengthAwarePaginator
    {
        return Post::with(['category', 'author'])->latest()->paginate($page);
    }

    public function createPost(array $data): Post
    {
        return Auth::user()->posts()->create($data);
    }

    public function updatePost(Post $post, array $data): bool
    {
        return $post->update($data);
    }

    public function removePost(Post $post): bool
    {
        return $post->delete();
    }

    public function getPostsByCategoryPagination(int $categoryId, int $page = 9): LengthAwarePaginator
    {
        return Post::with(['category', 'author'])
            ->where('category_id', $categoryId)
            ->latest()
            ->paginate($page);
    }

    public function getPostByAuthorId(int $author_id, int $perPage = 9): LengthAwarePaginator
    {
        return Post::with(['category', 'author'])
            ->where('user_id', $author_id)
            ->latest()
            ->paginate($perPage);
    }
}
