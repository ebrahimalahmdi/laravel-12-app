<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    function GetUser()
    {
        $GetUserByAuth = Auth::user()->id;
        $UserData = User::findOrFail($GetUserByAuth);
        return response()->json([
            'status' => "success",
            'message' => "Get User Successfully!",
            'Data ' => new UserResource($UserData),
        ]);
    }


    private function allPosts($query)
    {
        return $query->latest()->paginate(3);
    }

    function GetUserWithProfile()
    {
        // $GetUserByAuth = Auth::user()->id;
        // $UserData = User::with('profile')->findOrFail($GetUserByAuth);
        // return response()->json([
        //     'status' => "success",
        //     'message' => "Get User Successfully!",
        //     'Data ' => new UserResource($UserData),
        // ]);

        // // this is for collection

        // $GetUserByAuth = Auth::user()->id;
        // $UserData = User::with('profile')->findOrFail($GetUserByAuth);
        // return response()->json([
        //     'status' => "success",
        //     'message' => "Get User Successfully!",
        //     'Data ' => new UserResource($UserData),
        // ]);

        //   --------

        // $GetUserByAuth = Auth::user()->id;
        $UserData = User::with('profile')->get();
        return response()->json([
            'status' => "success",
            'message' => "Get User Successfully!",
            'Data ' =>  UserResource::collection($UserData),
        ]);
    }


    function index()
    {
        $GetAllUsers = User::all();

        return response()->json([
            "the Massge" => "Get All Users Successfully",
            "Status Codes" => 200,
            "the DATA" => $GetAllUsers,
        ], 200);
    }

    function register(Request $request)
    {
        $validtionsUser = $request->validate(
            [
                'name' => 'required|string|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|confirmed',
            ]
        );
        if (!$validtionsUser) {
            return response()->json([
                "the Massge" => "Register Validation Errors",
                "Status Codes" => 200,
                "the DATA" => $validtionsUser,
            ], 201);
        }

        $validtionsUser['password'] = Hash::make($request->password);
        $StoreUser = User::create($validtionsUser);

        // $ShowTheUser['token'] = $$StoreUser->createToken('AuthTpken')->plainTextToken;
        // $ShowTheUser['name'] = $$StoreUser->name;
        // $ShowTheUser['email'] = $$StoreUser->email;

        return response()->json([
            "the Massge" => "Registered User " . $StoreUser->name . " Registered  Successfully ",
            "Status Codes" => 201,
            "the DATA" => $StoreUser,
            // "the Token" => $ShowTheUser,
        ], 201);
    }
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


    //
    function getprofile($id)
    {
        // return (User::find(1)->profile);
        // User::find('id', $id);

        // /// this is qourey with Rlationsship
        // $getUserByprofile = User::find($id)->profile;
        // return ($getUserByprofile);

        $getUserByprofile = User::find($id)->profile;
        return ($getUserByprofile);
    }


    function getAllTaskByUserId($id)
    {
        // $GetTheTasksByIdUser = User::find($id)->Tasks;
        // $GetTheTasksByIdUser = User::query()->select('name', 'email')->get();
        // $GetTheTasksByIdUser = User::query()->select('name', 'email')->with('user_id')->get();
        // $GetTheTasksByIdUser = User::find($id)->Tasks('title')->get();
        $GetTheTasksByIdUser = User::find($id)->Tasks;

        return response()->json([
            "the Massge" => "Get The Tasks By User Id Successfully",
            "Status Codes" => 200,
            "the DATA" => $GetTheTasksByIdUser,
        ], 200);
        // // http://laravel-12-app.test/api/user/1
    }













    // =================================================
    // =================================================


    // =================================================
    // =================================================


    // =================================================
    // =================================================


    // =================================================
    // =================================================


    // =================================================
    // =================================================

    // function GetUserWithProfile()
    // {
    //     $GetUserByAuth = Auth::user()->id;
    //     $UserData = User::with('profile')->findOrFail($GetUserByAuth);
    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Get User Successfully!",
    //         'Data ' => new UserResource($UserData),
    //     ]);
    // }

    // =================================================
    // =================================================


    // function GetUser()
    // {
    //     $GetUserByAuth = Auth::user()->id;
    //     // $UserData = User::with('profile')->findOrFail($GetUserByAuth);
    //     $UserData = User::findOrFail($GetUserByAuth);
    //     // return new UserResource($UserData);
    //     return response()->json([
    //         'massage' => $UserData,
    //     ]);
    // }


    // function GetUser()
    // {
    //     // UserResource
    //     $GetUserByAuth = Auth::user()->id;
    //     // $UserData = User::findOrFail($GetUserByAuth)->first();
    //     $UserData = User::with('profile')->findOrFail($GetUserByAuth);
    //     return new UserResource($UserData);

    //     // return response()->json($UserData, 200);


    //     // if (!$user_id) {
    //     //     # code...
    //     //     return response()->json([
    //     //         "the Massge" => "erorr",
    //     //         "Status Codes" => 400,
    //     //         // "the DATA" => $GetAllUsers,
    //     //     ], 200);
    //     // }
    //     // return $UserData;
    // }
    // =================================================
    // =================================================
    // =================================================


    // function store(Request $request)
    // {
    //     $validtionsUser = $request->validate(
    //         [

    //             'name' => 'required|string',
    //             'email' => 'required|email|unique:users,email',
    //             // 'email_verified_at' => 'nullable',
    //             'password' => 'required|string',

    //         ]
    //     );
    //     if (!$validtionsUser) {
    //         return response()->json([
    //             "the Massge" => "errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrror",
    //             "Status Codes" => 200,
    //             "the DATA" => $validtionsUser,
    //         ], 201);
    //     }

    //     $StoreUser = User::create($validtionsUser);
    //     // $StoreUser = User::create($validtionsUser);

    //     // $StoreUser = User::create([
    //     //     'name' => $request->name,
    //     //     'email' => $request->email,
    //     //     'email_verified_at' => $request->email_verified_at,
    //     //     'password' => Hash::make($request->password),
    //     // ]);

    //     return response()->json([
    //         "the Massge" => "Get All Users Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $StoreUser,
    //     ], 201);
    // }
}















