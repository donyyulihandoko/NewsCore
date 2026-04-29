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
        // 
    }

    // crud
    private function handleImage(?UploadedFile $file, ?string $oldPath = null)
    {
        // no upload file
        if (!$file) return $oldPath;

        // jika ada file lama (update file)
        if ($oldPath) Storage::disk('public')->delete($oldPath);

        // simpan file baru
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
            $pathImage = $post->image;
            $delete = $this->postRepository->removePost($post);

            if ($pathImage && $delete) Storage::disk('public')->delete($pathImage);

            return (bool) $delete;
        });
    }

    public function findBySlug(Post $post): Post
    {
        return $this->postRepository->findBySlug($post);
    }

    // role admin
    public function totalAllPosts(): ?int
    {
        return $this->postRepository->totalAllPosts();
    }

    public function totalPublishedPosts(): ?int
    {
        return $this->postRepository->totalPublishedPosts();
    }

    public function totalPendingPosts(): ?int
    {
        return $this->postRepository->totalPendingPosts();
    }

    public function getPostsPagination(int $page): LengthAwarePaginator
    {
        return $this->postRepository->getPostsPagination($page);
    }


    public function getPublishedPosts(int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPublishedPosts($perPage);
    }

    public function getPendingPosts(int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPendingPosts($perPage);
    }

    public function approvalPendingPost(Post $post, array $data): bool
    {
        return DB::transaction(function () use ($post, $data) {
            return $this->postRepository->approvalPendingPost($post, $data);
        });
    }

    public function recentActivity(): Collection
    {
        return $this->postRepository->recentActivity();
    }


    // role author
    public function getPostsByAuthorId(int $authorId, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPostsByAuthorId($authorId, $perPage);
    }

    public function totalPostsByAuthor(int $authorId): ?int
    {
        return $this->postRepository->totalPostsByAuthor($authorId);
    }

    public function getRecentPostsByAuthor(int $authorId): Collection
    {
        return $this->postRepository->getRecentPostsByAuthor($authorId);
    }

    public function totalPendingPostsByAuthor(int $authorId): ?int
    {
        return $this->postRepository->totalPendingPostsByAuthor($authorId);
    }

    public function getPendingPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPendingPostsByAuthor($authorId, $perPage);
    }

    public function totalPublishedPostsByAuthor(int $authorId): ?int
    {
        return $this->postRepository->totalPublishedPostsByAuthor($authorId);
    }

    public function getPublishedPostsByAuthor(int $authorId, int $perPage): LengthAwarePaginator
    {
        return $this->postRepository->getPublishedPostsByAuthor($authorId, $perPage);
    }



    // role user
    public function getUserRecentPosts(): Collection
    {
        return $this->postRepository->getUserRecentPosts();
    }

    public function getUserPublishedPosts(int $perPage = 9): LengthAwarePaginator
    {
        return $this->postRepository->getUserPublishedPosts($perPage);
    }

    public function getPublishedPostsByCategory(int $categoryId, int $perPage = 9): LengthAwarePaginator
    {
        return $this->postRepository->getPublishedPostsByCategory($categoryId, $perPage);
    }
}
