<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'photo',
        'photo_url',
        'job_title',

        // Multilingual biographies
        'bio_fr',
        'bio_en',
        'bio_ht',
        'bio_es',

        // Old bio field kept for compatibility
        'bio',

        'facebook',
        'twitter',
        'linkedin',
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
}