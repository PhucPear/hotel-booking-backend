<?php
namespace App\Exceptions\Handlers;

use App\Enums\ErrorCode;
use Illuminate\Validation\ValidationException;

class ValidationHandler
{
    public static function handle(ValidationException $e)
    {
        $error = ErrorCode::VALIDATION_ERROR;

        return response()->json([
            'status' => false,
            'message' =>  $error->message(),
            'errors' => $e->errors(),
            'error_code' =>  $error->value,
        ], $error->status());
    }
}