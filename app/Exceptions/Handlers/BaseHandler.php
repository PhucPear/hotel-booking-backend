<?php
namespace App\Exceptions\Handlers;

use Throwable;

class BaseHandler
{
    public static function handle(Throwable $e)
    {
        return response()->json([
            'status' => false,
            'message' => __('messages.system.error'),
            'error_code' => 'SYSTEM_001',
        ], 500);
    }
}