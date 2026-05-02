<?php

namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ThrottleHandler
{
    public static function handle(ThrottleRequestsException $e)
    {
        $error = ErrorCode::TOO_MANY_REQUESTS;

        return response()->json([
            'status' => false,
            'message' => $error->message(),
            'error_code' => $error->value,
        ], $error->status());
    }
}
