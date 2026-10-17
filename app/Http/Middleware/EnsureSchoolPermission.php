<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // 1. Super Admin bypasses all school permissions
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // 2. School User check
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if ($user && $user->isActive() && $user->hasPermission($permission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized. Insufficient institutional permissions.',
                'required_permission' => $permission,
            ], 403);
        }

        abort(403, "Access Denied: You do not have the required '{$permission}' permission to perform this action.");
    }
}
