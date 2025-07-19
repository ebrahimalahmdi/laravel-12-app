<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponseHelper;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Category;
use App\Models\Task;
use App\Traits\TaskOwnershipTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Better RESTful structure:


// Laravel API For - Professional TaskController Restructuring
class TTaskController_copy extends Controller
{
    use TaskOwnershipTrait;
    /**
     * Get all tasks for authenticated user
     */
    function index()
    {
        $tasks = Auth::user()->tasks;
        if (!$tasks) {
            return apiResponse(404, 'Not Found Any Tasks!!');
        }
        // return apiResponse(200, 'Get  All Tasks Successfully', $tasks); 
        return apiResponse(200, 'Tasks retrieved successfully', $tasks);
    }

    /**
     * Create a new task
     */

    // store function with write the user id atomtucly 
    function store(TaskStoreRequest $request)
    {
        //  // Auth__user_____How_to_Access_the_Current_User

        // $GetUser_id = Auth::user()->id;
        // $validated = $request->validated();
        // $validated['user_id'] = $GetUser_id;
        // if (!$validated) {
        //     return response()->json([
        //         "the Massge" => "Not Fond Task",
        //         "Status Codes" => 404,
        //         "the DATA" => [],
        //     ]);
        // }
        // $StoreTask = Task::create($validated);
        // return response()->json([
        //     "the Massge" => "Created Task Successfully",
        //     "Status Codes" => 201,
        //     "the DATA" => $StoreTask,
        // ], 201);

        // -=-=-=-=-=-=-=-=-=-=
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $task = Task::create($validated);

        if (!$task) {
            return apiResponse(404, 'Not Found Any Tasks!!');
        }
        return apiResponse(201, 'Task created successfully!', $task);
    }

    /**
     * Get a specific task
     */
    function show($id)
    {
        $Task = Task::find($id);
        $user_id = Auth::id();

        if (!$Task || $Task->user_id != $user_id) {
            return apiResponse(200, 'Unauthenticated For Any Task!!');
        }
        return apiResponse(200, 'Show the Tasks By id Successfully!', $Task);
    }
    /**
     * Update a task
     */

    // function update(UpdateRequest $request, $id)
    // {

    //     // $task = Task::findOrFail($id);
    //     $user_id = Auth::id();
    //     // $task = Task::findOrFail($id)->user;
    //     $task = Task::findOrFail($id);
    //     // if ($request->user()->id !== $task->user_id) {
    //     if ($user_id !== $task->user_id) {
    //         # code...
    //         return apiResponse(403, 'Unauthenticated For Any Task!!');
    //     }
    //     $task->update($request->validated());
    //     return apiResponse(200, 'Task updated successfully!', $task);
    // }

    function update(UpdateRequest $request, $id)
    {
        try {
            $user_id = Auth::user()->id;
            $task = Task::findOrFail($id);
            if ($task->user_id != $user_id)
                return response()->json(['message' => 'Unauthorized'], 403);

            $task->update($request->validated());
            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }

        // try {
        //     $user_id = Auth::user()->id;
        //     $task = Task::findOrFail($id);
        //     if ($task->user_id != $user_id)
        //         return response()->json(['message' => 'Unauthorized'], 403);

        //     $task->update($request->validated());
        //     return response()->json($task, 200);
        // } catch (ModelNotFoundException $e) {
        //     return response()->json(['error' => 'Task not found'], 404);
        // } catch (Exception $e) {
        //     return response()->json(['error' => 'Something went wrong'], 500);
        // }
    }

    /**
     * Delete a task
     */
    function destroy($id)
    {
        try {
            $User_id = Auth::user()->id;
            $Task_id = Task::find($id);
            if ($Task_id->user_id != $User_id) {
                return apiResponse(404, 'Unauthenticated!!');
            }
            // $task = Task::findOrFail($id);
            $task = Task::find($id);
            $task->delete();
            return apiResponse(201, 'Task deleted successfully!!!', $task);
        } catch (ModelNotFoundException $m) {
            return apiResponse(404, 'Task Not Found!!');
        } catch (Exception $m) {
            return apiResponse(404, 'something went wrong while deleteing the task!');
        }
    }
    /**
     * Get tasks ordered by priority
     */
    function GetTasksByPriorty()
    {
        // $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
        $tasks = Auth::user()->tasks()
            ->orderByRaw("FIELD(priority,'high','medium','low')")
            ->get();
        if (!$tasks) {
            return apiResponse(404, 'Not Found Any Task!!');
        }
        return apiResponse(200, 'Tasks ordered by priority!', $tasks);
    }

    /**
     * Get all tasks (admin only)
     */
    function getalltasks()
    {
        $tasks = Task::all();
        return apiResponse(200, 'Get all tasks (admin only)', $tasks);
    }




