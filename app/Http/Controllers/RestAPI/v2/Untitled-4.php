<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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







// the controller


<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get all profiles (admin only)
     */
    public function index()
    {
        // Add authorization check if needed
        $profiles = Profile::all();
        
        return response()->json([
            'message' => 'Profiles retrieved successfully',
            'data' => $profiles
        ]);
    }

    /**
     * Create a new profile for current user
     */
    public function store(ProfileStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        $profile = Profile::create($validated);
        
        return response()->json([
            'message' => 'Profile created successfully',
            'data' => $profile
        ], 201);
    }

    /**
     * Get specific profile
     */
    public function show(Profile $profile)
    {
        $this->authorize('view', $profile);
        
        return response()->json([
            'message' => 'Profile retrieved',
            'data' => $profile
        ]);
    }

    /**
     * Update profile
     */
    public function update(ProfileUpdateRequest $request, Profile $profile)
    {
        $this->authorize('update', $profile);
        
        $profile->update($request->validated());
        
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }

    /**
     * Delete profile
     */
    public function destroy(Profile $profile)
    {
        $this->authorize('delete', $profile);
        
        $profile->delete();
        
        return response()->json([
            'message' => 'Profile deleted successfully'
        ], 204);
    }
}