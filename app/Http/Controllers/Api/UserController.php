<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    // function GetUser()
    // {
    //     $GetUserByAuth = Auth::user()->id;
    //     $UserData = User::findOrFail($GetUserByAuth);
    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Get User Successfully!",
    //         'Data ' => new UserResource($UserData),
    //     ]);
    // }


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


    // private function allPosts($query)
    // {
    //     return $query->latest()->paginate(3);
    // }


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
     * Get all users (admin only)
     */

    function index()
    {
        $GetAllUsers = User::all();

        return response()->json([
            "the Massge" => "Get All Users Successfully",
            "Status Codes" => 200,
            "the DATA" => $GetAllUsers,
        ], 200);
    }

    /**
     * Register a new user
     */
    function register(Request $request)
    {
        $validtionsUser = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        if (!$validtionsUser) {
            return response()->json([
                "the Massge" => "Register Validation Errors",
                "Status Codes" => 200,
                "the DATA" => $validtionsUser,
            ], 201);
        }

        $validtionsUser['password'] = Hash::make($request->password);
        $StoreUser = User::create($validtionsUser);

        // =================
        // = for send the Email to new users
        // =================
        // Mail::to($StoreUser->email)('examples@gmail.com');
        // Mail::to($StoreUser->email)->send(new WelcomeMail());
        // Mail::to($StoreUser->email)->send(new WelcomeMail($StoreUser));
        // ====
        // Mail::to($StoreUser->email)->send(new WelcomeMail($StoreUser));
        return response()->json([
            "the Massge" => "Registered User " . $StoreUser->name . " Registered  Successfully ",
            "Status Codes" => 201,
            "the DATA" => $StoreUser,
        ], 201);
    }
    /**
     * Authenticate user
     */
    function login(Request $request)
    {
        $validtionsUser = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ]
        );
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                "the Massge" => "Invalid email or password !!!",
                "Status Codes" => 400,
                "the DATA" => $validtionsUser,
            ], 401);
        }
        $StoreUser = User::where('email', $request->email)->firstOrFail();
        $StoreTokenForUser = $StoreUser->createToken('Auth_Tpken')->plainTextToken;

        return response()->json([
            "the Massge" => "Login " . $StoreUser->name . "  Successfully ",
            "Status Codes" => 201,
            "the DATA" => $StoreUser,
            "the Token" => $StoreTokenForUser,
        ], 201);
    }

    /**
     * Logout user (revoke token)
     */
    function logout(Request $request)
    {
        $LogoutUserWithDeletedToken = $request->user()->currentAccessToken()->delete();
        return response()->json([
            "the Massge" => "Logout Successfully ",
            "Status Codes" => 201,
            "the DATA" => $LogoutUserWithDeletedToken,
        ], 201);
    }





    function show($id)
    {
        $GetlUsersById = User::findOrFail($id);

        return response()->json([
            "the Massge" => "Get User By ID Successfully",
            "Status Codes" => 200,
            "the DATA" => $GetlUsersById,
        ], 200);
    }



    /**
     * Get specific user's profile
     */

    // done
    function getprofile($id)
    {
        $getUserByprofile = User::find($id)->profile;
        return apiResponse(200, 'Get  profile User Data!', $getUserByprofile);
    }

    /**
     * Get all tasks for specific user
     */
    function getAllTaskByUserId($id)
    {
        $GetTheTasksByIdUser = User::find($id)->Tasks;
        return apiResponse(200, '  Get all tasks for specific user By User Id Successfully !', $GetTheTasksByIdUser);
    }
}


// =================================================
// =================================================

    // function GetUserWithProfile()
    // {
    //     // $GetUserByAuth = Auth::user()->id;
    //     // $UserData = User::with('profile')->findOrFail($GetUserByAuth);
    //     // return response()->json([
    //     //     'status' => "success",
    //     //     'message' => "Get User Successfully!",
    //     //     'Data ' => new UserResource($UserData),
    //     // ]);

    //     // // this is for collection

    //     // $GetUserByAuth = Auth::user()->id;
    //     // $UserData = User::with('profile')->findOrFail($GetUserByAuth);
    //     // return response()->json([
    //     //     'status' => "success",
    //     //     'message' => "Get User Successfully!",
    //     //     'Data ' => new UserResource($UserData),
    //     // ]);

    //     //   --------

    //     // $GetUserByAuth = Auth::user()->id;
    //     $UserData = User::with('profile')->get();
    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Get User Successfully!",
    //         'Data ' =>  UserResource::collection($UserData),
    //     ]);
    // }
