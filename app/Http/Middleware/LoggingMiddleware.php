<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Lấy trace_id từ TraceIdMiddleware
        $traceId = $request->attributes->get('trace_id');

        // Gắn vào log context
        // Log::withContext([
        //     'trace_id' => $traceId
        // ]);

        // 2. Start time
        $startTime = microtime(true);

        Log::info("━━━━━━━━━━━━━ 🚀 START REQUEST [{$traceId}] 🚀 ━━━━━━━━━━━━━");

        // 3. Log request và payload
        Log::info('Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);

        Log::info('Payload', [
            //'headers' => $this->sanitize($request->headers->all(), ['authorization', 'cookie']),
            'body' => $this->sanitize($request->all(), ['password']),
        ]);


        // 4. Handle request
        $response = $next($request);

        // 5. End time
        $duration = round((microtime(true) - $startTime) * 1000, 2); // ms

        // 6. Get response content
        $responseData = $this->getResponseData($response);
        Log::info("───────────── ⚡ RESPONSE [{$traceId}] ⚡ ─────────────");

        // 7. Log response
        Log::info('Response', [
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration . 'ms',
            'response' => $this->sanitize($responseData, ['access_token']),
        ]);

        Log::info("━━━━━━━━━━━━━ 🏁 END REQUEST [{$traceId}] 🏁 ━━━━━━━━━━━━━\n");

        // 8. Add trace_id vào response
        return $response->header('X-Trace-Id', $traceId);
    }

    private function getResponseData($response)
    {
        try {
            // Không log stream (file download, export...)
            if ($response instanceof StreamedResponse) {
                return '[streamed response]';
            }

            // JsonResponse (chuẩn Laravel API)
            if ($response instanceof JsonResponse) {
                return $response->getData(true);
            }

            // Response thường
            if ($response instanceof Response) {
                $content = $response->getContent();

                return $this->decodeIfJson($content);
            }

            // fallback
            if (is_string($response)) {
                return $this->decodeIfJson($response);
            }

            return '[unknown response type]';
        } catch (\Throwable $e) {
            return '[unable to parse response]';
        }
    }

    private function decodeIfJson($content)
    {
        $decoded = json_decode($content, true);

        return json_last_error() === JSON_ERROR_NONE
            ? $decoded
            : $content;
    }

    private function sanitize(array $data, array $sensitiveKeys = [])
    {
        foreach ($data as $key => $value) {
            // check key nhạy cảm (không phân biệt hoa thường)
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $data[$key] = is_array($value) ? ['***hidden***'] : '***hidden***';
                continue;
            }

            // đệ quy nếu là mảng
            if (is_array($value)) {
                $data[$key] = $this->sanitize($value, $sensitiveKeys);
            }
        }

        return $data;
    }
}
