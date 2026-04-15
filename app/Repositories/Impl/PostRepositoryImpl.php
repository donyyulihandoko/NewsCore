<?php

namespace App\Repositories\Impl;

use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PostRepositoryImpl implements PostRepository
{
    public function getPostsPagination(): LengthAwarePaginator
    {
        return Post::with(['category', 'author'])->latest()->paginate(10);
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
}
