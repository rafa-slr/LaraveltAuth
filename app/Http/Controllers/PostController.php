<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;  

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No hay ningun post']);
        }else{
            return response()->json($posts);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $post = new Post;
        $post->contenido = $request->contenido;
        $post->user()->associate($user)->save();
        $post->save();
        return response()->json(['message' => 'Post creado correctamente'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post;
        /*$pst = Post::findOrFail($post);
        return response()->json($pst,200);*/
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return response()->json($post,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null,204);
    }

    public function findPostsByUser(){
        $user = Auth::user();
        $posts = $user->posts()->get();
        if(!$posts->isEmpty()){
            return response()->json($posts,200);
        }else{
            return response()->json(['message' => 'No tienes ningun post aun'],401);
        }
       
    }
}
