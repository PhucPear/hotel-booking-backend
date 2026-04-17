<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;

class AuthException extends BaseApiException
{
    public function __construct(ErrorCode $errorCode)
    {
        parent::__construct($errorCode);
    }
}
