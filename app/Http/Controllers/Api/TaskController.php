<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Auth::user()->tasks;
        if (!$tasks) {
            return apiResponse(404, 'Not Found Any Tasks!!');
        }
        return apiResponse(200, 'Get  All Tasks Successfully!', $tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        //
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $task = Task::create($validated);

        if (!$task) {
            return apiResponse(404, 'Not Found Any Tasks!!');
        }
        return apiResponse(201, 'Task created successfully!', $task);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Task $task)
    public function show($id)
    {
        //
        $Task = Task::find($id);
        $user_id = Auth::id();

        if (!$Task || $Task->user_id != $user_id) {
            return apiResponse(200, 'Unauthenticated For Any Task!!');
        }
        return apiResponse(200, 'Show the Tasks By id Successfully!', $Task);
    }

    /**
     * Update the specified resource in storage.
     */
    // dd($request->all());
    public function update(UpdateTaskRequest $request, $id)
    {
        try {

            $Task = Task::find($id);
            $user_id = Auth::id();

            if ($Task->user_id !== $user_id) {
                return apiResponse(200, 'Unauthenticated For Any Task!!');
            }
            $Task->update($request->validated());
            return apiResponse(200, 'Updated the Tasks By Successfully!', $Task);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Task not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $User_id = Auth::user()->id;
            $Task_id = Task::find($id);
            if ($Task_id->user_id != $User_id) {
                return apiResponse(404, 'Unauthenticated!!');
            }
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

    function getFavoriteTasks()
    {
        $tasks = Auth::user()->favoriteTask()->get();
        return response()->json([
            "the Massge" => "Get All Taak from Favorite Successfully",
            "Status Codes" => 200,
            "The Data" => $tasks,
        ]);
    }
    /**
     * Get all tasks (admin only)
     */
    function getalltasks()
    {
        $tasks = Task::all();
        return apiResponse(200, 'Get all tasks (admin only)', $tasks);
    }
}
