<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_fr',
        'name_en',
        'name_ht',
        'slug',
        'description_fr',
        'description_en',
        'description_ht',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->name_fr
            ?: $this->name_en
            ?: $this->name_ht
            ?: $this->slug
            ?: 'Sans nom';
    }
}