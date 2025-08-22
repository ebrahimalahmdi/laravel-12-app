
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Task Management API Routes
|--------------------------------------------------------------------------
|
| All routes are protected by Sanctum authentication middleware.
| Routes are grouped by logical functionality for better maintainability.
|
*/

Route::middleware('auth:sanctum')->controller(TaskController::class)->group(function () {
    // Basic CRUD operations
    Route::prefix('tasks')->group(function () {
        Route::get('/', 'index'); // List all tasks
        Route::post('/', 'store'); // Create new task
        Route::get('/{task}', 'show'); // Show specific task
        Route::put('/{task}', 'update'); // Update task
        Route::delete('/{task}', 'destroy'); // Delete task
    });

    // Task ordering and filtering
    Route::prefix('tasks')->group(function () {
        Route::get('/ordered', 'GetTasksByPriorty'); // Get ordered tasks by priority
        Route::get('/favorite', 'getFavoriteTasks'); // Get favorite tasks
        Route::get('/getalltasks', 'getalltasks')->middleware('CheckUser'); // Get all tasks (special permission)
    });

    // Task-user relationships
    Route::prefix('tasks/{task}')->group(function () {
        Route::get('/user', 'GetUserInfoByTaskBelongToUser'); // Get task owner info
        Route::post('/favorite', 'addToFavorite'); // Add to favorites
        Route::delete('/favorite', 'removeFromFavorite'); // Remove from favorites
    });

    // Task-category relationships
    Route::prefix('tasks/{task}')->group(function () {
        Route::get('/categories', 'GetTaskCategories'); // Get task's categories
        Route::post('/categories', 'AddCategoriesToTask'); // Add categories to task
    });

    // Category-based task queries
    Route::prefix('categories/{category}')->group(function () {
        Route::get('/tasks', 'GetTasksByCategory'); // Get tasks by category
    });
});

// the contrrolers

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Authenticate user
     */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get all users (admin only)
     */
    public function index()
    {
        // Add authorization check if needed
        $users = User::all();
        
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users
        ]);
    }

    /**
     * Get current authenticated user
     */
    public function GetUser()
    {
        $user = Auth::user();
        
        return response()->json([
            'message' => 'Current user retrieved',
            'data' => $user
        ]);
    }

    /**
     * Get current user with profile
     */
    public function GetUserWithProfile()
    {
        $user = Auth::user()->load('profile');
        
        return response()->json([
            'message' => 'User with profile retrieved',
            'data' => $user
        ]);
    }

    /**
     * Get specific user by ID
     */
    public function show(User $user)
    {
        return response()->json([
            'message' => 'User retrieved',
            'data' => $user
        ]);
    }

    /**
     * Get specific user's profile
     */
    public function getprofile(User $user)
    {
        return response()->json([
            'message' => 'User profile retrieved',
            'data' => $user->profile
        ]);
    }

    /**
     * Get all tasks for specific user
     */
    public function getAllTaskByUserId(User $user)
    {
        $tasks = $user->tasks;
        
        return response()->json([
            'message' => 'User tasks retrieved',
            'data' => $tasks
        ]);
    }
}

