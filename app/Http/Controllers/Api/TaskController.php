<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    function index()
    {
        $tasks = Task::all();

        return response()->json([
            "the Massge" => "Get  All Tasks Successfully",
            "Status Codes" => 200,
            "the DATA" => $tasks,
        ]);
    }

    function show($id)
    {
        $ShowTaskById = Task::where('id', $id)->get();

        return response()->json([
            "the Massge" => "Show the Tasks By ID Successfully",
            "Status Codes" => 200,
            "the DATA" => $ShowTaskById,
        ]);
    }

    function store(TaskStoreRequest $request)
    {
        // $valadationsStore = $request->validate(
        $valadationsStore = $request->validated();

        if (!$valadationsStore) {
            return response()->json([
                "the Massge" => "Not Fond Task",
                "Status Codes" => 404,
                "the DATA" => [],
            ]);
        }
        $StoreTask = Task::create($valadationsStore);

        return response()->json([
            "the Massge" => "Created Task Successfully",
            "Status Codes" => 201,
            "the DATA" => $StoreTask,
        ], 201);
    }


    function update(UpdateRequest $request, $id)
    {

        //  $valadationsupdate = $request->validated() ;
        $task = $request->validated();

        $task = Task::findOrFail($id);
        // $task = Task::find($id);
        $task->update($request->all());
        return response()->json([
            "the Massge" => "Updated Task Successfully",
            "Status Codes" => 201,
            "the DATA" => $task,
        ], 201);
    }


    function destroy($id)
    {
        // $task = Task::findOrFail($id);
        $task = Task::find($id);
        if (!$task) {
            return response()->json([
                "the Massge" => "Not Fond Task",
                "Status Codes" => 404,
                "the DATA" => [],
            ]);
        }
        $task->delete();
        return response()->json([
            "the Massge" => "Deleted Task Successfully",
            "Status Codes" => 204,
            "the DATA" => $task,
        ]);
    }

    function GetUserInfoByTaskBelongToUser($id)
    {
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
}

// // =====//// =====//// =====//// =====//// =====//


// function store(Request $request)
    // {
    //     $valadationsStore = $request->validate(
    //         [
    //             'title' => 'required|string|min:1|unique:tasks,title',
    //             'descriotion' => 'required|string|min:1|unique:tasks,descriotion',
    //             'priority'  => 'required|integer',
    //         ],
    //         // [
        //         //     // 'title' =>  "The title field is required.  ",
        //         //     'descriotion' =>  "The descriotion field is required.  ",
        //         //     'priority'  =>  "The priority field is required.  ",
        //         // ]
    //     );
    
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




        // // =====//// =====//// =====//// =====//// =====//

        
    // // / this is must edit all Fields 
    // function update(Request $request, $id)
    // {
    //     $task = Task::find($id);
    //     if ($task) {
    //         $task->update([
    //             "title" => $request->title,
    //             "descriotion" => $request->descriotion,
    //             "priority" => $request->priority,
    //         ]);
    //         return response()->json(
    //             [
    //                 'message' => 'task not found',
    //                 'Data' => $task,
    //             ],
    //             201
    //         );
    //     } else {
    //         return response()->json(['message' => 'task not found'], 404);
    //     }
    // }
