<?php

use App\Models\Post;
use Illuminate\Support\Str;


it('use title case for titles' , function(){
    $post = Post::factory()->create(['title' => 'Hello World, how are you?']);

    expect($post->title)->toBe('Hello World, How Are You?');
});

it('can generate a root to the show pagee', function(){
    $post = Post::factory()->create();

    expect($post->showRoute())->toBe(route('posts.show', [$post, Str::slug($post->title)]));

});

it('can generate a route with additional parameters', function(){
    $post = Post::factory()->create();

    expect($post->showRoute(['page'=>2]))->toBe(route('posts.show', [$post, Str::slug($post->title), 'page'=>2]));

});

it('generates the HTML' , function(){
    $post = Post::factory()->make(['body' => '## Hello World']);

    $post->save();

    expect($post->html)->toEqual(str($post->body)->markdown());

});
