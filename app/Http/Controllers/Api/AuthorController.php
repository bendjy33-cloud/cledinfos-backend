<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        return AuthorResource::collection(
            Author::where('is_active', true)
                ->withCount('posts')
                ->orderBy('name')
                ->get()
        );
    }

    public function show(string $slug)
    {
        $author = Author::with([
                'posts' => function ($query) {
                    $query->with([
                            'category',
                            'author',
                            'tags',
                        ])
                        ->where('is_published', true)
                        ->latest('published_at');
                },
            ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return new AuthorResource($author);
    }
}