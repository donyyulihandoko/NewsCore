<?php

namespace App\Providers;

use App\Repositories\CommentRepository;
use App\Repositories\Impl\CommentRepositoryImpl;
use App\Services\CommentService;
use App\Services\Impl\CommentServiceImpl;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Override;

class CommentServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CommentService::class, CommentServiceImpl::class);
        $this->app->singleton(CommentRepository::class, CommentRepositoryImpl::class);
    }

    #[Override]
    public function provides():array
    {
        return [
            CommentRepository::class, 
            CommentService::class
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
