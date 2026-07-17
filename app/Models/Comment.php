<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = [
        'post_id',
        'name',
        'email',
        'comment',
        'is_approved',
    ];


    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
        ];
    }


    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}