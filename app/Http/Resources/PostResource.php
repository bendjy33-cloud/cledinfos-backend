<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\AuthorResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'content' => $this->content,

            'meta_description' => $this->meta_description,

            'keywords' => $this->keywords,

            'featured' => $this->featured,

            'views' => $this->views,

            'published_at' => $this->published_at,

            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            'author' => new AuthorResource(
                $this->whenLoaded('author')
            ),

            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),
        ];
    }
}