<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Exceptions\Handlers\ValidationHandler;
use App\Exceptions\Handlers\HttpHandler;
use App\Exceptions\Handlers\AuthHandler;
use App\Exceptions\Handlers\BaseHandler;

class ApiExceptionHandler
{
    public static function handle(Throwable $e, Request $request)
    {
        // Trace id
        $traceId = $request->attributes->get('trace_id');
        
        // Custom exception error_code
        if ($e instanceof BaseApiException) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
                'trace_id' => $traceId,
            ], $e->getStatus());
        }

        // Validation
        if ($e instanceof ValidationException) {
            return ValidationHandler::handle($e);
        }

        // Auth Laravel
        if ($e instanceof AuthenticationException) {
            return AuthHandler::handle($e);
        }

        // HTTP (404, 403...)
        if ($e instanceof HttpExceptionInterface) {
            dd($e);
            return HttpHandler::handle($e);
        }

        // Fallback
        return BaseHandler::handle($e);
    }
}
