<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        //
        Gate::authorize('create', Comment::class);
        $comment = Comment::create([
            ...$request->validate(['body' => ['required', 'string', 'max:2500']]),
            'post_id'=>$post->id,
            'user_id'=>$request->user()->id,

        ]);

        return to_route('posts.show', $post)
            ->banner('You have added a comment.');


    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500', 'min:5'],
        ]);
        Gate::authorize('update', $comment);
        $comment->update($validated);
        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')])
            ->banner('Your comment has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        //Usage as model's can/cannot
//        if($request->user()->cannot('delete', $comment)){
//            abort(403);
//        }
        //usage as Gate
        Gate::authorize('delete',  $comment);
        $comment->delete();
        return to_route('posts.show', ['post' => $comment->post_id, 'page' => $request->query('page')])
            ->banner("You've successfully deleted your comment.");

    }
}
