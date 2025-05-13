<?php

namespace App\Http\Controllers;

abstract class Controller
{
    private function makeJson($status, $data, $msg)
    {
        return response()->json(['status' => $status, 'data' => $data, 'message' => $msg])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }    
}
