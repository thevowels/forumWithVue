<?php

use App\Models\Post;
use App\Models\Comment;

use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;

use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    get($post->showRoute())
        ->assertComponent('Posts/Show', true);
});

it('passes a post to the view' , function () {
    $post = Post::factory()->create();

    $post->load('user');
    get($post->showRoute())
        ->assertHasResource('post', PostResource::make($post));
});
it('passes comments to the view' , function () {
    $post = Post::factory()->create();
    $comments = Comment::factory(2)->for($post)->create();

    $comments->load('user');

    $post->load('user');
    get($post->showRoute())
        ->assertHasPaginatedResource('comments', CommentResource::collection($comments->reverse()));
});

it('will redirect if the slug is incorrect', function () {
    $post = Post::factory()->create(['title' => "Hello World"]);

    get(route('posts.show', [$post, 'foo-bar']))
        ->assertRedirect($post->showRoute());
});


it('query parameter is passed along in redirect', function () {
    $post = Post::factory()->create(['title' => "Hello World"]);

    get(route('posts.show', [$post, 'foo-bar','page' => 2]))
        ->assertRedirect($post->showRoute(['page'=>2]));
});
