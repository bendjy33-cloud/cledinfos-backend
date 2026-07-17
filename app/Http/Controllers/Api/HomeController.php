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
        $hero = Post::with('category')
            ->where('is_published', true)
            ->latest('published_at')
            ->first();

        $latest = Post::with('category')
            ->where('is_published', true)
            ->when($hero, fn ($query) => $query->where('id', '!=', $hero->id))
            ->latest('published_at')
            ->paginate(9);

        return response()->json([
            'hero' => $hero ? new PostResource($hero) : null,

            'featured' => PostResource::collection(
                Post::with('category')
                    ->where('featured', true)
                    ->where('is_published', true)
                    ->when($hero, fn ($query) => $query->where('id', '!=', $hero->id))
                    ->latest('published_at')
                    ->take(4)
                    ->get()
            ),

            'latest' => [
                'data' => PostResource::collection($latest->items()),
                'current_page' => $latest->currentPage(),
                'last_page' => $latest->lastPage(),
                'total' => $latest->total(),
            ],

            'mostRead' => PostResource::collection(
                Post::with('category')
                    ->where('is_published', true)
                    ->orderByDesc('views')
                    ->take(5)
                    ->get()
            ),

            'trending' => PostResource::collection(
                Post::with(['category','author','tags'])
                    ->where('is_published', true)
                    ->orderByDesc('views')
                    ->take(5)
                    ->get()
            ),

            'categories' => CategoryResource::collection(
                Category::where('is_active', true)->get()
            ),
        ]);
    }
}