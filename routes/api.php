<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get(('/test'), function () {
    return response()->json(['message' => 'This is a test endpoint']);
});
// http://laravel-12-app.test/api/test

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, World!']);
});
// http://laravel-12-app.test/api/hello

//  git add .
//  git commit -m "Add API routes for user and test endpoints"
//  git push origin main
//     git push --set-upstream origin TaskManager
//  git commit --amend -m "Add API routes after user and test endpoints"
