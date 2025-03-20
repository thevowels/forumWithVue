<?php

use App\Models\Post;
use function Pest\Laravel\get;

it('can show a post', function () {
    $post = Post::factory()->create();

    get(route('posts.show', $post))
        ->assertInertia(fn (\Inertia\Testing\AssertableInertia $inertia) => $inertia->component('Posts/Show', true));
});
