<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;
use App\Exceptions\BaseApiException;
use Illuminate\Auth\AuthenticationException;

class AuthHandler extends BaseApiException
{
    public static function handle(AuthenticationException $e)
    {
        $error = ErrorCode::AUTH_UNAUTHORIZED;

        return response()->json([
            'status' => false,
            'message' =>  $error->message(),
            'error_code' =>  $error->value,
        ], $error->status());
    }
}
