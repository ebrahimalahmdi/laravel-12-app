<?php

namespace App\Http\Controllers\Api;

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



    // // =====//// =====//// =====//// =====//// =====//
    use TaskOwnershipTrait; // Assuming you have a trait for task ownership checks


    // public function show($taskId)
    // {
    //     $task = $this->checkTaskOwnership($taskId);
    //     if ($task instanceof \Illuminate\Http\JsonResponse) {
    //         return $task; // رجعنا الرد في حال لم يكن صاحب التاسك
    //     }

    //     // إذا وصلنا هنا، المستخدم يملك التاسك
    //     return response()->json([
    //         'message' => 'Task found',
    //         'status' => 200,
    //         'data' => $task
    //     ]);
    // }

    // public function update(Request $request, $taskId)
    // {
    //     $task = $this->checkTaskOwnership($taskId);
    //     if ($task instanceof \Illuminate\Http\JsonResponse) {
    //         return $task;
    //     }

    //     $task->update($request->all());

    //     return response()->json([
    //         'message' => 'Task updated',
    //         'status' => 200,
    //         'data' => $task
    //     ]);
    // }

    //
    function index()
    {
        $tasks = Auth::user()->tasks;
        return response()->json([
            "the Massge" => "Get  All Tasks Successfully",
            "Status Codes" => 200,
            "the DATA" => $tasks,
        ]);
    }
    function getalltasks()
    {
        $tasks = Task::all();
        // $tasks = Auth::user()->tasks;

        return $tasks;

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

    // // store function with write the user id atomtucly 
    function store(TaskStoreRequest $request)
    {
        // // $GetUser_id = Auth::user();
        // $GetUser_id = Auth::user()->id;
        // return $GetUser_id;

        //  // Auth__user_____How_to_Access_the_Current_User

        $GetUser_id = Auth::user()->id;
        $valadationsStore = $request->validated();
        $valadationsStore['user_id'] = $GetUser_id;
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
        // ===// =====//// =====//// =====//// =====//// =====//
        $task = $this->getOwnedTaskOrFail($id);
        // ===// =====//// =====//// =====//// =====//// =====//
        $UpdatetasksRequest = Auth::user()->tasks;
        $task = $request->validated();
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return response()->json([
            "the Massge" => "Updated Task Successfully",
            "Status Codes" => 201,
            "the DATA" => $task,
        ], 201);
    }




    function destroy($id)
    {
        $User_id = Auth::user()->id;
        $Task_id = Task::find($id);
        if ($Task_id->user_id != $User_id) {
            return response()->json([
                "Massages" => "Unauthenticated !!!",
                "Status Codes" => 200,
                "the DATA" => null,
            ], 200);
        }
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


        // $User_id = Auth::user()->id;
        // $Task_id = Task::find($TaskId);
        // if ($Task_id->user_id != $User_id) {
        //     return response()->json([
        //         "Massages" => "Unauthenticated !!!",
        //         "Status Codes" => 200,
        //         "the DATA" => [],
        //     ], 200);
        // }

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


    // // this is focuse after morning

    // function GetTasksByCategory($Categorid)
    // {
    //     //  this is method use for get the Taskes By Categories ID 
    //     //http://laravel-12-app.test/api/tasks/categories/3/tasks

    //     // return $TaskId;
    //     // http://laravel-12-app.test/api/categories/35/tasks


    //     $User_id = Auth::user();
    //     if (!$User_id) {
    //         # code...
    //         return response()->json([
    //             "message" => " Unauthenticated !!!!",
    //         ]);
    //     }
    //     $Category = Category::find($Categorid);
    //     if (!$Category) {
    //         # code...
    //         return response()->json([
    //             "message" => "the Category Not Found",
    //         ]);
    //         $task = $User_id->Tasks()
    //             ->whereHas('categories', function ($query) use ($Categorid) {
    //                 $query->where('categories.id', $Categorid);
    //             })
    //             ->with('categories')
    //             ->get();
    //         return response()->json([
    //             "category" => $Category,
    //             "task" => $task,
    //         ]);
    //     }





    //     // // $Category_id = Category::find($Categorid)->tasks->user_id;
    //     // // return $Category_id;
    //     // if (!$Category_id != $User_id) {
    //     //     // if ($Category_id->user_id != $User_id) {
    //     //     # code...
    //     //     // return "false";
    //     //     return $Category_id;
    //     // }
    //     // return "true";

    //     // $GetTheTask = Category::findOrFail($Categorid)->tasks;
    //     // return $GetTheTask;

    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     // http://laravel-12-app.test/api/categories/35/tasks
    //     // ===// =====//// =====//// =====//// =====//// =====//




    //     // $GetTheTask = Category::findOrFail($TaskId)->tasks;
    //     // // $GetTheTask->categories()->attach($request->category_id);
    //     // return response()->json([
    //     //     "the Massge" => "Get  Attach tasks Successfully ",
    //     //     "Status Codes" => 200,
    //     //     "the DATA" => $GetTheTask,
    //     // ], 200);
    // }
}













// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//
// // =====//// =====//// =====//// =====//// =====//










// // =====//// =====//// =====//// =====//// =====//

    // function GetTaskCategories($TaskId)
    // {
    //     // ===// =====//// =====//// =====//// =====//// =====//
    //     $task = $this->getOwnedTaskOrFail($TaskId);
    //     // ===// =====//// =====//// =====//// =====//// =====//

    //     // return $id;
    //     $GetTheTask = Task::findOrFail($TaskId)->categories;
    //     // $GetTheTask->categories()->attach($request->category_id);
    //     return response()->json([
    //         "the Massge" => "Get  Attach Categories Successfully ",
    //         "Status Codes" => 200,
    //         "the DATA" => $GetTheTask,
    //     ], 200);
    // }


// // =====//// =====//// =====//// =====//// =====//
// function destroy($id)
//     {
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
//         if (!$task) {
//             return response()->json([
//                 "the Massge" => "Not Fond Task",
//                 "Status Codes" => 404,
//                 "the DATA" => [],
//             ]);
//         }
//         $task->delete();
//         return response()->json([
//             "the Massge" => "Deleted Task Successfully",
//             "Status Codes" => 204,
//             "the DATA" => $task,
//         ]);
//     }
// // =====//// =====//// =====//// =====//// =====//

 // function update(UpdateRequest $request, $id)
    // {
    //     $User_id = Auth::user()->id;                // this is for get user id
    //     $Task_id = Task::find($id);                 // this is for get task id 
    //     if ($Task_id->user_id != $User_id) {
    //         return response()->json([
    //             "Massages" => "Unauthenticated !!!",
    //             "Status Codes" => 200,
    //             "the DATA" => null,
    //         ], 200);
    //     } else {
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
    // }




// // =====//// =====//// =====//// =====//// =====//








// // =====//// =====//// =====//// =====//// =====//
//   function destroy($id)
//     {
//         // $task = Task::findOrFail($id);
//         $task = Task::find($id);
//         if (!$task) {
//             return response()->json([
//                 "the Massge" => "Not Fond Task",
//                 "Status Codes" => 404,
//                 "the DATA" => [],
//             ]);
//         }
//         $task->delete();
//         return response()->json([
//             "the Massge" => "Deleted Task Successfully",
//             "Status Codes" => 204,
//             "the DATA" => $task,
//         ]);
//     }

// // =====//// =====//// =====//// =====//// =====//


  // function update(UpdateRequest $request, $id)
    // {
    //     //  $valadationsupdate = $request->validated() ;
    //     // if ($User_id == $Task_id->user->id) { // this is no 
    //     // if (!$User_id == $Task_id->user->id) { // this is no 

    //     $User_id = Auth::user()->id; // this is for get user id
    //     $Task_id = Task::find($id); // this is for get task id 
    //     if ($Task_id->user_id != $User_id) {
    //         return response()->json([
    //             "Massages" => "Unauthenticated !!!",
    //             "Status Codes" => 200,
    //             "the DATA" => null,
    //         ], 201);
    //     } else {
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
    // }



// // =====//// =====//// =====//// =====//// =====//



//  function update(UpdateRequest $request, $id)
//     {

//         //  $valadationsupdate = $request->validated() ;
//         $task = $request->validated();
//         $task = Task::findOrFail($id);
//         // $task = Task::find($id);
//         $task->update($request->all());
//         return response()->json([
//             "the Massge" => "Updated Task Successfully",
//             "Status Codes" => 201,
//             "the DATA" => $task,
//         ], 201);
//     }









// // =====//// =====//// =====//// =====//// =====//

// // show all user with taskes
 // function index()
    // {
    //     $tasks = Task::all();

    //     return response()->json([
    //         "the Massge" => "Get  All Tasks Successfully",
    //         "Status Codes" => 200,
    //         "the DATA" => $tasks,
    //     ]);
    // }



// // =====//// =====//// =====//// =====//// =====//

// // update function with write the user id 
//   function update(UpdateRequest $request, $id)
//     {

//         //  $valadationsupdate = $request->validated() ;
//         $task = $request->validated();
//         $task = Task::findOrFail($id);
//         // $task = Task::find($id);
//         $task->update($request->all());
//         return response()->json([
//             "the Massge" => "Updated Task Successfully",
//             "Status Codes" => 201,
//             "the DATA" => $task,
//         ], 201);
//     }


// // =====//// =====//// =====//// =====//// =====//

    // // store function with write the user id 
    // function store(TaskStoreRequest $request)
    // {
    //     // $valadationsStore = $request->validate(
    //     $valadationsStore = $request->validated();

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
