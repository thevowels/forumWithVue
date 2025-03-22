<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;


class PostController extends Controller
{

    use AuthorizesRequests;


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return inertia('Posts/Index',
            ['posts' => PostResource::collection(Post::with('user')->latest()->latest('id')->paginate())]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        Gate::authorize('create', Post::class);
        return inertia('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        $validated = $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:10000'],
        ]);
        $post = Post::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);
        return redirect($post->showRoute());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post)
    {
        if(! Str::contains($post->showRoute() , $request->path())) {
            return redirect($post->showRoute($request->query()));
        }
        $post->load('user');
        return inertia('Posts/Show',
            [
                'post' => fn () =>  PostResource::make($post),
                'comments' => fn () => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate()),
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
