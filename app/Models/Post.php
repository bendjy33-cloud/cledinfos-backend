<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        // French
        'title_fr',
        'content_fr',
        'meta_description_fr',
        'keywords_fr',

        // English
        'title_en',
        'content_en',
        'meta_description_en',
        'keywords_en',

        // Haitian Creole
        'title_ht',
        'content_ht',
        'meta_description_ht',
        'keywords_ht',

        // Spanish
        'title_es',
        'content_es',
        'meta_description_es',
        'keywords_es',

        // General
        'slug',

        // Images
        'image',
        'image_url',

        // Relationships
        'category_id',
        'author_id',

        // Status
        'featured',
        'views',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}