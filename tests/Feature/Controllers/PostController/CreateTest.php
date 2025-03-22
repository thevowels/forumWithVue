<?php

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

use App\Models\User;

it('requires authentication ', function () {
    get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('returns a correct ocmponent', function () {
    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
