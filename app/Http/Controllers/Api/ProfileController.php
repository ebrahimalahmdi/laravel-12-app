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

    // function index()
    // {
    //     $user_id = Auth::user()->id;
    //     $profile = profile::where('user_id', $user_id)->get();

    //     return response()->json([
    //         'message' => 'Task found',
    //         'status' => 200,
    //         'data' => $profile,
    //     ]);
    // }
    function index()
    {
        $user_id = Auth::user()->id;
        $profiles = profile::where('user_id', $user_id)->get();

        // تعديل كل عنصر في النتائج لإضافة مسار الصورة
        $profiles->transform(function ($profile) {
            if ($profile->image) {
                $profile->path = 'storage/images/' . $profile->image;
                $profile->full_url = asset('storage/images/' . $profile->image);
            }
            return $profile;
        });

        return response()->json([
            'message' => 'Profiles found',
            'status' => 200,
            'data' => $profiles,
        ]);
    }



    // function show($id)
    // {
    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     $Profile = $this->getOwnedprofileOrFail($id);
    //     // ===// =====//// =====//// =====//// =====//// =====//

    //     // /// this is qourey without Rlationsship
    //     $ShowProfileById = profile::where('id', $id)->get();
    //     return response()->json([
    //         "the Massge" => "Show profiles By Profile ID Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $ShowProfileById,
    //     ], 200);
    // }
    function show($id)
    {
        // التحقق من الملكية
        $this->getOwnedprofileOrFail($id);

        // جلب الملف
        $profile = profile::findOrFail($id);

        if ($profile->image) {
            $profile->path = 'storage/images/' . $profile->image;
            $profile->full_url = asset('storage/images/' . $profile->image);
        }

        return response()->json([
            "message" => "Show profile by ID successfully",
            "status" => 200,
            "data" => $profile,
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


    // function update(ProfileUpdateRequest $request, $id)
    // {
    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     $Profile = $this->getOwnedprofileOrFail($id);
    //     // ===// =====//// =====//// =====//// =====//// =====//

    //     $profileUpdate = $request->validated();
    //     $profileUpdate = profile::findOrFail($id);
    //     $profileUpdate->update($request->all());
    //     return response()->json([
    //         "the Massge" => "Updated Profiled Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $profileUpdate,
    //     ], 201);
    // }


    public function update(ProfileUpdateRequest $request, $id)
    {
        $this->getOwnedprofileOrFail($id);

        $profile = Profile::findOrFail($id);
        $validatedData = $request->validated();

        $publicPath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::random(20) . '.' . $image->getClientOriginalExtension();

            // حذف الصورة القديمة إن وُجدت
            if ($profile->image && \Storage::disk('public')->exists('images/' . $profile->image)) {
                \Storage::disk('public')->delete('images/' . $profile->image);
            }

            $image->storeAs('images', $fileName, 'public');
            $validatedData['image'] = $fileName;
            $publicPath = 'storage/images/' . $fileName;
        }

        $profile->update($validatedData);

        // استخدام الصورة القديمة إن لم يتم رفع صورة جديدة
        $imageName = $profile->image;
        if (!$publicPath && $imageName) {
            $publicPath = 'storage/images/' . $imageName;
        }

        return response()->json([
            'status' => "success",
            'message' => $request->hasFile('image') ?
                "Image Updated Successfully" :
                "Profile updated successfully (no image uploaded).",
            'path' => $publicPath,
            'full_url' => $publicPath ? asset($publicPath) : null,
        ]);
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
