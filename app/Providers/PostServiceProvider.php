<?php

namespace App\Providers;

use App\Repositories\Impl\PostRepositoryImpl;
use App\Repositories\PostRepository;
use App\Services\Impl\PostServiceImpl;
use App\Services\PostService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PostService::class, PostServiceImpl::class);
        $this->app->singleton(PostRepository::class, PostRepositoryImpl::class);
    }

    public function provides(): array
    {
        return [
            PostService::class,
            PostRepository::class
        ];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
