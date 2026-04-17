<?php
namespace App\Exceptions\Handlers;

use Illuminate\Validation\ValidationException;

class ValidationHandler
{
    public static function handle(ValidationException $e)
    {
        return response()->json([
            'status' => false,
            'message' => __('messages.validation.error'),
            'errors' => $e->errors(),
            'error_code' => 'VALIDATION_001',
        ], 422);
    }
}