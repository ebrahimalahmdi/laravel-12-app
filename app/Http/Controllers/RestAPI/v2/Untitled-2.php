<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\Category;
use App\Models\Task;
use App\Traits\TaskOwnershipTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    use TaskOwnershipTrait;

    /**
     * Get all tasks for authenticated user
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->get();

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks
        ], 200);
    }

    /**
     * Create a new task
     */
    public function store(TaskStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Get a specific task
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json([
            'message' => 'Task retrieved successfully',
            'data' => $task
        ], 200);
    }

    /**
     * Update a task
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task
        ], 200);
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ], 204);
    }

    /**
     * Get tasks ordered by priority
     */
    public function GetTasksByPriorty()
    {
        $tasks = Auth::user()->tasks()
            ->orderByRaw("FIELD(priority,'high','medium','low')")
            ->get();

        return response()->json([
            'message' => 'Tasks ordered by priority',
            'data' => $tasks
        ], 200);
    }

    /**
     * Get all tasks (admin only)
     */
    public function getalltasks()
    {
        $tasks = Task::all();

        return response()->json([
            'message' => 'All tasks retrieved',
            'data' => $tasks
        ], 200);
    }

    /**
     * Get user's favorite tasks
     */
    public function getFavoriteTasks()
    {
        $tasks = Auth::user()->favoriteTasks()->get();

        return response()->json([
            'message' => 'Favorite tasks retrieved',
            'data' => $tasks
        ], 200);
    }

    /**
     * Add task to favorites
     */
    public function addToFavorite(Task $task)
    {
        Auth::user()->favoriteTasks()->syncWithoutDetaching($task->id);

        return response()->json([
            'message' => 'Task added to favorites'
        ], 200);
    }

    /**
     * Remove task from favorites
     */
    public function removeFromFavorite(Task $task)
    {
        Auth::user()->favoriteTasks()->detach($task->id);

        return response()->json([
            'message' => 'Task removed from favorites'
        ], 200);
    }

    /**
     * Get task owner info
     */
    public function GetUserInfoByTaskBelongToUser(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json([
            'message' => 'Task owner info retrieved',
            'data' => $task->user->only('id', 'name', 'email')
        ], 200);
    }

    /**
     * Get task categories
     */
    public function GetTaskCategories(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json([
            'message' => 'Task categories retrieved',
            'data' => $task->categories
        ], 200);
    }

    /**
     * Add categories to task
     */
    public function AddCategoriesToTask(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->categories()->attach($request->category_id);

        return response()->json([
            'message' => 'Categories added to task',
            'data' => $task->fresh()->categories
        ], 201);
    }

    /**
     * Get tasks by category
     */
    public function GetTasksByCategory(Category $category)
    {
        $tasks = $category->tasks()
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'message' => 'Tasks by category retrieved',
            'data' => $tasks
        ], 200);
    }
}
