<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Services\CloudinaryService;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "Laravel OK";
});

Route::get('/test-cloudinary', function () {

    $post = Post::latest()->first();

    $path = storage_path('app/public/' . $post->image);

    return [
        'image' => $post->image,
        'image_url' => $post->image_url,
        'path' => $path,
        'exists' => file_exists($path),
        'upload' => app(CloudinaryService::class)->upload(
            $path,
            'cledinfos/posts'
        ),
    ];
});