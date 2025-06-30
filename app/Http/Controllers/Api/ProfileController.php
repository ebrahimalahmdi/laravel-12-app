<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    function index()
    {
        $ShowAllProfile = profile::all();
        return response()->json([
            "the Massge" => "Show All profiles Successfully",
            "Status Codes" => 200,
            "the DATA" => $ShowAllProfile,
        ], 200);
    }
    function show($id)
    {
        // $ShowProfileById = profile::where('user_id', $id)->first();
        // $ShowProfileById = profile::where('user_id', $id)->firstOrFail();

        // /// this is qourey without Rlationsship
        $ShowProfileById = profile::where('id', $id)->get();
        return response()->json([
            "the Massge" => "Show profiles By Profile ID Successfully",
            "Status Codes" => 200,
            "the DATA" => $ShowProfileById,
        ], 200);
    }



    function store(ProfileStoreRequest $request)
    {

        $profileStore = profile::create($request->validated());
        return response()->json([
            "the Massge" => "profile Created Successfully",
            "Status Codes" => 201,
            "the DATA" => $profileStore,
        ], 201);
    }


    // // =====//// =====//// =====//// =====//// =====//
    // // =====//// =====//// =====//// =====//// =====//

    function update(ProfileUpdateRequest $request, $id)
    {

        $profileUpdate = $request->validated();
        $profileUpdate = profile::findOrFail($id);
        $profileUpdate->update($request->all());

        return response()->json([
            "the Massge" => "Updated Profiled Successfully",
            "Status Codes" => 201,
            "the DATA" => $profileUpdate,
        ], 201);
    }
}








// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//


    // function index()
    // {
    //     return response()->json([
    //         "the Massge" => "Hello from Index Page",
    //     ], 200);
    // }

// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//

     // function show($id)
    // {
    //     // $ShowAllProfile = profile::all();
    //     // $ById = profile::where('id', $id);
    //     // $ById = User::where('id', $id);
    //     // $ShowProfileById = profile::where('user_id', $ById);
    //     // $ShowProfileById = profile::where('user_id', $id);

    //     // $ShowProfileById = profile::where('user_id', $id)->first();
    //     // $ShowProfileById = profile::where('user_id', $id)->firstOrFail();


    //     // $ShowProfileById = profile::where('user_id', $id)->get();

    //     // $ShowProfileById = profile::where('user_id', $id)->users;

    //     // $ShowProfileById = profile::find($id)->users;
    //     $ShowProfileById = profile::find($id)->users;
    //     return response()->json([
    //         "the Massge" => "Show profiles By ID Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $ShowProfileById,
    //     ], 200);
    // }





// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//



    
    // function update(ProfileStoreRequest $request, $id)
    // {
    //     // $getTheId = profile::where('id', $id);
    //     $profileStore = profile::where('id', $id);
    //     $profileStore = $request->validated();
    //     $profileStore = profile::update();
    //     // $profileStore = profile::update($request->validated());
    //     return response()->json([
    //         "the Massge" => "profile UpDated Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $profileStore,
    //     ], 201);
    // }