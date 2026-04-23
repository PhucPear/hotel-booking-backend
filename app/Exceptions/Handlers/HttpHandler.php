<?php
namespace App\Exceptions\Handlers;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpHandler
{
    public static function handle(HttpExceptionInterface $e)
    {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage() ?: 'HTTP error',
            'error_code' => 'SYSTEM_001',
        ], $e->getStatusCode());
    }
}