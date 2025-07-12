<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\profile;
use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\profileOwnershipTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        // ===// =====//// =====//// =====//// =====//// =====//
        $Profile = $this->getOwnedprofileOrFail($id);
        // ===// =====//// =====//// =====//// =====//// =====//

        // /// this is qourey without Rlationsship
        $ShowProfileById = profile::where('id', $id)->get();
        return response()->json([
            "the Massge" => "Show profiles By Profile ID Successfully",
            "Status Codes" => 200,
            "the DATA" => $ShowProfileById,
        ], 200);
    }

    // ----------------------------------------

    public function store(ProfileStoreRequest $request)
    {
        // الحصول على معرف المستخدم الحالي
        $userId = Auth::id();

        // التحقق من صحة البيانات القادمة من الطلب
        $validatedData = $request->validated();
        $validatedData['user_id'] = $userId;

        // التحقق من وجود صورة
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // توليد اسم عشوائي للملف
            $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();

            // تخزين الصورة في storage/app/public/images
            // (يجب أن يكون FILESYSTEM_DISK=public في .env)
            $image->storeAs('images', $fileName, 'public');

            // حفظ اسم الصورة في قاعدة البيانات
            $validatedData['image'] = $fileName;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Image is required.'
            ], 422);
        }
        // إنشاء الرابط العلني للصورة
        $publicPath = 'storage/images/' . $fileName;

        // إنشاء سجل جديد في قاعدة البيانات
        $profile = Profile::create($validatedData);


        return response()->json([
            'status' => "success",
            'message' => "Image Uploaded Successfully",
            'path' => $publicPath,
            'full_url' => asset($publicPath),
        ]);
    }
    // ----------------------------------------


    // // ----------------------------------------

    // public function store(ProfileStoreRequest $request)
    // {
    //     // الحصول على معرف المستخدم الحالي
    //     $userId = Auth::id();

    //     // التحقق من صحة البيانات القادمة من الطلب
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $userId;

    //     // التحقق من وجود صورة
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');

    //         // توليد اسم عشوائي للملف
    //         $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();

    //         // تخزين الصورة في storage/app/public/images
    //         // (يجب أن يكون FILESYSTEM_DISK=public في .env)
    //         $image->storeAs('images', $fileName, 'public');

    //         // حفظ اسم الصورة في قاعدة البيانات
    //         $validatedData['image'] = $fileName;
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Image is required.'
    //         ], 422);
    //     }

    //     // إنشاء سجل جديد في قاعدة البيانات
    //     $profile = Profile::create($validatedData);

    //     // إنشاء الرابط العلني للصورة
    //     $publicPath = 'storage/images/' . $fileName;

    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Image Uploaded Successfully",
    //         'path' => $publicPath,
    //         'full_url' => asset($publicPath),
    //     ]);
    // }
    // // ----------------------------------------


    function update(ProfileUpdateRequest $request, $id)
    {
        // ===// =====//// =====//// =====//// =====//// =====//
        $Profile = $this->getOwnedprofileOrFail($id);
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

        try {
            //code...
            // ===// =====//// =====//// =====//// =====//// =====//
            $Profile = $this->getOwnedprofileOrFail($id);
            // ===// =====//// =====//// =====//// =====//// =====//

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
        } catch (ModelNotFoundException $m) {
            return response()->json([
                "error" => "Task Not Found!! ",
                "details" => $m->getMessage(),
                "Status Codes" => 403,
            ], 403);
            //throw $th;
        } catch (Exception $th) {
            //throw $th;
            return response()->json([
                "error" => "something went wrong while deleteing the profile ",
                "details" => $th->getMessage(),
                "Status Codes" => 403,
            ], 403);
        }
    }
}



// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//







// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
 // // ----------------------------------------

    // public function store(ProfileStoreRequest $request)
    // {
    //     // الحصول على معرف المستخدم الحالي
    //     $userId = Auth::id();

    //     // التحقق من صحة البيانات القادمة من الطلب
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $userId;

    //     // التحقق من وجود صورة
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');

    //         // توليد اسم عشوائي للملف
    //         $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();

    //         // تخزين الصورة في storage/app/public/images
    //         // (يجب أن يكون FILESYSTEM_DISK=public في .env)
    //         $image->storeAs('images', $fileName, 'public');

    //         // حفظ اسم الصورة في قاعدة البيانات
    //         $validatedData['image'] = $fileName;
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Image is required.'
    //         ], 422);
    //     }

    //     // إنشاء سجل جديد في قاعدة البيانات
    //     $profile = Profile::create($validatedData);

    //     // إنشاء الرابط العلني للصورة
    //     $publicPath = 'storage/images/' . $fileName;

    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Image Uploaded Successfully",
    //         'path' => $publicPath,
    //         'full_url' => asset($publicPath),
    //     ]);
    // }
    // // ----------------------------------------

    // public function store(ProfileStoreRequest $request)
    // {
    //     $userId = Auth::id();
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $userId;

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');

    //         // إنشاء اسم عشوائي للملف
    //         $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();

    //         // تخزين الصورة في storage/app/public/images
    //         $image->storeAs('public/images', $fileName);

    //         // حفظ اسم الملف في قاعدة البيانات
    //         $validatedData['image'] = $fileName;
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Image is required.'
    //         ], 422);
    //     }

    //     // إنشاء سجل في قاعدة البيانات
    //     $profile = Profile::create($validatedData);

    //     // إنشاء المسار العلني (الظاهر للويب)
    //     $publicPath = 'storage/images/' . $fileName;

    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Image Uploaded Successfully",
    //         'path' => $publicPath,
    //         'full_url' => asset($publicPath),
    //     ]);
    // }
    // ----------------------------------------

    // public function store(ProfileStoreRequest $request)
    // {
    //     $userId = Auth::id();
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $userId;

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();
    //         $image->storeAs('public/images', $fileName);
    //         $validatedData['image'] = $fileName;
    //     } else {
    //         return response()->json(['error' => 'Image file is required.'], 422);
    //     }

    //     $profile = Profile::create($validatedData);

    //     $publicPath = 'storage/images/' . $fileName;

    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Image Uploaded Successfully",
    //         'path' => $publicPath,
    //         'full_url' => asset($publicPath),
    //     ]);
    // }

