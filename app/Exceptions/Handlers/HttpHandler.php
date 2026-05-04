<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;

class HttpHandler extends BaseHandler
{
    public static function handle($e)
    {
        $status = $e->getStatusCode();

        $error = match ($status) {
            401 => ErrorCode::AUTH_UNAUTHORIZED,
            403 => ErrorCode::AUTH_FORBIDDEN,
            404 => ErrorCode::NOT_FOUND,
            405 => ErrorCode::METHOD_NOT_ALLOWED,
            default => ErrorCode::SYSTEM_ERROR,
        };

        return self::response($error);
    }
}