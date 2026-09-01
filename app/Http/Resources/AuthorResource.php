<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'photo' => $this->photo_url,

            'photo_url' => $this->photo_url,

            'job_title' => $this->job_title,

            /*
            |--------------------------------------------------------------------------
            | MULTILINGUAL BIO
            |--------------------------------------------------------------------------
            */

            'bio_fr' => $this->bio_fr,

            'bio_en' => $this->bio_en,

            'bio_ht' => $this->bio_ht,

            'bio_es' => $this->bio_es,

            /*
            |--------------------------------------------------------------------------
            | SOCIAL LINKS
            |--------------------------------------------------------------------------
            */

            'facebook' => $this->facebook,

            'twitter' => $this->twitter,

            'linkedin' => $this->linkedin,

            /*
            |--------------------------------------------------------------------------
            | POSTS
            |--------------------------------------------------------------------------
            */

            'posts_count' => $this->whenCounted('posts'),

            'posts' => PostResource::collection(
                $this->whenLoaded('posts')
            ),
        ];
    }
}