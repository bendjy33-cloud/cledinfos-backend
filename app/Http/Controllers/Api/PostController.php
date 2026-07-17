<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(9)
        );
    }

    public function show(string $slug)
    {
        $post = Post::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Incrémente automatiquement les vues
        $post->increment('views');
        $post->refresh();

        return new PostResource($post);
    }

    public function related(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->take(3)
                ->get()
        );
    }

    public function byCategory(string $slug)
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->whereHas('category', function ($query) use ($slug) {
                    $query->where('slug', $slug);
                })
                ->where('is_published', true)
                ->latest('published_at')
                ->get()
        );
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->where(function ($builder) use ($query) {
                    $builder->where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%")
                        ->orWhere('keywords', 'like', "%{$query}%");
                })
                ->latest('published_at')
                ->get()
        );
    }

    public function featured()
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->where('featured', true)
                ->latest('published_at')
                ->take(4)
                ->get()
        );
    }

    public function mostRead()
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->orderByDesc('views')
                ->take(5)
                ->get()
        );
    }

    public function trending()
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->orderByDesc('views')
                ->take(5)
                ->get()
        );
    }

    public function incrementView($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $post->increment('views');

        return response()->json([
            'success' => true,
            'views' => $post->views,
        ]);
    }
}