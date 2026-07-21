<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return 'TEST OK';
});

use App\Models\User;

Route::get('/check-users', function () {
    return User::select('id', 'name', 'email')->get();
});