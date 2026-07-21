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

use Illuminate\Support\Facades\Hash;

Route::get('/create-admin', function () {
    $user = User::firstOrCreate(
        ['email' => 'admin@cledinfos.com'],
        [
            'name' => 'Admin',
            'password' => Hash::make('Admin@12345'),
        ]
    );

    return $user;
});