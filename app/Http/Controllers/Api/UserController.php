<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
        $getUserByprofile = User::find($id)->profile;
        return ($getUserByprofile);
    }
}
