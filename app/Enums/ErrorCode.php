<?php
namespace App\Enums;

enum ErrorCode: string
{
    // AUTH
    case AUTH_INVALID_CREDENTIALS = 'AUTH_001';
    case AUTH_UNAUTHORIZED = 'AUTH_002';
    case AUTH_FORBIDDEN = 'AUTH_003';

    // SYSTEM
    case SYSTEM_ERROR = 'SYSTEM_001';

    // NOT FOUND
    case NOT_FOUND = 'NOT_FOUND';

    // VALIDATION
    case VALIDATION_ERROR = 'VALIDATION_001';

    case METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';

    public function message(): string
    {
        return match($this) {
            self::AUTH_INVALID_CREDENTIALS => __('messages.auth.invalid_credentials'),
            self::AUTH_UNAUTHORIZED => __('messages.auth.unauthorized'),
            self::AUTH_FORBIDDEN => __('messages.auth.forbidden'),
            self::SYSTEM_ERROR => __('messages.system.error'),
            self::NOT_FOUND => __('messages.not_found'),
            self::VALIDATION_ERROR => __('messages.validation.error'),
            self::METHOD_NOT_ALLOWED => __('messages.method_not_allowed'),
        };
    }

    public function status(): int
    {
        return match($this) {
            self::AUTH_INVALID_CREDENTIALS => 401,
            self::AUTH_UNAUTHORIZED => 401,
            self::AUTH_FORBIDDEN => 403,
            self::NOT_FOUND => 404,
            self::METHOD_NOT_ALLOWED => 405,
            self::VALIDATION_ERROR => 422,
            self::SYSTEM_ERROR => 500,
        };
    }
}