<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AutoAuthorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 1. Check auth
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
                'error_code' => 'AUTH_001'
            ], 401);
        }

        $route = $request->route();
        $actionMethod = $route->getActionMethod();

        // 2. CRUD mapping
        $crudMap = [
            'index'   => 'viewAny',
            'show'    => 'view',
            'store'   => 'create',
            'update'  => 'update',
            'destroy' => 'delete',
        ];

        // 3. Resolve ability
        if (isset($crudMap[$actionMethod])) {
            $ability = $crudMap[$actionMethod];
        } else {
            $ability = $this->resolveCustomAbility($request);
        }

        // nếu không detect được thì cho qua
        if (!$ability) {
            return $next($request);
        }

        // 4. Lấy model instance nếu có (route model binding)
        $model = null;

        foreach ($route->parameters() as $param) {
            if (is_object($param)) {
                $model = $param;
                break;
            }
        }

        // 5. Resolve model class
        $modelClass = $model
            ? get_class($model)
            : $this->resolveModelFromRoute($request);

        if (!$modelClass) {
            return $next($request);
        }

        // 6. Build permission name
        $resource = Str::plural(Str::snake(class_basename($modelClass)));
        $permission = "{$ability}_{$resource}";

        // 7. Check permission (RBAC)
        if (!$user->hasPermission($permission)) {
            return response()->json([
                'message' => 'Forbidden (permission)',
                'error_code' => 'AUTH_003'
            ], 403);
        }

        // 8. Check policy (ABAC)
        if ($model) {
            if ($user->cannot($ability, $model)) {
                return response()->json([
                    'message' => 'Forbidden (policy)',
                    'error_code' => 'AUTH_003'
                ], 403);
            }
        } else {
            if ($user->cannot($ability, $modelClass)) {
                return response()->json([
                    'message' => 'Forbidden (policy)',
                    'error_code' => 'AUTH_003'
                ], 403);
            }
        }

        return $next($request);
    }

    // resolve model
    protected function resolveModel($request)
    {
        $route = $request->route();

        foreach ($route->parameters() as $param) {
            if (is_object($param)) {
                return $param;
            }
        }

        return null;
    }

    // Detect custom action: approve, cancel...
    protected function resolveCustomAbility(Request $request): ?string
    {
        $segments = $request->segments();

        $last = end($segments);

        // nếu là số (id) thì bỏ
        if (is_numeric($last)) {
            return null;
        }

        return Str::snake($last); // approve, cancel
    }

    // Resolve model từ URL (/api/bookings → Booking)
    protected function resolveModelFromRoute(Request $request): ?string
    {
        $segments = $request->segments();

        // ví dụ: /api/bookings
        $resource = $segments[1] ?? null;

        if (!$resource) return null;

        $model = ucfirst(Str::singular($resource));

        $modelClass = "App\\Models\\{$model}";

        return class_exists($modelClass) ? $modelClass : null;
    }
}
