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


/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
|
| These endpoints are consumed by the Next.js frontend.
| General public requests are protected with the API rate limiter.
|
*/

Route::middleware('throttle:api')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */

    Route::get('/home', [HomeController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

    Route::get('/posts', [PostController::class, 'index']);

    Route::get('/posts/{slug}', [PostController::class, 'show']);

    Route::get(
        '/posts/{slug}/related',
        [PostController::class, 'related']
    );


    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get(
        '/categories/{slug}/posts',
        [PostController::class, 'byCategory']
    );


    /*
    |--------------------------------------------------------------------------
    | Featured / Popular / Trending
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/featured-posts',
        [PostController::class, 'featured']
    );

    Route::get(
        '/most-read',
        [PostController::class, 'mostRead']
    );

    Route::get(
        '/trending',
        [PostController::class, 'trending']
    );


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    Route::get('/search', [PostController::class, 'search']);


    /*
    |--------------------------------------------------------------------------
    | Tags
    |--------------------------------------------------------------------------
    */

    Route::get('/tags', [TagController::class, 'index']);

    Route::get(
        '/tags/{slug}',
        [TagController::class, 'show']
    );


    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/authors',
        [AuthorController::class, 'index']
    );

    Route::get(
        '/authors/{slug}',
        [AuthorController::class, 'show']
    );


    /*
    |--------------------------------------------------------------------------
    | Comments - READ
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/posts/{slug}/comments',
        [CommentController::class, 'index']
    );


    /*
    |--------------------------------------------------------------------------
    | Ads / Breaking News / Settings
    |--------------------------------------------------------------------------
    */

    Route::get('/ads', [AdController::class, 'index']);

    Route::get(
        '/breaking-news',
        [BreakingNewsController::class, 'index']
    );

    Route::get(
        '/settings',
        [SettingController::class, 'index']
    );
});


/*
|--------------------------------------------------------------------------
| View Counter
|--------------------------------------------------------------------------
|
| This endpoint is called when someone views an article.
| It has its own limiter because it can be called frequently.
|
*/

Route::post(
    '/posts/{slug}/view',
    [PostController::class, 'incrementView']
)->middleware('throttle:views');


/*
|--------------------------------------------------------------------------
| User Interactions
|--------------------------------------------------------------------------
|
| These endpoints accept user input and therefore receive
| a stricter rate limit.
|
*/

Route::middleware('throttle:interactions')->group(function () {

    Route::post(
        '/posts/{slug}/comments',
        [CommentController::class, 'store']
    );

    Route::post(
        '/contact',
        [ContactController::class, 'store']
    );

    Route::post(
        '/newsletter',
        [NewsletterController::class, 'store']
    );
});