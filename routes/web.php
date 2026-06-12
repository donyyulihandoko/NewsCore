<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Author\CommentController as AuthorCommentController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\PostController as AuthorPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CategoryController as UserCategoryController;
use App\Http\Controllers\User\CommentController as UserCommentController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\PostController as UserPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/posts/published', [DashboardController::class, 'publishedPost'])->name('published.post');
    Route::get('/posts/pending', [DashboardController::class, 'pendingPost'])->name('pending.post');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/posts', PostController::class);
    Route::resource('/comments', CommentController::class);
});


Route::middleware(['auth', 'verified', 'is_author'])->name('author.')->prefix('author')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::get('posts/published', [AuthorDashboardController::class, 'publishedPost'])->name('published.post');
    Route::get('posts/pending', [AuthorDashboardController::class, 'pendingPost'])->name('pending.post');
    Route::resource('/posts', AuthorPostController::class);
    Route::resource('/posts.comments', AuthorCommentController::class)->only('store', 'destroy')->shallow();
});

Route::middleware(['auth', 'verified', 'is_user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/posts', UserPostController::class)->only(['index', 'show']);
    Route::resource('/topics', UserCategoryController::class)->only('index', 'show')->parameters(['topics' => 'category']);
    // Route::resource('/comments', UserCommentController::class);
    Route::resource('/posts.comments', UserCommentController::class)->only('store', 'destroy')->shallow();
});


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
