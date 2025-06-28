<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    function index()
    {
        // return "Welcome From UserControllers Page";
        $users = [
            [
                'id' => '1',
                'Name' => 'Ebrahim',
            ],
            [
                'id' => '1',
                'Name' => 'Ebrahim',
            ],
            [
                'id' => '1',
                'Name' => 'Ebrahim',
            ],
        ];
        return response()->json([
            "the Message" => "Send DARA Successful ",
            "the Sattus Code" => 200,
            "the DATA" => $users,
        ]);
    }
    function checkuser($id)
    {
        if ($id > 10) {
            # code...
            return response()->json("the Id Biger then Ten    " . $id);
        } else {
            # code...
            return response()->json("the Id Smoller then Ten");
        }
        // return response()->json(true);
    }
}
