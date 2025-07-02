<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{


    function index()
    {
        $GetAllUsers = User::all();

        return response()->json([
            "the Massge" => "Get All Users Successfully",
            "Status Codes" => 200,
            "the DATA" => $GetAllUsers,
        ], 200);
    }


    function store(Request $request)
    {
        $validtionsUser = $request->validate(
            [

                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                // 'email_verified_at' => 'nullable',
                'password' => 'required|string',

            ]
        );
        if (!$validtionsUser) {
            return response()->json([
                "the Massge" => "errrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrror",
                "Status Codes" => 200,
                "the DATA" => $validtionsUser,
            ], 201);
        }

        $StoreUser = User::create($validtionsUser);
        // $StoreUser = User::create($validtionsUser);

        // $StoreUser = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'email_verified_at' => $request->email_verified_at,
        //     'password' => Hash::make($request->password),
        // ]);

        return response()->json([
            "the Massge" => "Get All Users Successfully",
            "Status Codes" => 201,
            "the DATA" => $StoreUser,
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
}
