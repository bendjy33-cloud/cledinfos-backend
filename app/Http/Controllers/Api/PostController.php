<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->latest('published_at')
                ->paginate(9)
        );
    }

    public function show(Request $request, string $slug)
    {
        $post = Post::with([
            'category',
            'author',
            'tags',
            'images',
        ])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $post->increment('views');
        $post->refresh();

        return new PostResource($post);
    }

    public function related(Request $request, string $slug)
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

    public function byCategory(Request $request, string $slug)
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
                    $builder
                        ->where('title_fr', 'like', "%{$query}%")
                        ->orWhere('title_en', 'like', "%{$query}%")
                        ->orWhere('title_ht', 'like', "%{$query}%")
                        ->orWhere('title_es', 'like', "%{$query}%")

                        ->orWhere('content_fr', 'like', "%{$query}%")
                        ->orWhere('content_en', 'like', "%{$query}%")
                        ->orWhere('content_ht', 'like', "%{$query}%")
                        ->orWhere('content_es', 'like', "%{$query}%")

                        ->orWhere('keywords_fr', 'like', "%{$query}%")
                        ->orWhere('keywords_en', 'like', "%{$query}%")
                        ->orWhere('keywords_ht', 'like', "%{$query}%")
                        ->orWhere('keywords_es', 'like', "%{$query}%")

                        ->orWhere('meta_title', 'like', "%{$query}%")

                        ->orWhere('meta_description_fr', 'like', "%{$query}%")
                        ->orWhere('meta_description_en', 'like', "%{$query}%")
                        ->orWhere('meta_description_ht', 'like', "%{$query}%")
                        ->orWhere('meta_description_es', 'like', "%{$query}%");
                })
                ->latest('published_at')
                ->get()
        );
    }

    public function featured(Request $request)
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

    public function mostRead(Request $request)
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->orderByDesc('views')
                ->take(5)
                ->get()
        );
    }

    public function trending(Request $request)
    {
        return PostResource::collection(
            Post::with(['category', 'author', 'tags'])
                ->where('is_published', true)
                ->orderByDesc('views')
                ->take(5)
                ->get()
        );
    }

    public function incrementView(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $post->increment('views');

        return response()->json([
            'success' => true,
            'views' => $post->views,
        ]);
    }
}