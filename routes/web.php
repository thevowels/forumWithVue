<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

use App\Http\Resources\UserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('posts', PostController::class)->only(['store']);

    Route::resource('posts.comments', CommentController::class)->shallow()->only('store', 'update', 'destroy');
//    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
//    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
//    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

Route::resource('posts', PostController::class)->only(['index', 'show']);
//Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
//
//Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');


