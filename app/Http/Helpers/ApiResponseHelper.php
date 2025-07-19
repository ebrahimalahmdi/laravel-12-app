<?php


function apiResponse($status, $message, $data = null)
{
    $response = [
        'status' => $status,
        'message' => $message,
    ];
    if (!$data) {
        return response()->json($response);
    }
    $response['data'] = $data;
    return response()->json($response);
}




// =================

// static function apiResponse($code = 200, $msg = null, $data = null)
// {
//     $response = [
//         'status'    => $code,
//         'msg'       => $msg,
//         'data'      => $data,
//     ];

//     return response()->json($response, $code);
// }
