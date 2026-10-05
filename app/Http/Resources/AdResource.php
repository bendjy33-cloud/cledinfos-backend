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

            // =====================================================
            // TITRES
            // =====================================================

            'title' => $this->title,

            'title_fr' => $this->title_fr,

            'title_ht' => $this->title_ht,

            'title_en' => $this->title_en,

            'title_es' => $this->title_es,

            // =====================================================
            // POSITION
            // =====================================================

            'position' => $this->position,

            // =====================================================
            // ARTICLE ASSOCIÉ
            // =====================================================

            'post_id' => $this->post_id,

            // =====================================================
            // MEDIA
            // =====================================================

            'image' => $this->image,

            'video' => $this->video,

            // =====================================================
            // LIEN
            // =====================================================

            'url' => $this->url,

            // =====================================================
            // STATUT
            // =====================================================

            'active' => $this->active,

            // =====================================================
            // DATES
            // =====================================================

            'starts_at' => $this->starts_at,

            'ends_at' => $this->ends_at,

        ];
    }
}