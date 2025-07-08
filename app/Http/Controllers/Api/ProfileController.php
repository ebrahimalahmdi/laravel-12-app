<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\profile;
use App\Models\User;
use App\Traits\profileOwnershipTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Profiler\Profile as ProfilerProfile;

class ProfileController extends Controller
{
    //
    use profileOwnershipTrait; // Assuming you have a trait for task ownership checks

    function index()
    {
        $user_id = Auth::user()->id;
        $profile = profile::where('user_id', $user_id)->get();

        return response()->json([
            'message' => 'Task found',
            'status' => 200,
            'data' => $profile,
        ]);
    }


    function show($id)
    {

        $User_id = Auth::user()->id;
        $profileUpdate = profile::find($id);
        if ($profileUpdate->user_id != $User_id) {
            # code...
            return response()->json([
                "Massages" => "Unauthenticated !!!",
                "Status Codes" => 200,
                "the DATA" => [],
            ], 200);
        }

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
            "the Massge" => "Created profile Successfully",
            "Status Codes" => 201,
            "the DATA" => $profileStore,
        ], 201);
    }


    // // =====//// =====//// =====//// =====//// =====//
    // // =====//// =====//// =====//// =====//// =====//

    function update(ProfileUpdateRequest $request, $id)
    {
        // ===// =====//// =====//// =====//// =====//// =====//

        // $profile = profile::find($id);
        // $user_id = Auth::id();

        // if (!$profile || $profile->user_id != $user_id) {
        //     abort(response()->json([
        //         "message" => "Unauthenticated !!!",
        //         "status" => 200,
        //         "data" => []
        //     ], 200));
        // }
        // ===// =====//// =====//// =====//// =====//// =====//

        // ===// =====//// =====//// =====//// =====//// =====//
        $task = $this->getOwnedprofileOrFail($id);
        // ===// =====//// =====//// =====//// =====//// =====//

        $profileUpdate = $request->validated();
        $profileUpdate = profile::findOrFail($id);
        $profileUpdate->update($request->all());
        return response()->json([
            "the Massge" => "Updated Profiled Successfully",
            "Status Codes" => 201,
            "the DATA" => $profileUpdate,
        ], 201);
    }



    function destroy($id)
    {
        // $task = Task::findOrFail($id);
        $profile = profile::find($id);
        if (!$profile) {
            return response()->json([
                "the Massge" => "Not Fond profile",
                "Status Codes" => 404,
                "the DATA" => [],
            ]);
        }
        $profile->delete();
        return response()->json([
            "the Massge" => "Deleted profile Successfully",
            "Status Codes" => 204,
            "the DATA" => $profile,
        ]);
    }
}



// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//




//   function update(ProfileUpdateRequest $request, $id)
//     {
//         $User_id = Auth::user()->id;
//         $profileUpdate = profile::find()->users;
//         if ($profileUpdate->user_id != $User_id) {
//             # code...
//             return "fgsjkfdl";
//         }


//         $profileUpdate = $request->validated();
//         $profileUpdate = profile::findOrFail($id);
//         $profileUpdate->update($request->all());

//         return response()->json([
//             "the Massge" => "Updated Profiled Successfully",
//             "Status Codes" => 201,
//             "the DATA" => $profileUpdate,
//         ], 201);
//     }

// // =====//// =====//// =====//// =====//// =====//



//    function show($id)
//     {

//         $User_id = Auth::user()->id;
//         $profileUpdate = profile::find($id);
//         if ($profileUpdate->user_id != $User_id) {
//             # code...
//             return response()->json([
//                 "Massages" => "Unauthenticated !!!",
//                 "Status Codes" => 200,
//                 "the DATA" => [],
//             ], 200);
//         }
//         // $ShowProfileById = profile::where('user_id', $id)->first();
//         // $ShowProfileById = profile::where('user_id', $id)->firstOrFail();

//         // /// this is qourey without Rlationsship
//         $ShowProfileById = profile::where('id', $id)->get();
//         return response()->json([
//             "the Massge" => "Show profiles By Profile ID Successfully",
//             "Status Codes" => 200,
//             "the DATA" => $ShowProfileById,
//         ], 200);
//     }



// // =====//// =====//// =====//// =====//// =====//



    // function index()
    // {
    //     // $User_id = Auth::user()->users;
    //     // $User_id = Auth::user()->users;
    //     // $User_id = Auth::user()->profile->get();
    //     // $User_id = profile::all()->users;
    //     // return  $User_id;

    //     // $User_id = Auth::user()->profile;



    //     $profile = profile::get();
    //     // $user_id = Auth::id();

    //     // if (!$profile || $profile->user_id != $user_id) {
    //     //     abort(response()->json([
    //     //         "message" => "Unauthenticated !!!",
    //     //         "status" => 200,
    //     //         "data" => []
    //     //     ], 200));
    //     // }

    //     return response()->json([
    //         'message' => 'Task found',
    //         'status' => 200,
    //         'data' => $profile
    //     ]);


    //     // $user_id = Auth::id();
    //     // $profile_id = profile::all();

    //     // if ($profile_id->user_id != $user_id) {
    //     //     # code...
    //     //     return "fgfdgfd";
    //     // }
    //     // $ShowAllProfile = profile::all()->users;
    //     // return response()->json([
    //     //     "the Massge" => "Show All profiles Successfully",
    //     //     "Status Codes" => 200,
    //     //     "the DATA" => $user_id,
    //     // ], 200);
    // }


    
// // =====//// =====//// =====//// =====//// =====//







// // =====//// =====//// =====//// =====//// =====//
// function index()
// {
//     $ShowAllProfile = profile::all();
//     return response()->json([
//         "the Massge" => "Show All profiles Successfully",
//         "Status Codes" => 200,
//         "the DATA" => $ShowAllProfile,
//     ], 200);
// }
// function show($id)
// {
//     // $ShowProfileById = profile::where('user_id', $id)->first();
//     // $ShowProfileById = profile::where('user_id', $id)->firstOrFail();

//     // /// this is qourey without Rlationsship
//     $ShowProfileById = profile::where('id', $id)->get();
//     return response()->json([
//         "the Massge" => "Show profiles By Profile ID Successfully",
//         "Status Codes" => 200,
//         "the DATA" => $ShowProfileById,
//     ], 200);
// }



// function store(ProfileStoreRequest $request)
// {

//     $profileStore = profile::create($request->validated());
//     return response()->json([
//         "the Massge" => "Created profile Successfully",
//         "Status Codes" => 201,
//         "the DATA" => $profileStore,
//     ], 201);
// }


// // // =====//// =====//// =====//// =====//// =====//
// // // =====//// =====//// =====//// =====//// =====//

// function update(ProfileUpdateRequest $request, $id)
// {

//     $profileUpdate = $request->validated();
//     $profileUpdate = profile::findOrFail($id);
//     $profileUpdate->update($request->all());

//     return response()->json([
//         "the Massge" => "Updated Profiled Successfully",
//         "Status Codes" => 201,
//         "the DATA" => $profileUpdate,
//     ], 201);
// }

// function destroy($id)
// {
//     // $task = Task::findOrFail($id);
//     $profile = profile::find($id);
//     if (!$profile) {
//         return response()->json([
//             "the Massge" => "Not Fond profile",
//             "Status Codes" => 404,
//             "the DATA" => [],
//         ]);
//     }
//     $profile->delete();
//     return response()->json([
//         "the Massge" => "Deleted profile Successfully",
//         "Status Codes" => 204,
//         "the DATA" => $profile,
//     ]);
// }
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