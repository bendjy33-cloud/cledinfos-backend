<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Titles
            'title_fr' => $this->title_fr,
            'subtitle_fr' => $this->subtitle_fr,

            'title_en' => $this->title_en,
            'subtitle_en' => $this->subtitle_en,

            'title_ht' => $this->title_ht,
            'subtitle_ht' => $this->subtitle_ht,

            'title_es' => $this->title_es,
            'subtitle_es' => $this->subtitle_es,

            // Slug
            'slug' => $this->slug,

            // Images
            'image' => $this->image_url,
            'image_url' => $this->image_url,

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'image' => $image->image_url,
                        'image_url' => $image->image_url,
                        'sort_order' => $image->sort_order,
                    ];
                })->values();
            }),

            // Content
            'content_fr' => $this->content_fr,
            'content_en' => $this->content_en,
            'content_ht' => $this->content_ht,
            'content_es' => $this->content_es,

            // SEO
            'meta_description_fr' => $this->meta_description_fr,
            'meta_description_en' => $this->meta_description_en,
            'meta_description_ht' => $this->meta_description_ht,
            'meta_description_es' => $this->meta_description_es,

            'keywords_fr' => $this->keywords_fr,
            'keywords_en' => $this->keywords_en,
            'keywords_ht' => $this->keywords_ht,
            'keywords_es' => $this->keywords_es,

            // Status / statistics
            'featured' => $this->featured,
            'views' => $this->views,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,

            // Category
            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),

            // Author
            'author' => new AuthorResource(
                $this->whenLoaded('author')
            ),

            // Tags
            'tags' => TagResource::collection(
                $this->whenLoaded('tags')
            ),
        ];
    }
}