    // function getalltasks()
    // {
    //     $tasks = Task::all();
    //     // $tasks = Auth::user()->tasks;

    //     return $tasks;

    //     return response()->json([
    //         "the Massge" => "Get  All Tasks Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $tasks,
    //     ]);
    // }

    // function GetTasksByPriorty()
    // {
    //     // $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
    //     // $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'low','high','medium')")->get();
    //     $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
    //     return response()->json([
    //         "the Massge" => "Get  All Tasks Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $tasks,
    //     ]);
    // }


    /**
     * Get user's favorite tasks
     */


    function addToFavorite($taskId)
    {
        $tasks = Task::findOrFail($taskId);
        Auth::user()->favoriteTask()->syncWithoutDetaching($taskId);
        return response()->json([
            "the Massge" => "Taak Added To Favorite Successfully",
            "Status Codes" => 200,
        ]);
    }
    function removeFromFavorite($taskId)
    {
        $tasks = Task::findOrFail($taskId);
        Auth::user()->favoriteTask()->detach($taskId);
        return response()->json([
            "the Massge" => "Taak removed from Favorite Successfully",
            "Status Codes" => 200,
        ]);
    }
    function getFavoriteTasks()
    {
        $tasks = Auth::user()->favoriteTask()->get();
        return response()->json([
            "the Massge" => "Get All Taak from Favorite Successfully",
            "Status Codes" => 200,
            "The Data" => $tasks,
        ]);
    }



    // function show($id)
    // {
    //     $ShowTaskById = Task::where('id', $id)->get();

    //     return response()->json([
    //         "the Massge" => "Show the Tasks By ID Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $ShowTaskById,
    //     ]);
    // }

    // // // store function with write the user id atomtucly 
    // function store(TaskStoreRequest $request)
    // {
    //     // // $GetUser_id = Auth::user();
    //     // $GetUser_id = Auth::user()->id;
    //     // return $GetUser_id;

    //     //  // Auth__user_____How_to_Access_the_Current_User

    //     $GetUser_id = Auth::user()->id;
    //     $valadationsStore = $request->validated();
    //     $valadationsStore['user_id'] = $GetUser_id;
    //     if (!$valadationsStore) {
    //         return response()->json([
    //             "the Massge" => "Not Fond Task",
    //             "Status Codes" => 404,
    //             "the DATA" => [],
    //         ]);
    //     }
    //     $StoreTask = Task::create($valadationsStore);
    //     return response()->json([
    //         "the Massge" => "Created Task Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $StoreTask,
    //     ], 201);
    // }



    // function update(UpdateRequest $request, $id)
    // {
    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     $task = $this->getOwnedTaskOrFail($id);
    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     $UpdatetasksRequest = Auth::user()->tasks;
    //     $task = $request->validated();
    //     $task = Task::findOrFail($id);
    //     $task->update($request->all());
    //     return response()->json([
    //         "the Massge" => "Updated Task Successfully",
    //         "Status Codes" => 201,
    //         "the DATA" => $task,
    //     ], 201);
    // }




    // function destroy($id)
    // {
    //     try {
    //         //code...

    //         $User_id = Auth::user()->id;
    //         $Task_id = Task::find($id);
    //         if ($Task_id->user_id != $User_id) {
    //             return response()->json([
    //                 "Massages" => "Unauthenticated !!!",
    //                 "Status Codes" => 200,
    //                 "the DATA" => null,
    //             ], 200);
    //         }
    //         // $task = Task::findOrFail($id);
    //         $task = Task::find($id);
    //         $task->delete();
    //         return response()->json([
    //             "the Massge" => "Deleted Task Successfully",
    //             "Status Codes" => 204,
    //             "the DATA" => $task,
    //         ]);
    //     } catch (ModelNotFoundException $m) {
    //         return response()->json([
    //             "error" => "Task Not Found!! ",
    //             "details" => $m->getMessage(),
    //             "Status Codes" => 403,
    //         ], 403);
    //     } catch (Exception $m) {
    //         return response()->json([
    //             "error" => "something went wrong while deleteing the task ",
    //             "details" => $m->getMessage(),
    //             "Status Codes" => 403,
    //         ], 403);
    //     }
    // }

    function GetUserInfoByTaskBelongToUser($id)
    {
        // ===// =====//// =====//// =====//// =====//// =====//
        $task = $this->getOwnedTaskOrFail($id);
        // http://laravel-12-app.test/api/tasks/50/user
        // ===// =====//// =====//// =====//// =====//// =====//



        // // http://laravel-12-app.test/api/tasks/1/user
        // $GetUserInfoByTaskBelongToUser = Task::find($id)->user;
        // $GetUserInfoByTaskBelongToUser = Task::findOrFail($id)->user->email;
        $GetUserInfoByTaskBelongToUser = Task::findOrFail($id)->user->only('id', 'name', 'email');
        return response()->json([
            "the Massge" => "Get User Info By Task BelongTo User",
            "Status Codes" => 200,
            "the DATA" => $GetUserInfoByTaskBelongToUser,
        ], 200);
    }

