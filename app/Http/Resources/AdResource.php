<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'title' => $this->title,

            'position' => $this->position,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'url' => $this->url,

            'active' => $this->active,

            'starts_at' => $this->starts_at,

            'ends_at' => $this->ends_at,

        ];
    }
}