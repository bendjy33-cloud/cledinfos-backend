<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        // French
        'name_fr',
        'description_fr',

        // English
        'name_en',
        'description_en',

        // Haitian Creole
        'name_ht',
        'description_ht',

        // Spanish
        'name_es',
        'description_es',

        // General
        'slug',
        'is_active',
    ];

    protected $appends = [
        'display_name',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Nom d'affichage utilisé par Filament.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name_fr
            ?? $this->name_en
            ?? $this->name_ht
            ?? $this->name_es
            ?? 'Sans catégorie';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}