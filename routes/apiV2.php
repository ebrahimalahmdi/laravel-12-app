<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// // =====//// =====//// =====//// =====//// =====//
Route::controller(UserController::class)->group(function () {

    Route::prefix('/user')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/', 'index');
            Route::get('/getuser', 'GetUser'); ///http://laravel-12-app.test/api/user/getuser
            Route::get('/getuserwithprofile', 'GetUserWithProfile'); ///http://laravel-12-app.test/api/user/getuserwithprofile/
            // // =====//// =====//// =====//// =====//// =====//
            Route::post('/logout', 'logout');
            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/{id}', 'show');
            // not used
            Route::get('/{id}/profile', 'getprofile');
            Route::get('/{id}/tasks', 'getAllTaskByUserId');
        });
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        // Route::post('/logout', 'logout')->middleware('auth:sanctum');;
    });
});
// // =====//// =====//// =====//// =====//// =====//


// app\Http\Controllers\RestAPI\v2\TaskController
// // =====//// =====//// =====//// =====//// =====//
Route::controller(TaskController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('/tasks')->group(function () {

            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::post('', 'store');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser');
            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/ordered', 'GetTasksByPriorty');
            // // // =====//// =====//// =====//// =====//// =====//
            // // // =====//// =====//// =====//// =====//// =====//
            Route::get('/{id}/user', 'GetUserInfoByTaskBelongToUser');
            Route::post('/{TaskId}/categories', 'AddCategoriesToTask'); // this is Post method 
            Route::get('/{TaskId}/categories', 'GetTaskCategories'); // this is Get method 
            // Route::get('/categories/{TaskId}/tasks', 'GetCategoriesTask');
            Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
            // // =====//// =====//// =====//// =====//// =====//
            Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
            // // =====//// =====//// =====//// =====//// =====//
            Route::post('/{id}/favorite', 'addToFavorite');
            Route::delete('/{id}/favorite', 'removeFromFavorite'); ////
            Route::get('/favorite', 'getFavoriteTasks'); ////tasks/favorite
            // // =====//// =====//// =====//// =====//// =====//

        });
    });
});
// // =====//// =====//// =====//// =====//// =====//


// // // =====//// =====//// =====//// =====//// =====//
// Route::controller(TaskController::class)->group(function () {
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::prefix('/tasks')->group(function () {

//             // // =====//// =====//// =====//// =====//// =====//
//             Route::post('/{id}/favorite', 'addToFavorite');
//             Route::delete('/{id}/favorite', 'removeFromFavorite'); ////
//             Route::get('/favorite', 'getFavoriteTasks'); ////tasks/favorite
//             // // =====//// =====//// =====//// =====//// =====//

//             Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/ordered', 'GetTasksByPriorty');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/', 'index');
//             Route::get('/{id}', 'show');
//             Route::post('', 'store');
//             Route::put('/{id}', 'update');
//             Route::delete('/{id}', 'destroy');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/{id}/user', 'GetUserInfoByTaskBelongToUser');
//             Route::post('/{TaskId}/categories', 'AddCategoriesToTask'); // this is Post method 
//             Route::get('/{TaskId}/categories', 'GetTaskCategories'); // this is Get method 
//             // Route::get('/categories/{TaskId}/tasks', 'GetCategoriesTask');
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
//         });
//     });
// });
// // // =====//// =====//// =====//// =====//// =====//


// // =====//// =====//// =====//// =====//// =====//
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/profile')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });
    });
});
// // =====//// =====//// =====//// =====//// =====//
