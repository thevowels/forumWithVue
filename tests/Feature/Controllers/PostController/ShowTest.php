<?php

use App\Models\Post;

use App\Http\Resources\PostResource;
use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post))
        ->assertComponent('Posts/Show', true);
});

it('passes a post to the view' , function () {
    $post = Post::factory()->create();

    $post->load('user');
    get(route('posts.show', $post))
        ->assertHasResource('post', PostResource::make($post));
});
