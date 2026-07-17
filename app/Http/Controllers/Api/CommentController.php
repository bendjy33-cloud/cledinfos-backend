<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function index($slug)
    {
        $post = Post::where('slug', $slug)
            ->firstOrFail();


        return CommentResource::collection(
            $post->comments()
                ->where('is_approved', true)
                ->latest()
                ->get()
        );
    }



    public function store(Request $request, $slug)
    {

        $post = Post::where('slug', $slug)
            ->firstOrFail();


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:100'
            ],

            'email' => [
                'required',
                'email'
            ],

            'comment' => [
                'required',
                'string'
            ],

        ]);



        $comment = $post->comments()->create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'comment' => $validated['comment'],

            'is_approved' => false,

        ]);



        return response()->json([

            'message' =>
                'Votre commentaire est en attente de validation.',

            'comment' =>
                new CommentResource($comment),

        ], 201);

    }

}