// // =====//// =====//// =====//// =====//// =====//






// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
    // ----------------------------------------
    // ----------------------------------------
    //     public function store(ProfileStoreRequest $request)
    // {
    //     $userId = Auth::id();
    //     $validatedData = $request->validated();
    //     $validatedData['user_id'] = $userId;

    //     // Ensure the image exists in the request
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();
    //         $image->storeAs('public/images', $fileName);
    //         $validatedData['image'] = $fileName;
    //     } else {
    //         // Return an error if no image is found
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Image is required.'
    //         ], 422);
    //     }

    //     // Optional: dd($validatedData); to confirm all fields
    //     $profile = Profile::create($validatedData);

    //     $publicPath = 'storage/images/' . $fileName;

    //     return response()->json([
    //         'status' => "success",
    //         'message' => "Image Uploaded Successfully",
    //         'path' => $publicPath,
    //         'full_url' => asset($publicPath),
    //     ]);
    // }
    // ----------------------------------------
    // function store(ProfileStoreRequest $request)
    // {

    //     $GetUser_id = Auth::user()->id;
    //     $profileStore = $request->validated();
    //     $profileStore['user_id'] = $GetUser_id;

    //     // $image = $request->File('image');
    //     // $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();
    //     // $path = $image->storeAs('public/images/' . $fileName);
    //     // $publicPath = 'storage/images/' . $fileName;

    //     $image = $request->File('image');
    //     $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();
    //     $path = $image->storeAs('public/images/' . $fileName);
    //     $publicPath = 'storage/images/' . $fileName;

    //     $profileStore['image'] = $fileName;

    //     $profile = profile::create($profileStore);
    //     return response()->json([
    //         'status' => "sucess",
    //         'message' => "Image Updoaded SuccessFully",
    //         'path' => $publicPath,
    //         'Full_url' => asset($publicPath),

    //     ]);
    // }
    // function store(ProfileStoreRequest $request)
    // {

    //     $GetUser_id = Auth::user()->id;
    //     $profileStore = $request->validated();
    //     $profileStore['user_id'] = $GetUser_id;

    //     $image = $request->File('image');
    //     $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();
    //     $path = $image->storeAs('public/images/' . $fileName);
    //     $publicPath = 'storage/images/' . $fileName;

    //     $profile = profile::create($profileStore);
    //     return response()->json([
    //         'status' => "sucess",
    //         'message' => "Image Updoaded SuccessFully",
    //         'path' => $publicPath,
    //         'Full_url' => asset($publicPath),

    //     ]);

    // }



    // function store(ProfileStoreRequest $request)
    // {

    //     $GetUser_id = Auth::user()->id;
    //     $profileStore = $request->validated();
    //     $profileStore['user_id'] = $GetUser_id;
    //     if ($request->hasFile('image')) {
    //         # code...
    //         //     // $request->file('image')->store($folder ,$disk);
    //         $path = $request->file('image')->store('Image_Folder', 'public');
    //         $profileStore['image'] = $path;
    //     }
    //     $profile = profile::create($profileStore);
    //     return response()->json([
    //         "the Massge" => "Created profile Successfully",
    //         "Status Codes" => 201,
    //         // "the DATA" => $profile,
    //         // "the DATA" => $profile,
    //     ], 201);
    // }
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//



// // =====//// =====//// =====//// =====//// =====//
    // function store(ProfileStoreRequest $request)
    // {

    //     $GetUser_id = Auth::user()->id;
    //     $profileStore = $request->validated();
    //     $profileStore['user_id'] = $GetUser_id;
    //     $profileStore = profile::create($profileStore);
    //     return response()->json([
    //         "the Massge" => "Created profile Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $profileStore,
    //     ], 201);
    // }

    // // =====//// =====//// =====//// =====//// =====//


    // git commit -m "Auth__user_____How_to_Access_the_Current_User After clean code "




//  function update(ProfileUpdateRequest $request, $id)
//     {
//         // ===// =====//// =====//// =====//// =====//// =====//

//         // $profile = profile::find($id);
//         // $user_id = Auth::id();

//         // if (!$profile || $profile->user_id != $user_id) {
//         //     abort(response()->json([
//         //         "message" => "Unauthenticated !!!",
//         //         "status" => 200,
//         //         "data" => []
//         //     ], 200));
//         // }
//         // ===// =====//// =====//// =====//// =====//// =====//

//         // ===// =====//// =====//// =====//// =====//// =====//
//         $task = $this->getOwnedprofileOrFail($id);
//         // ===// =====//// =====//// =====//// =====//// =====//

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