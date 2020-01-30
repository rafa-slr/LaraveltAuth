<?php

namespace App\Http\Controllers;

use App\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Post;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource by post.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCommentsByPost(Post $post){
        $pst = Post::findOrFail($post)->first();
        $comments = $pst->comentarios()->get();
        return response()->json($comments,200);
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
        $post = Post::findOrFail($request->post_id);

        $comentario = new Comentario;
        $comentario->comentario = $request->comentario;
        $comentario->user()->associate($user);
        $comentario->post()->associate($post);
        $comentario->save();
        return response()->json("Comentario creado",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function show(Comentario $comentario)
    {
        return $comentario;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comentario $comentario)
    {
        $comentario->comentario = $request->comentario;
        $comentario->save;
        return response()->json($comentario,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentario $comentario)
    {
        $comentario->delete();
        return response()->json(null,204);
    }
}
