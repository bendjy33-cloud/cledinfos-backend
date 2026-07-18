
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\BreakingNewsController;


Route::get('/test-api', function () {
    return response()->json([
        'status' => 'OK'
    ]);
});


Route::get('/categories/{slug}/posts', [PostController::class, 'byCategory']);

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/search', [PostController::class, 'search']);

Route::get('/featured-posts', [PostController::class, 'featured']);

Route::get('/most-read', [PostController::class, 'mostRead']);

Route::get('/search', [PostController::class, 'search']);

Route::get('/home', [HomeController::class, 'index']);

Route::post('/contact', [ContactController::class, 'store']);

Route::get('/posts/{slug}/related', [PostController::class, 'related']);

Route::get('/settings', [SettingController::class, 'index']);

Route::post('/newsletter', [NewsletterController::class, 'store']);

Route::post('/posts/{slug}/view', [PostController::class,'incrementView']);

Route::get('/tags', [TagController::class, 'index']);

Route::get('/tags/{slug}', [TagController::class, 'show']);

Route::get('/authors', [AuthorController::class, 'index']);

Route::get('/authors/{slug}', [AuthorController::class, 'show']);

Route::get('/posts/{slug}/comments', [CommentController::class, 'index']);

Route::get('/ads', [AdController::class, 'index']);

Route::post('/posts/{slug}/comments', [CommentController::class, 'store']);

Route::get('/breaking-news', [BreakingNewsController::class, 'index']);

Route::get('/trending', [PostController::class, 'trending']);

