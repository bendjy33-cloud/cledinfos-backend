<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Models\Post;

Route::get('/test-post', function () {

    return Post::create([
        'title' => 'Test',
        'slug' => 'test-' . time(),
        'content' => 'Test',
        'image' => 'https://example.com/test.jpg',
        'category_id' => 1,
        'author_id' => 1,
        'keywords' => 'test',
        'featured' => false,
        'views' => 0,
        'is_published' => true,
        'published_at' => now(),
    ]);

});