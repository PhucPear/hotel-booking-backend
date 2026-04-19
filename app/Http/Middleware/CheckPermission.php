<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $resource, $customPermission = null)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error_code' => 'AUTH_001'
            ], 401);
        }

        if ($customPermission) {
            if (!$user->hasPermission($customPermission)) {
                return response()->json([
                    'message' => 'Forbidden',
                    'error_code' => 'AUTH_003'
                ], 403);
            }

            return $next($request);
        }

        $methodMap = [
            'GET'    => 'view',
            'POST'   => 'create',
            'PUT'    => 'update',
            'PATCH'  => 'update',
            'DELETE' => 'delete',
        ];

        $action = $methodMap[$request->method()] ?? null;

        $permission = $action . '_' . $resource;

        if (!$user->hasPermission($permission)) {
            return response()->json([
                'message' => 'Forbidden',
                'error_code' => 'AUTH_003'
            ], 403);
        }

        return $next($request);
    }
}
