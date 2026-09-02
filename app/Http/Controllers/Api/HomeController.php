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
        | FEATURED
        |--------------------------------------------------------------------------
        |
        | Featured = 5 atik ki pi resan yo.
        |
        | Egzanp:
        | 10, 9, 8, 7, 6
        |
        */

        $featured = Post::with([
            'category',
            'author',
            'tags',
        ])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | LATEST POSTS
        |--------------------------------------------------------------------------
        |
        | Tout dènye atik yo, kòmanse ak atik ki pi resan an.
        |
       

        $latest = Post::with([
            'category',
            'author',
            'tags',
        ])
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(9); */


        /*
        |--------------------------------------------------------------------------
        | TRENDING
        |--------------------------------------------------------------------------
        |
        | 5 atik ki gen plis views.
        |
        */

        $trending = Post::with([
            'category',
            'author',
            'tags',
        ])
            ->where('is_published', true)
            ->orderByDesc('views')
            ->orderByDesc('id')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        $categories = Category::where('is_active', true)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

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
            

            'latest' => [
                'data' => PostResource::collection(
                    $latest->items()
                ),

                'current_page' => $latest->currentPage(),

                'last_page' => $latest->lastPage(),

                'total' => $latest->total(),
            ], */


            /*
            |--------------------------------------------------------------------------
            | TRENDING
            |--------------------------------------------------------------------------
            */

            'trending' => PostResource::collection($trending),


            /*
            |--------------------------------------------------------------------------
            | CATEGORIES
            |--------------------------------------------------------------------------
            */

            'categories' => CategoryResource::collection($categories),
        ]);
    }
}