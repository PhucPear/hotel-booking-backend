<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCode;
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


    protected function error(ErrorCode $error)
    {
        $traceId = request()->attributes->get('trace_id');

        return response()->json([
            'status' => false,
            'message' => $error->message(),
            'error_code' => $error->value,
            'trace_id' => $traceId,
        ], $error->status());
    }
}
