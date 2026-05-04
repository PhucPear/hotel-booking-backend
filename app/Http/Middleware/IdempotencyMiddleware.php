<?php

namespace App\Http\Middleware;

use App\Exceptions\IdempotencyProcessingException;
use App\Services\IdempotencyService;
use Closure;
use Illuminate\Http\Request;

class IdempotencyMiddleware
{
    protected $service;

    public function __construct(IdempotencyService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return $next($request);
        }

        $payload = $request->all();

        try {
            $existing = $this->service->check($key, $payload);

            if ($existing) {
                if ($existing['status'] === 'PROCESSING') {
                    throw new IdempotencyProcessingException();
                }

                if ($existing['status'] === 'SUCCESS') {
                    return response()->json(
                        $existing['response'],
                        $existing['status_code']
                    );
                }
            }

            $locked = $this->service->markProcessing($key, $payload);

            if (!$locked) {
                return response()->json([
                    'message' => 'Duplicate request'
                ], 429);
            }

            $response = $next($request);

            $this->service->storeSuccess($key, $payload, $response);

            return $response;
        } catch (\Exception $e) {
            if ($this->service->shouldClear($e)) {
                $this->service->clear($key);
            }

            throw $e;
        }
    }
}
