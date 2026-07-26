<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-cloudinary', function () {
    return [
        'cloud_name' => config('cloudinary.cloud.cloud_name'),
        'api_key' => config('cloudinary.cloud.api_key'),
        'api_secret' => config('cloudinary.cloud.api_secret') ? 'OK' : 'MISSING',
    ];
});