<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name_fr' => $this->name_fr,
            'name_en' => $this->name_en,
            'name_ht' => $this->name_ht,

            'slug' => $this->slug,

            'description_fr' => $this->description_fr,
            'description_en' => $this->description_en,
            'description_ht' => $this->description_ht,

            'is_active' => $this->is_active,
        ];
    }
}