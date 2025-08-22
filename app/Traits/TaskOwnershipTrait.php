<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;



trait TaskOwnershipTrait
{
    // ===// =====//// =====//// =====//// =====//// =====//
    public function getOwnedTaskOrFail($taskId)
    {
        $Task = Task::find($taskId);
        $user_id = Auth::id();

        if (!$Task || $Task->user_id != $user_id) {
            abort(response()->json([
                "message" => "Unauthenticated !!!",
                "status" => 200,
                "data" => []
            ], 200));
        }
        // return $task;
    }
    // ===// =====//// =====//// =====//// =====//// =====//
}
