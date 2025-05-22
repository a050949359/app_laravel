<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function makeJson($msg, $status = null, $data = null,  $code = 200)
    {
        $responseData = ['message' => $msg];

        // 如果 $status 不為 null，加入 'status' 鍵
        if ($status !== null) {
            $responseData['status'] = $status;
        }

        // 如果 $data 不為 null，加入 'data' 鍵
        if ($data !== null) {
            $responseData['data'] = $data;
        }

        // 返回 JSON 格式的回應，並設定狀態碼和編碼選項
        return response()->json($responseData, $code)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }    
}
