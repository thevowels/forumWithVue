<?php

use function Pest\Laravel\get;

use Inertia\Testing\AssertableInertia;

it('should return a correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('Posts/Index', true)
        );
});


it('shoudl passes posts to the view ' ,function (){
    get(route('posts.index'))
        ->assertInertia( fn (AssertableInertia $inertia) => $inertia
            ->has('posts')
        );
});
