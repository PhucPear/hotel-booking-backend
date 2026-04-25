<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;
use Throwable;

class BaseHandler
{
    public static function handle(Throwable $e)
    {
        $error = ErrorCode::SYSTEM_ERROR;

        return response()->json([
            'status' => false,
            'message' =>  $error->message(),
            'error_code' =>  $error->value,
        ], $error->status());
    }
}