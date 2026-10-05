<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',

        // Localized titles
        'title_fr',
        'title_ht',
        'title_en',
        'title_es',

        'position',
        'post_id',
        'image',
        'video',
        'url',
        'active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
