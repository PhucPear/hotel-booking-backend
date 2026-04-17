<?php
namespace App\Exceptions\Handlers;

use Throwable;

class BaseHandler
{
    public static function handle(Throwable $e)
    {
        return response()->json([
            'status' => false,
            'message' => 'Server error',
        ], 500);
    }
}