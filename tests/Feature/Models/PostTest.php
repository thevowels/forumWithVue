<?php

use App\Models\Post;


it('use title case for titles' , function(){
    $post = Post::factory()->create(['title' => 'Hello World, how are you?']);

    expect($post->title)->toBe('Hello World, How Are You?');
});
