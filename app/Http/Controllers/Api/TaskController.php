<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    function index()
    {
        $tasks = Task::all();

        return response()->json([
            "the Massge" => "Send All Tasks Successfully",
            "Status Codes" => 200,
            "the DATA" => $tasks,
        ]);
    }

    function show($id)
    {
        $ShowTaskById = Task::where('id', $id)->get();

        return response()->json([
            "the Massge" => "Send Tasks By ID Successfully",
            "Status Codes" => 200,
            "the DATA" => $ShowTaskById,
        ]);
    }

    function store(Request $request)
    {
        $StoreTask = Task::create([
            'title' => $request->title,
            'descriotion' => $request->descriotion,
            'priority' => $request->priority,
        ]);

        return response()->json([
            "the Massge" => "Created Task Successfully",
            "Status Codes" => 201,
            "the DATA" => $StoreTask,
        ]);
    }


    function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        // $task = Task::find($id);
        $task->update($request->all());
        return response()->json($task);
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





}
