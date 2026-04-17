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
        ], $e->getStatusCode());
    }
}