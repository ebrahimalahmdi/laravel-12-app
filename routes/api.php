<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// // =====//// =====//// =====//// =====//// =====//
// Route::controller(function () {
Route::controller(TaskController::class)->group(function () {
    Route::get('/tasks', 'index');
    Route::get('/tasks/{id}', 'show');
    Route::post('/tasks', 'store');
    Route::put('tasks/{id}', 'update');
    Route::delete('tasks/{id}', 'destroy');
});
// // =====//// =====//// =====//// =====//// =====//





// // =====//// =====//// =====//// =====//// =====//
Route::controller(ProfileController::class)->group(function () {

    Route::get('/profile', 'index');
    Route::post('/profile', 'store');
    Route::get('/profile/{id}', 'show');
    Route::put('profile/{id}', 'update');
});
// // =====//// =====//// =====//// =====//// =====//





// // =====//// =====//// =====//// =====//// =====//
Route::controller(UserController::class)->group(function () {

    Route::get('user', 'index');
    Route::post('user', 'store');
    Route::get('user/{id}', 'show');
    Route::get('user/{id}/profile', 'getprofile');
    Route::get('user/{id}/tasks', 'getAllTaskByUserId');
});
// // =====//// =====//// =====//// =====//// =====//


// // =====//// =====//// =====//// =====//// =====//
Route::get('/tasks/{id}/user', [TaskController::class, 'GetUserInfoByTaskBelongToUser']);




// php artisan make:model Category -mc
// php artisan make:migration  create_category_task_table



// // =====//// =====//// =====//// =====//// =====//
Route::post('/tasks/{TaskId}/categories', [TaskController::class, 'AddCategoriesToTask']);
// 
// // I wnat get all the categories by the task id 
// http://laravel-12-app.test/api/tasks/1/categories
Route::get('/tasks/{TaskId}/categories', [TaskController::class, 'GetTaskCategories']);

// // I wnat get  by the task id all the categories 
// http://laravel-12-app.test/api/categories/1/tasks
Route::get('/categories/{TaskId}/tasks', [TaskController::class, 'GetCategoriesTask']);
// // =====//// =====//// =====//// =====//// =====//







// php artisan make:seeder CategorySeeder 
// php artisan db:seed --class=CategorySeeder
// php artisan db:seed 
// php artisan mi:f
// php artisan mi:f --seed
// // =====//// =====//// =====//// =====//// =====//






















// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get(('/test'), function () {
//     return response()->json(['message' => 'This is a test endpoint']);
// });
// // http://laravel-12-app.test/api/test

// Route::get('/hello', function () {
//     return response()->json(['message' => 'Hello, World!']);
// });
// // http://laravel-12-app.test/api/hello

// //  git add .
// //  git commit -m "Add API routes for user and test endpoints"
// //  git push origin main
// //     git push --set-upstream origin TaskManager
// //  git commit --amend -m "Add API routes after user and test endpoints"




// // php artisan make:controller UserController

// // Route::get('/users', [UserController::class, 'index']);
// // http://laravel-12-app.test/api/users
// // Route::get('/users/{id}', [UserController::class, 'checkuser']);
// // http://laravel-12-app.test/azpi/users/1



// // Start for the Projects has name ===> Task Manager 
// // php artisan make:migration create_tasks_table
// // php artisan migrate
// // php artisan mi:f
// // php artisan make:controller Api/TaskController
// // php artisan make:model Task
// // php artisan make:model Task -m
// // php artisan make:model Task -m


// Route::get('/tasks', [TaskController::class, 'index']);
// // http://laravel-12-app.test/api/tasks/
// Route::get('/tasks/{id}', [TaskController::class, 'show']);
// // http://laravel-12-app.test/api/tasks/1
// Route::post('/tasks', [TaskController::class, 'store']);
// // http://laravel-12-app.test/api/tasks/
// Route::put('tasks/{id}', [TaskController::class, 'update']);
// // post  http://laravel-12-app.test/api/tasks/4   and add the method ==> _method | put in form-data
// // put  http://laravel-12-app.test/api/tasks/4   and add the data in parames
// Route::delete('tasks/{id}', [TaskController::class, 'destroy']);
// // http://laravel-12-app.test/api/tasks/4



// // // =====//// =====//// =====//// =====//// =====//

// Route::get('/profile', [ProfileController::class, 'index']);
// // http://laravel-12-app.test/api/profile
// Route::post('/profile', [ProfileController::class, 'store']);
// // http://laravel-12-app.test/api/profile
// Route::get('/profile/{id}', [ProfileController::class, 'show']);
// // http://laravel-12-app.test/api/profile/1


// Route::put('profile/{id}', [ProfileController::class, 'update']);
// // http://laravel-12-app.test/api/profile/2?user_id=1
// // http://laravel-12-app.test/api/profile/2?user_id=1&phone=11111111111

// // post  http://laravel-12-app.test/api/profile/2
// // put  http://laravel-12-app.test/api/profile/2
// // // =====//// =====//// =====//// =====//// =====//


// // // =====//// =====//// =====//// =====//// =====//
// Route::get('user/{id}/profile', [UserController::class, 'getprofile']);
// // http://laravel-12-app.test/api/user/1/profile

// // // =====//// =====//// =====//// =====//// =====//














// git commit -m "CRUD_In_the_Tasks_module"
// php artisan make:request UpdateRequest

// git commit -m "Add valadations for task module"

// php artisan make:model profile -mc
// php artisan m:fr
// php artisan make:controller Api/ProfileController

// php artisan make:request ProfileStoreRequest
// php artisan make:request ProfileUpdateRequest