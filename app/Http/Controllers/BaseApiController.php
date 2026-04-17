<?php

namespace App\Http\Controllers;

class BaseApiController extends Controller
{
    protected function success($data = null, $message = 'Success', $code = 200, $meta = [])
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'trace_id' => request()->attributes->get('trace_id'),
            'meta' => $meta
        ], $code);
    }


    protected function error($message = 'Error', $code = 400, $errorCode = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error_code' => 'SYSTEM_001',
            'trace_id' => request()->attributes->get('trace_id'),
        ], $code);
    }
}
