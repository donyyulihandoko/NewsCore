<?php

namespace App\Services\Impl;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostServiceImpl implements PostService
{

    public function __construct(private PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getUserRecentPost(): Collection
    {
        return $this->postRepository->getUserRecentPost();
    }

    public function getPostsPagination(int $page): LengthAwarePaginator
    {
        return $this->postRepository->getPostsPagination($page);
    }

    private function handleImage(?UploadedFile $file, ?string $oldPath = null)
    {
        if (!$file) return $oldPath;

        if ($oldPath) return  Storage::disk('public')->delete($oldPath);

        return $file->store('post-images', 'public');
    }

    public function createPost(array $data): Post
    {
        return DB::transaction(function () use ($data) {

            $data['image'] = $this->handleImage($data['image'] ?? null);
            $result = $this->postRepository->createPost($data);

            return $result;
        });
    }

    public function updatePost(Post $post, array $data): bool
    {
        return DB::transaction(function () use ($post, $data) {

            $data['image'] = $this->handleImage($data['image'] ?? null, $post->image);

            $result = $this->postRepository->updatePost($post, $data);

            return $result;
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

    public function getPostByAuthorId(int $author_id, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPostByAuthorId($author_id, $perPage);
    }
}
