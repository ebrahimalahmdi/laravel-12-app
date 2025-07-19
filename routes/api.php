<?php

use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Laravel API Routes - Professional Restructuring
// git commit -m " Laravel API Routes - Professional Restructuring"


/*
|--------------------------------------------------------------------------
| User Management API Routes
|--------------------------------------------------------------------------
|
| Authentication routes are public while user data routes require Sanctum auth
| Routes are grouped by logical functionality for better maintainability
|
*/

Route::controller(UserController::class)->group(function () {
    // Public auth routes
    Route::prefix('user')->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    // Authenticated routes
    Route::middleware('auth:sanctum')->prefix('user')->group(function () {
        // User data routes
        Route::get('/', 'index'); // 
        Route::get('/profile', 'GetUserWithProfile'); //http://laravel-12-app.test/api/user/profile
        Route::get('/current', 'GetUser'); //http://laravel-12-app.test/api/user/current
        Route::post('/logout', 'logout');

        // User-specific data routes
        Route::prefix('{user}')->group(function () {
            Route::get('/', 'show'); //http://laravel-12-app.test/api/user/52
            Route::get('/profile', 'getprofile'); //http://laravel-12-app.test/api/user/52/profile
            Route::get('/tasks', 'getAllTaskByUserId'); //http://laravel-12-app.test/api/user/52/tasks
        });
    });
});

/*
|--------------------------------------------------------------------------
| Profile Management API Routes
|--------------------------------------------------------------------------
|
| All routes are protected by Sanctum authentication middleware.
| Standard CRUD operations for user profiles.
|
*/

Route::middleware('auth:sanctum')->controller(ProfileController::class)->group(function () {
    Route::prefix('profile')->group(function () {
        // Basic CRUD operations
        Route::get('/', 'index');         // List profiles
        Route::post('/', 'store');        // Create profile
        Route::get('/{profile}', 'show'); // Show specific profile
        Route::put('/{profile}', 'update'); // Update profile
        Route::delete('/{profile}', 'destroy'); // Delete profile
    });
});

// // =====//// =====//// =====//// =====//// =====//
// Route::middleware('auth:sanctum')->group(function () {
//     Route::prefix('/profile')->group(function () {
//         Route::controller(ProfileController::class)->group(function () {
//             Route::get('/', 'index');
//             Route::post('/', 'store');
//             Route::get('/{id}', 'show');
//             Route::put('{id}', 'update');
//             Route::delete('{id}', 'destroy');
//         });
//     });
// });
// // =====//// =====//// =====//// =====//// =====//



/*
|--------------------------------------------------------------------------
| Task Management API Routes
|--------------------------------------------------------------------------
|
| All routes are protected by Sanctum authentication middleware.
| Routes are grouped by logical functionality for better maintainability.
|
*/

