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

            'photo' => $this->photo
                ? asset('storage/' . $this->photo)
                : null,

            'job_title' => $this->job_title,

            'bio' => $this->bio,

            'facebook' => $this->facebook,

            'twitter' => $this->twitter,

            'linkedin' => $this->linkedin,

            'posts_count' => $this->whenCounted('posts'),

            'posts' => PostResource::collection(
                $this->whenLoaded('posts')
            ),
        ];
    }
}