    function AddCategoriesToTask(Request $request, $TaskId)
    {
        // http://laravel-12-app.test/api/tasks/1/categories
        // http://laravel-12-app.test/api/tasks/35/categories?category_id=1

        // ===// =====//// =====//// =====//// =====//// =====//
        $task = $this->getOwnedTaskOrFail($TaskId);
        // ===// =====//// =====//// =====//// =====//// =====//


        // return $id;
        $GetTheTask = Task::findOrFail($TaskId);
        $GetTheTask->categories()->attach($request->category_id);
        return response()->json([
            "the Massge" => "Created Attach Categories Successfully ",
            "Status Codes" => 201,
            "the DATA" => $GetTheTask,
        ], 200);
    }

    function GetTaskCategories($TaskId)
    {
        // ===// =====//// =====//// =====//// =====//// =====//
        $task = $this->getOwnedTaskOrFail($TaskId);
        // ===// =====//// =====//// =====//// =====//// =====//
        // http://laravel-12-app.test/api/tasks/35/categories
        // return $id;
        $GetTheTask = Task::findOrFail($TaskId)->categories;
        // $GetTheTask->categories()->attach($request->category_id);
        return response()->json([
            "the Massge" => "Get  Attach Categories Successfully ",
            "Status Codes" => 200,
            "the DATA" => $GetTheTask,
        ], 200);
    }
}























// this is before  - Professional Restructuring


// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//









// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Http\Helpers\ApiResponseHelper;
// use App\Http\Requests\TaskStoreRequest;
// use App\Http\Requests\UpdateRequest;
// use App\Models\Category;
// use App\Models\Task;
// use App\Traits\TaskOwnershipTrait;
// use Exception;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class TaskController extends Controller
// {



//     // // =====//// =====//// =====//// =====//// =====//
//     use TaskOwnershipTrait; // Assuming you have a trait for task ownership checks

//     //
//     function index()
//     {
//         $tasks = Auth::user()->tasks;
//         if (!$tasks) {
//             return apiResponse(404, 'Not Found Any Tasks!!');
//         }
//         return apiResponse(200, 'Get  All Tasks Successfully', $tasks);
//     }


//     function getalltasks()
//     {
//         $tasks = Task::all();
//         // $tasks = Auth::user()->tasks;

//         return $tasks;

//         return response()->json([
//             "the Massge" => "Get  All Tasks Successfully",
//             "Status Codes" => 200,
//             "the DATA" => $tasks,
//         ]);
//     }

//     function GetTasksByPriorty()
//     {
//         // $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
//         // $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'low','high','medium')")->get();
//         $tasks = Auth::user()->Tasks()->orderByRaw("FIELD(priority,'high','medium','low')")->get();
//         return response()->json([
//             "the Massge" => "Get  All Tasks Successfully",
//             "Status Codes" => 200,
//             "the DATA" => $tasks,
//         ]);
//     }


//     function addToFavorite($taskId)
//     {
//         $tasks = Task::findOrFail($taskId);
//         Auth::user()->favoriteTask()->syncWithoutDetaching($taskId);
//         return response()->json([
//             "the Massge" => "Taak Added To Favorite Successfully",
//             "Status Codes" => 200,
//         ]);
//     }
//     function removeFromFavorite($taskId)
//     {
//         $tasks = Task::findOrFail($taskId);
//         Auth::user()->favoriteTask()->detach($taskId);
//         return response()->json([
//             "the Massge" => "Taak removed from Favorite Successfully",
//             "Status Codes" => 200,
//         ]);
//     }
//     function getFavoriteTasks()
//     {
//         $tasks = Auth::user()->favoriteTask()->get();
//         return response()->json([
//             "the Massge" => "Get All Taak from Favorite Successfully",
//             "Status Codes" => 200,
//             "The Data" => $tasks,
//         ]);
//     }



//     function show($id)
//     {
//         $ShowTaskById = Task::where('id', $id)->get();

//         return response()->json([
//             "the Massge" => "Show the Tasks By ID Successfully",
//             "Status Codes" => 200,
//             "the DATA" => $ShowTaskById,
//         ]);
//     }

//     // // store function with write the user id atomtucly 
//     function store(TaskStoreRequest $request)
//     {
//         // // $GetUser_id = Auth::user();
//         // $GetUser_id = Auth::user()->id;
//         // return $GetUser_id;

//         //  // Auth__user_____How_to_Access_the_Current_User

