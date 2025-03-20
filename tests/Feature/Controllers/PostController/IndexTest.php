<?php

use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;
use function Pest\Laravel\get;

use App\Models\Post;

use Inertia\Testing\AssertableInertia;

it('should return a correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('Posts/Index', true)
        );
});


it('shoudl passes posts to the view ' ,function (){

    $posts = Post::factory(3)->create();
    $posts->load('user');

    get(route('posts.index'))
            ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});
