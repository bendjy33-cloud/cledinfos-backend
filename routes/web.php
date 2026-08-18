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


use Illuminate\Support\Facades\DB;

Route::get('/db-test', function () {

    return [
        'connection' => DB::connection()->getDatabaseName(),
        'posts' => DB::table('posts')->count(),
    ];

});

use Illuminate\Support\Facades\Artisan;

Route::get('/run-migrations', function () {
    Artisan::call('migrate', ['--force' => true]);

    return Artisan::output();
});

use App\Models\User;


Route::get('/check-admin', function () {
    $user = User::where('email', 'admin@cledinfos.com')->first();

    if (!$user) {
        return response()->json([
            'exists' => false,
            'message' => 'Admin user pa egziste nan database Render.'
        ]);
    }

    return response()->json([
        'exists' => true,
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'password_hash_exists' => !empty($user->password),
    ]);
});