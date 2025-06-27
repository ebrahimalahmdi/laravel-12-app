<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// http://laravel-12-app.test/
// http://laravel-12-app.test/api/user