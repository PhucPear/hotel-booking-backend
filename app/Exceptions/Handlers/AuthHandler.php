<?php
namespace App\Exceptions\Handlers;

use Illuminate\Auth\AuthenticationException;

class AuthHandler
{
    public static function handle(AuthenticationException $e)
    {
        return response()->json([
            'status' => false,
            'message' => __('messages.auth.unauthorized'),
            'error_code' => 'AUTH_001'
        ], 401);
    }
}
