<?php

use App\Models\Comment;
use App\Models\User;

use function Pest\Laravel\delete;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    delete(route('comments.destroy', Comment::factory()->create()))
        ->assertRedirect(route('login'));
});


it('redirects to post show page', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->delete(route('comments.destroy',$comment ))
        ->assertRedirect($comment->post->showRoute());
});
it('redirects to post show page with correct comments page', function () {
    $comment = Comment::factory()->create();
    actingAs($comment->user)
        ->delete(route('comments.destroy',['comment' => $comment,'page' => 2] ))
        ->assertRedirect($comment->post->showRoute(['page' => 2] ));

});

it('prevents deleting a comment you did not create', function () {
    $comment = Comment::factory()->create();
    actingAs(User::factory()->create())
        ->delete(route('comments.destroy',$comment ))
        ->assertForbidden();

});

it('prevents deleting a comment posted over an hour ago', function () {

        $this->freezeTime();
        $comment = Comment::factory()->create();

        $this->travel(1)->hour();

        actingAs($comment->user)
            ->delete(route('comments.destroy',$comment ))
            ->assertForbidden();


});