//         $GetUser_id = Auth::user()->id;
//         $valadationsStore = $request->validated();
//         $valadationsStore['user_id'] = $GetUser_id;
//         if (!$valadationsStore) {
//             return response()->json([
//                 "the Massge" => "Not Fond Task",
//                 "Status Codes" => 404,
//                 "the DATA" => [],
//             ]);
//         }
//         $StoreTask = Task::create($valadationsStore);
//         return response()->json([
//             "the Massge" => "Created Task Successfully",
//             "Status Codes" => 201,
//             "the DATA" => $StoreTask,
//         ], 201);
//     }



//     function update(UpdateRequest $request, $id)
//     {
//         // ===// =====//// =====//// =====//// =====//// =====//
//         $task = $this->getOwnedTaskOrFail($id);
//         // ===// =====//// =====//// =====//// =====//// =====//
//         $UpdatetasksRequest = Auth::user()->tasks;
//         $task = $request->validated();
//         $task = Task::findOrFail($id);
//         $task->update($request->all());
//         return response()->json([
//             "the Massge" => "Updated Task Successfully",
//             "Status Codes" => 201,
//             "the DATA" => $task,
//         ], 201);
//     }




//     function destroy($id)
//     {
//         try {
//             //code...

//             $User_id = Auth::user()->id;
//             $Task_id = Task::find($id);
//             if ($Task_id->user_id != $User_id) {
//                 return response()->json([
//                     "Massages" => "Unauthenticated !!!",
//                     "Status Codes" => 200,
//                     "the DATA" => null,
//                 ], 200);
//             }
//             // $task = Task::findOrFail($id);
//             $task = Task::find($id);
//             $task->delete();
//             return response()->json([
//                 "the Massge" => "Deleted Task Successfully",
//                 "Status Codes" => 204,
//                 "the DATA" => $task,
//             ]);
//         } catch (ModelNotFoundException $m) {
//             return response()->json([
//                 "error" => "Task Not Found!! ",
//                 "details" => $m->getMessage(),
//                 "Status Codes" => 403,
//             ], 403);
//         } catch (Exception $m) {
//             return response()->json([
//                 "error" => "something went wrong while deleteing the task ",
//                 "details" => $m->getMessage(),
//                 "Status Codes" => 403,
//             ], 403);
//         }
//     }

//     function GetUserInfoByTaskBelongToUser($id)
//     {
//         // ===// =====//// =====//// =====//// =====//// =====//
//         $task = $this->getOwnedTaskOrFail($id);
//         // http://laravel-12-app.test/api/tasks/50/user
//         // ===// =====//// =====//// =====//// =====//// =====//



//         // // http://laravel-12-app.test/api/tasks/1/user
//         // $GetUserInfoByTaskBelongToUser = Task::find($id)->user;
//         // $GetUserInfoByTaskBelongToUser = Task::findOrFail($id)->user->email;
//         $GetUserInfoByTaskBelongToUser = Task::findOrFail($id)->user->only('id', 'name', 'email');
//         return response()->json([
//             "the Massge" => "Get User Info By Task BelongTo User",
//             "Status Codes" => 200,
//             "the DATA" => $GetUserInfoByTaskBelongToUser,
//         ], 200);
//     }

//     function AddCategoriesToTask(Request $request, $TaskId)
//     {
//         // http://laravel-12-app.test/api/tasks/1/categories
//         // http://laravel-12-app.test/api/tasks/35/categories?category_id=1

//         // ===// =====//// =====//// =====//// =====//// =====//
//         $task = $this->getOwnedTaskOrFail($TaskId);
//         // ===// =====//// =====//// =====//// =====//// =====//


//         // return $id;
//         $GetTheTask = Task::findOrFail($TaskId);
//         $GetTheTask->categories()->attach($request->category_id);
//         return response()->json([
//             "the Massge" => "Created Attach Categories Successfully ",
//             "Status Codes" => 201,
//             "the DATA" => $GetTheTask,
//         ], 200);
//     }

//     function GetTaskCategories($TaskId)
//     {
//         // ===// =====//// =====//// =====//// =====//// =====//
//         $task = $this->getOwnedTaskOrFail($TaskId);
//         // ===// =====//// =====//// =====//// =====//// =====//
//         // http://laravel-12-app.test/api/tasks/35/categories
//         // return $id;
//         $GetTheTask = Task::findOrFail($TaskId)->categories;
//         // $GetTheTask->categories()->attach($request->category_id);
//         return response()->json([
//             "the Massge" => "Get  Attach Categories Successfully ",
//             "Status Codes" => 200,
//             "the DATA" => $GetTheTask,
//         ], 200);
//     }
// }













// // // =====//// =====//// =====//// =====//// =====//
