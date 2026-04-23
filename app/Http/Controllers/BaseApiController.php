<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseApiController extends Controller
{
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        // Nếu là paginator
        if ($data instanceof ResourceCollection) {
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data->items(),
                'trace_id' => request()->attributes->get('trace_id'),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ]
            ], $code);
        }

        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'trace_id' => request()->attributes->get('trace_id'),
        ], $code);
    }


    protected function error($message = 'Error', $code = 400, $errorCode = 'SYSTEM_001')
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error_code' => $errorCode,
            'trace_id' => request()->attributes->get('trace_id'),
        ], $code);
    }
}