// // =====//// =====//// =====//// =====//// =====//


// // this is code from this projects
    // function login(Request $request)
    // {
    //     $validtionsUser = $request->validate(
    //         [
    //             'email' => 'required|email',
    //             'password' => 'required|string',
    //         ]
    //     );
    //     if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         // if (!Auth::attempt($request->only('email', 'password'))) {
    //         # code...
    //         return response()->json([
    //             "the Massge" => "Invalid email or password !!!",
    //             "Status Codes" => 400,
    //             "the DATA" => $validtionsUser,
    //         ], 401);
    //     }

    //     // $StoreUser = User::where('email', $request->email)->first();
    //     // $StoreUser = User::where('email', $request->email)->findOrFail();
    //     $StoreUser = User::where('email', $request->email)->first();
    //     $StoreTokenForUser = $StoreUser->createToken('Auth_Tpken')->plainTextToken;

    //     return response()->json([
    //         "the Massge" => "Login " . $StoreUser->name . "  Successfully ",
    //         "Status Codes" => 200,
    //         "the DATA" => $StoreUser,
    //         "the Token" => $StoreTokenForUser,
    //     ], 200);
    // }



// // =====//// =====//// =====//// =====//// =====//


// // this is code from this API_COURCES projects

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'email', 'max:255', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ], [], [
    //         'name' => 'Name',
    //         'email' => 'Email',
    //         'password' => 'Password',
    //     ]);

    //     if ($validator->fails()) {
    //         return ApiResponse::sendResponse(422, 'Register Validation Errors', $validator->messages()->all());
    //     }

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         //         $input['password'] = bcrypt($input['password']);
    //     ]);
    //     // $success['token'] =  $user->createToken('MyApp')->plainTextToken;
    //     $data['token'] = $user->createToken('APIcourse')->plainTextToken;
    //     $data['name'] = $user->name;
    //     $data['email'] = $user->email;

    //     return ApiResponse::sendResponse(201, 'User Account Created Successfully', $data);
    // }

// // =====//// =====//// =====//// =====//// =====//

// // this is code from this API_COURCES projects

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => ['required', 'email', 'max:255'],
    //         'password' => ['required'],
    //     ], [], [
    //         'email' => 'Email',
    //         'password' => 'Password',
    //     ]);

    //     if ($validator->fails()) {
    //         return ApiResponse::sendResponse(422, 'Login Validation Errors', $validator->errors());
    //     }

    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();
    //         $data['token'] = $user->createToken('MyAuthApp')->plainTextToken;
    //         $data['name'] = $user->name;
    //         $data['email'] = $user->email;
    //         // return ApiResponse::sendResponse(200, 'User Logged In Successfully', $data);
    //         return ApiResponse::sendResponse(200, 'User Login Successfully', $data);
    //     } else {
    //         return ApiResponse::sendResponse(401, 'These credentials doesn\'t exist', null);
    //     }
    // }



    // // =====//// =====//// =====//// =====//// =====//