Route::apiResource('tasks', TaskController::class)->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->controller(TaskController::class)->group(function () {
// Basic CRUD operations
// Route::apiResource('tasks', TaskController::class);

// Route::prefix('tasks')->group(function () {
//     Route::get('/', 'index'); // List all tasks
//     Route::post('/', 'store'); // Create new task
//     Route::get('/{task}', 'show'); // Show specific task
//     Route::put('/{task}', 'update'); // Update task
//     Route::delete('/{task}', 'destroy'); // Delete task
// });

// Task ordering and filtering
// Route::prefix('tasks')->group(function () {
//     Route::get('/ordered', 'GetTasksByPriorty'); // Get ordered tasks by priority
//     Route::get('/favorite', 'getFavoriteTasks'); // Get favorite tasks
//     Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser'); // Get all tasks (special permission)
// });

// Task-user relationships
// Route::prefix('tasks/{task}')->group(function () {
//     Route::get('/user', 'GetUserInfoByTaskBelongToUser'); // Get task owner info
//     Route::post('/favorite', 'addToFavorite'); // Add to favorites
//     Route::delete('/favorite', 'removeFromFavorite'); // Remove from favorites
// });

// Task-category relationships
// Route::prefix('tasks/{task}')->group(function () {
//     Route::get('/categories', 'GetTaskCategories'); // Get task's categories
//     Route::post('/categories', 'AddCategoriesToTask'); // Add categories to task
// });

// Category-based task queries
// Route::prefix('categories/{category}')->group(function () {
//     Route::get('/tasks', 'GetTasksByCategory'); // Get tasks by category
// });
// });




// Route::middleware('auth:sanctum')->controller(TaskController::class)->group(function () {
//     // Basic CRUD operations
//     Route::prefix('tasks')->group(function () {
//         Route::get('/', 'index'); // List all tasks
//         Route::post('/', 'store'); // Create new task
//         Route::get('/{task}', 'show'); // Show specific task
//         Route::put('/{task}', 'update'); // Update task
//         Route::delete('/{task}', 'destroy'); // Delete task
//     });

//     // Task ordering and filtering
//     Route::prefix('tasks')->group(function () {
//         Route::get('/ordered', 'GetTasksByPriorty'); // Get ordered tasks by priority
//         Route::get('/favorite', 'getFavoriteTasks'); // Get favorite tasks
//         Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser'); // Get all tasks (special permission)
//     });

//     // Task-user relationships
//     Route::prefix('tasks/{task}')->group(function () {
//         Route::get('/user', 'GetUserInfoByTaskBelongToUser'); // Get task owner info
//         Route::post('/favorite', 'addToFavorite'); // Add to favorites
//         Route::delete('/favorite', 'removeFromFavorite'); // Remove from favorites
//     });

//     // Task-category relationships
//     Route::prefix('tasks/{task}')->group(function () {
//         Route::get('/categories', 'GetTaskCategories'); // Get task's categories
//         Route::post('/categories', 'AddCategoriesToTask'); // Add categories to task
//     });

//     // Category-based task queries
//     Route::prefix('categories/{category}')->group(function () {
//         Route::get('/tasks', 'GetTasksByCategory'); // Get tasks by category
//     });
// });





// // =====//// =====//// =====//// =====//// =====//
// Route::controller(TaskController::class)->group(function () {
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::prefix('/tasks')->group(function () {

//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/', 'index');
//             Route::get('/{id}', 'show');
//             Route::post('', 'store');
//             Route::put('/{id}', 'update');
//             Route::delete('/{id}', 'destroy');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/ordered', 'GetTasksByPriorty');
//             // // // =====//// =====//// =====//// =====//// =====//
//             // Route::get('/', 'index');
//             // Route::get('/{id}', 'show');
//             // Route::post('', 'store');
//             // Route::put('/{id}', 'update');
//             // Route::delete('/{id}', 'destroy');
//             // // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/{id}/user', 'GetUserInfoByTaskBelongToUser');
//             Route::post('/{TaskId}/categories', 'AddCategoriesToTask'); // this is Post method 
//             Route::get('/{TaskId}/categories', 'GetTaskCategories'); // this is Get method 
//             // Route::get('/categories/{TaskId}/tasks', 'GetCategoriesTask');
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');


//             // // =====//// =====//// =====//// =====//// =====//
//             Route::post('/{id}/favorite', 'addToFavorite');
//             Route::delete('/{id}/favorite', 'removeFromFavorite'); ////
//             Route::get('/favorite', 'getFavoriteTasks'); ////tasks/favorite
//             // // =====//// =====//// =====//// =====//// =====//

//         });
//     });
// });
// // =====//// =====//// =====//// =====//// =====//









// // =====//// =====//// =====//// =====//// =====//
// Route::controller(UserController::class)->group(function () {

//     Route::prefix('/user')->group(function () {
//         Route::middleware('auth:sanctum')->group(function () {
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/', 'index');
//             Route::get('/getuser', 'GetUser'); ///http://laravel-12-app.test/api/user/getuser
//             Route::get('/getuserwithprofile', 'GetUserWithProfile'); ///http://laravel-12-app.test/api/user/getuserwithprofile/
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::post('/logout', 'logout');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/{id}', 'show');
//             // not used
//             Route::get('/{id}/profile', 'getprofile');
//             Route::get('/{id}/tasks', 'getAllTaskByUserId');
//         });
//         Route::post('/register', 'register');
//         Route::post('/login', 'login');
//         Route::post('/logout', 'logout');
//         // Route::post('/logout', 'logout')->middleware('auth:sanctum');;
//     });
// });
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




// // // =====//// =====//// =====//// =====//// =====//
// Route::middleware('auth:sanctum')->group(function () {
//     Route::prefix('/profile')->group(function () {
//         Route::controller(ProfileController::class)->group(function () {
//             Route::get('/', 'index');
//             Route::post('/', 'store');
//             Route::get('/{id}', 'show');
//             Route::put('{id}', 'update');
//             Route::delete('{id}', 'destroy');
//         });
//     });
// });
// // // =====//// =====//// =====//// =====//// =====//












// // // =====//// =====//// =====//// =====//// =====//
// // // =====//// =====//// =====//// =====//// =====//
// // // =====//// =====//// =====//// =====//// =====//
// // // =====//// =====//// =====//// =====//// =====//

// use App\Http\Controllers\Api\ProfileController;
// use App\Http\Controllers\Api\TaskController;
// use App\Http\Controllers\Api\UserController;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;




// // // =====//// =====//// =====//// =====//// =====//
// // // =====//// =====//// =====//// =====//// =====//
// Route::controller(UserController::class)->group(function () {

//     Route::prefix('/user')->group(function () {
//         Route::middleware('auth:sanctum')->group(function () {
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/', 'index');
//             Route::get('/getuser', 'GetUser'); ///http://laravel-12-app.test/api/user/getuser
//             Route::get('/getuserwithprofile', 'GetUserWithProfile'); ///http://laravel-12-app.test/api/user/getuserwithprofile/
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::post('/logout', 'logout');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/{id}', 'show');
//             // not used
//             Route::get('/{id}/profile', 'getprofile');
//             Route::get('/{id}/tasks', 'getAllTaskByUserId');
//         });
//         Route::post('/register', 'register');
//         Route::post('/login', 'login');
//         Route::post('/logout', 'logout');
//         // Route::post('/logout', 'logout')->middleware('auth:sanctum');;
//     });
// });
// // // =====//// =====//// =====//// =====//// =====//


// // // =====//// =====//// =====//// =====//// =====//
// Route::controller(TaskController::class)->group(function () {
//     Route::middleware('auth:sanctum')->group(function () {
//         Route::prefix('/tasks')->group(function () {

//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/', 'index');
//             Route::get('/{id}', 'show');
//             Route::post('', 'store');
//             Route::put('/{id}', 'update');
//             Route::delete('/{id}', 'destroy');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/ordered', 'GetTasksByPriorty');
//             // // // =====//// =====//// =====//// =====//// =====//
//             // Route::get('/', 'index');
//             // Route::get('/{id}', 'show');
//             // Route::post('', 'store');
//             // Route::put('/{id}', 'update');
//             // Route::delete('/{id}', 'destroy');
//             // // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/{id}/user', 'GetUserInfoByTaskBelongToUser');
//             Route::post('/{TaskId}/categories', 'AddCategoriesToTask'); // this is Post method 
//             Route::get('/{TaskId}/categories', 'GetTaskCategories'); // this is Get method 
//             // Route::get('/categories/{TaskId}/tasks', 'GetCategoriesTask');
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
//             // // =====//// =====//// =====//// =====//// =====//
//             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');


//             // // =====//// =====//// =====//// =====//// =====//
//             Route::post('/{id}/favorite', 'addToFavorite');
//             Route::delete('/{id}/favorite', 'removeFromFavorite'); ////
//             Route::get('/favorite', 'getFavoriteTasks'); ////tasks/favorite
//             // // =====//// =====//// =====//// =====//// =====//

//         });
//     });
// });
// // // =====//// =====//// =====//// =====//// =====//


// // // // =====//// =====//// =====//// =====//// =====//
// // Route::controller(TaskController::class)->group(function () {
// //     Route::middleware('auth:sanctum')->group(function () {
// //         Route::prefix('/tasks')->group(function () {

// //             // // =====//// =====//// =====//// =====//// =====//
// //             Route::post('/{id}/favorite', 'addToFavorite');
// //             Route::delete('/{id}/favorite', 'removeFromFavorite'); ////
// //             Route::get('/favorite', 'getFavoriteTasks'); ////tasks/favorite
// //             // // =====//// =====//// =====//// =====//// =====//

// //             Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser');
// //             // // =====//// =====//// =====//// =====//// =====//
// //             Route::get('/ordered', 'GetTasksByPriorty');
// //             // // =====//// =====//// =====//// =====//// =====//
// //             Route::get('/', 'index');
// //             Route::get('/{id}', 'show');
// //             Route::post('', 'store');
// //             Route::put('/{id}', 'update');
// //             Route::delete('/{id}', 'destroy');
// //             // // =====//// =====//// =====//// =====//// =====//
// //             Route::get('/{id}/user', 'GetUserInfoByTaskBelongToUser');
// //             Route::post('/{TaskId}/categories', 'AddCategoriesToTask'); // this is Post method 
// //             Route::get('/{TaskId}/categories', 'GetTaskCategories'); // this is Get method 
// //             // Route::get('/categories/{TaskId}/tasks', 'GetCategoriesTask');
// //             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
// //             // // =====//// =====//// =====//// =====//// =====//
// //             Route::get('/categories/{TaskId}/tasks', 'GetTasksByCategory');
// //         });
// //     });
// // });
// // // // =====//// =====//// =====//// =====//// =====//


// // // =====//// =====//// =====//// =====//// =====//
// Route::middleware('auth:sanctum')->group(function () {
//     Route::prefix('/profile')->group(function () {
//         Route::controller(ProfileController::class)->group(function () {
//             Route::get('/', 'index');
//             Route::post('/', 'store');
//             Route::get('/{id}', 'show');
//             Route::put('{id}', 'update');
//             Route::delete('{id}', 'destroy');
//         });
//     });
// });
// // // =====//// =====//// =====//// =====//// =====//
