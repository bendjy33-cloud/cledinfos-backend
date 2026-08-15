<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreakingNewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', 'fr');

        $title = match ($locale) {
            'en' => $this->title_en
                ?? $this->title_fr
                ?? $this->title_ht,

            'ht' => $this->title_ht
                ?? $this->title_fr
                ?? $this->title_en,

            default => $this->title_fr
                ?? $this->title_en
                ?? $this->title_ht,
        };

        return [
            'id' => $this->id,

            'title' => $title,

            'link' => $this->link,

            'active' => $this->active,

            'starts_at' => $this->starts_at,

            'ends_at' => $this->ends_at,
        ];
    }
}