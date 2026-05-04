<?php

namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;

class IdempotencyConflictHandler extends BaseHandler
{
    public static function handle($e)
    {
        return self::response(ErrorCode::IDEMPOTENCY_CONFLICT);
    }
}
