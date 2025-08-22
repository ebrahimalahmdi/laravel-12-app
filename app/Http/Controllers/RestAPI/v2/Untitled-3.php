<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
        Route::get('/', 'index');
        Route::get('/profile', 'GetUserWithProfile');
        Route::get('/current', 'GetUser');
        Route::post('/logout', 'logout');

        // User-specific data routes
        Route::prefix('{user}')->group(function () {
            Route::get('/', 'show');
            Route::get('/profile', 'getprofile');
            Route::get('/tasks', 'getAllTaskByUserId');
        });
    });
});



//  the contrroles



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