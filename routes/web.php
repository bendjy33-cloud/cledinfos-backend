<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Development / Testing Routes
|--------------------------------------------------------------------------
|
| These routes are available ONLY locally.
| They are completely disabled in production.
|
*/

if (app()->environment('local')) {

    Route::get('/test', function () {
        return 'Laravel OK';
    });

    Route::get('/test-cloudinary', function () {

        $post = \App\Models\Post::latest()->first();

        if (!$post) {
            return response()->json([
                'error' => 'No post found.',
            ], 404);
        }

        $path = storage_path(
            'app/public/' . $post->image
        );

        return [
            'image' => $post->image,
            'image_url' => $post->image_url,
            'path' => $path,
            'exists' => file_exists($path),

            'upload' => app(
                \App\Services\CloudinaryService::class
            )->upload(
                $path,
                'cledinfos/posts'
            ),
        ];
    });

    Route::get('/db-test', function () {

        return [
            'connection' => \Illuminate\Support\Facades\DB::connection()
                ->getDatabaseName(),

            'posts' => \Illuminate\Support\Facades\DB::table('posts')
                ->count(),
        ];
    });

    Route::get('/run-migrations', function () {

        \Illuminate\Support\Facades\Artisan::call(
            'migrate',
            ['--force' => true]
        );

        return \Illuminate\Support\Facades\Artisan::output();
    });

    Route::get('/check-admin', function () {

        $user = \App\Models\User::where(
            'email',
            'admin@cledinfos.com'
        )->first();

        if (!$user) {
            return response()->json([
                'exists' => false,
                'message' => 'Admin user pa egziste.',
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
}