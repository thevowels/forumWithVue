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

    Route::resource('posts', PostController::class)->only(['create','store']);

    Route::resource('posts.comments', CommentController::class)->shallow()->only('store', 'update', 'destroy');
});


Route::resource('posts', PostController::class)->only(['index']);

Route::get('/posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');


