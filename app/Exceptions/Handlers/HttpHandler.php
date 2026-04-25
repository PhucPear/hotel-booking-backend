<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpHandler
{
    public static function handle(HttpExceptionInterface $e)
    {
        $status = $e->getStatusCode();

        $error = match ($status) {
            401 => ErrorCode::AUTH_UNAUTHORIZED,
            403 => ErrorCode::AUTH_FORBIDDEN,
            404 => ErrorCode::NOT_FOUND,
            405 => ErrorCode::METHOD_NOT_ALLOWED,
            default => ErrorCode::SYSTEM_ERROR,
        };

        return response()->json([
            'status' => false,
            'message' => $e->getMessage(),
            'error_code' => $error->value,
        ], $status);
    }
}