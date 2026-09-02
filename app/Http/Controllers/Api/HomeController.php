<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | HERO
        |--------------------------------------------------------------------------
        */

        $hero = Post::with('category')
            ->where('is_published', true)
            ->latest('published_at')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | LATEST POSTS
        |--------------------------------------------------------------------------
        |
        | Tout dènye atik yo.
        | Hero a pa ladan l pou evite menm atik la parèt 2 fwa.
        |
        */

        $latest = Post::with('category')
            ->where('is_published', true)
            ->when(
                $hero,
                fn ($query) => $query->where('id', '!=', $hero->id)
            )
            ->latest('published_at')
            ->paginate(9);


        /*
        |--------------------------------------------------------------------------
        | FEATURED
        |--------------------------------------------------------------------------
        |
        | Featured ap itilize menm dènye atik yo.
        |
        | Nou retire ->take(4), kidonk pa gen limit 4 ankò.
        |
        */

        $featured = Post::with('category')
            ->where('is_published', true)
            ->when(
                $hero,
                fn ($query) => $query->where('id', '!=', $hero->id)
            )
            ->latest('published_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            /*
            |--------------------------------------------------------------------------
            | HERO
            |--------------------------------------------------------------------------
            */

            'hero' => $hero
                ? new PostResource($hero)
                : null,


            /*
            |--------------------------------------------------------------------------
            | FEATURED
            |--------------------------------------------------------------------------
            */

            'featured' => PostResource::collection($featured),


            /*
            |--------------------------------------------------------------------------
            | LATEST
            |--------------------------------------------------------------------------
            */

            'latest' => [
                'data' => PostResource::collection(
                    $latest->items()
                ),

                'current_page' => $latest->currentPage(),

                'last_page' => $latest->lastPage(),

                'total' => $latest->total(),
            ],


            /*
            |--------------------------------------------------------------------------
            | MOST READ
            |--------------------------------------------------------------------------
            */

            'mostRead' => PostResource::collection(
                Post::with('category')
                    ->where('is_published', true)
                    ->orderByDesc('views')
                    ->take(5)
                    ->get()
            ),


            /*
            |--------------------------------------------------------------------------
            | TRENDING
            |--------------------------------------------------------------------------
            */

            'trending' => PostResource::collection(
                Post::with([
                    'category',
                    'author',
                    'tags',
                ])
                    ->where('is_published', true)
                    ->orderByDesc('views')
                    ->take(5)
                    ->get()
            ),


            /*
            |--------------------------------------------------------------------------
            | CATEGORIES
            |--------------------------------------------------------------------------
            */

            'categories' => CategoryResource::collection(
                Category::where('is_active', true)->get()
            ),
        ]);
    }
}