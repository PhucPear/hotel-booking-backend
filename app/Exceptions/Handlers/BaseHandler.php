<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;

class BaseHandler
{
    public static function response(ErrorCode $error)
    {
        return response()->json([
            'status' => false,
            'message' =>  $error->message(),
            'error_code' =>  $error->value,
        ], $error->status());
    }

    public static function handle($e)
    {
        return self::response(ErrorCode::SYSTEM_ERROR);
    }
}