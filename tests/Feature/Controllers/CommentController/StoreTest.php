<?php

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requies authentication', function() {
    $post = Post::factory()->create();
    post(route('posts.comments.store',$post ))
        ->assertRedirect(route('login'));
});

it('can store a comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => "Valid comment body",
    ]);

    $this->assertDatabaseHas(Comment::class, [
        'user_id' => $user->id,
        'post_id' => $post->id,
        'body' => "Valid comment body",
    ]);
});

it('redirects to a post showPage', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)->post(route('posts.comments.store', $post), [
        'body' => "Valid comment body",
    ])->assertRedirect(route('posts.show', $post));
});


it('requires a valid body', function ($value) {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    actingAs($user)->post(route('posts.comments.store', $post), [
        'body' =>$value,
    ])->assertInvalid('body');

})->with([
    null,
    1,
    1.5,
    true,
    str_repeat('a',2501)
]);
