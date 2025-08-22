<?php

namespace App\Traits;

use App\Models\profile;
use Illuminate\Support\Facades\Auth;



trait profileOwnershipTrait
{
    // ===// =====//// =====//// =====//// =====//// =====//
    public function getOwnedprofileOrFail($taskId)
    {
        $task = profile::find($taskId);
        $user_id = Auth::id();

        if (!$task || $task->user_id != $user_id) {
            abort(response()->json([
                "message" => "Unauthenticated !!!",
                "status" => 200,
                "data" => []
            ], 200));
        }

        return response()->json([
            'message' => 'Task found',
            'status' => 200,
            'data' => $task
        ]);
        // return $task;
    }
    // ===// =====//// =====//// =====//// =====//// =====//
}





 // ===// =====//// =====//// =====//// =====//// =====//
    // public function validateTaskOwnership($taskId)
    // {
    //     $task = Task::find($taskId);
    //     $user_id = Auth::id();

    //     if (!$task || $task->user_id != $user_id) {
    //         return $this->unauthorizedResponse();
    //     }

    //     return $task;
    // }

    // private function unauthorizedResponse()
    // {
    //     return response()->json([
    //         "message" => "Unauthenticated !!!",
    //         "status" => 200,
    //         "data" => []
    //     ], 200);
    // }


    // ------write in controllers

      // ===// =====//// =====//// =====//// =====//// =====//
        // $task = $this->validateTaskOwnership($id);

        // if ($task instanceof \Illuminate\Http\JsonResponse) {
        //     return $task; // الرد جاهز إذا لم يكن المستخدم يملك التاسك
        // }

        // return response()->json([
        //     'message' => 'Task found',
        //     'status' => 200,
        //     'data' => $task
        // ]);

        // ===// =====//// =====//// =====//// =====//// =====//





// ===// =====//// =====//// =====//// =====//// =====//




// trait TaskOwnershipTrait
// {
//     public function checkTaskOwnership($taskId)
//     {
//         // $user_id = Auth::id();
//         // $task = Task::find($taskId);

//         $User_id = Auth::user()->id;
//         $Task_id = Task::find($taskId);
//         if ($Task_id->user_id != $User_id) {
//             return response()->json([
//                 "message" => "Unauthenticated !!!",
//                 "status" => 200,
//                 "data" => []
//             ], 200);
//         }

//         return $Task_id; // نرجع التاسك لو كل شيء تمام
//     }
// }














/////////////////////////
// use Illuminate\Support\Facades\Auth;
// use App\Models\Task;

// trait TaskOwnershipTrait
// {
//     public function checkTaskOwnership($taskId)
//     {
//         $user_id = Auth::id();
//         $task = Task::find($taskId);
//             if (!$task || $task->user_id != $user_id) {
//             return response()->json([
//                 "message" => "Unauthenticated !!!",
//                 "status" => 200,
//                 "data" => []
//             ], 200);
//         }

//         return $task; // نرجع التاسك لو كل شيء تمام
//     }
// }
