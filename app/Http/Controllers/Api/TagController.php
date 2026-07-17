<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        return TagResource::collection(
            Tag::where('is_active', true)
                ->orderBy('name')
                ->get()
        );
    }

    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'tag' => new TagResource($tag),

            'posts' => PostResource::collection(
                $tag->posts()
                    ->with(['category', 'tags'])
                    ->where('is_published', true)
                    ->latest('published_at')
                    ->get()
            ),
        ]);
    }
}