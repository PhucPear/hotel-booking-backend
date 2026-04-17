<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TraceIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu client gửi lên thì dùng lại (trace xuyên FE → BE)
        $traceId = $request->header('X-Trace-Id') ?? Str::uuid()->toString();

        // Gắn vào request
        $request->attributes->set('trace_id', $traceId);

        // Gắn vào header để middleware sau dùng
        $request->headers->set('X-Trace-Id', $traceId);

        return $next($request)->header('X-Trace-Id', $traceId);
    }
}
