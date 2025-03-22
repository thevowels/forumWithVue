<?php

use App\Models\Post;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

use App\Models\User;


beforeEach(function () {
    $this->validData = [
        'title' => 'Hello World',
        'body' => 'Tis is first post'
    ];
});

it('requires authentication', function () {
    post(route('posts.store'))
        ->assertRedirect(route('login'));
});

it('stores post', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post(route('posts.store'), $this->validData);
    $this->assertDatabaseHas(Post::class,[
        ...$this->validData,
        'user_id' => $user->id,
    ]);
});

it('redirects to show page', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post(route('posts.store'), $this->validData)
        ->assertRedirect( Post::latest('id')->first()->showRoute());


});


it('requires valid data' , function (array $badData, array|string $errors) {
    actingAs(User::factory()->create())
        ->post(route('posts.store'), [...$this->validData, ...$badData])
        ->assertInvalid($errors);

})->with([
    [['title'=>null], 'title'],
    [['title'=>true], 'title'],
    [['title'=>1], 'title'],
    [['title'=>1.5], 'title'],
    [['title'=>str_repeat('a',3)], 'title'],
    [['title'=>str_repeat('a', 121)], 'title'],

    [['body'=>null], 'body'],
    [['body'=>1], 'body'],
    [['body'=>3.4], 'body'],
    [['body'=>true], 'body'],
    [['body'=>str_repeat('a',3)], 'body'],
    [['body'=>str_repeat('a', 10001)], 'body'],


]);
































