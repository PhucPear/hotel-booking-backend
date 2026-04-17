<?php
namespace App\Exceptions;

use Exception;
use App\Enums\ErrorCode;

class BaseApiException extends Exception
{
    protected ErrorCode $errorCode;

    public function __construct(ErrorCode $errorCode)
    {
        parent::__construct($errorCode->message(), $errorCode->status());
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode->value;
    }

    public function getStatus(): int
    {
        return $this->errorCode->status();
    }
}