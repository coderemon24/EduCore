<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = Auth::guard('admin')->check();
        $isUser = Auth::guard('web')->check();

        if (! $isAdmin && ! $isUser) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('admin.login'));
        }

        // If authenticated as a school user, ensure their status is active and tenant is bound
        if ($isUser) {
            $user = Auth::guard('web')->user();

            if (! $user || ! $user->isActive()) {
                Auth::guard('web')->logout();
                session()->forget('current_school_id');

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Account is inactive.'], 403);
                }

                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Your account is currently inactive. Please contact your school administrator.',
                ]);
            }

            $schoolId = $user->school_id ?? session('current_school_id');
            if ($schoolId) {
                if (! session()->has('current_school_id')) {
                    session(['current_school_id' => $schoolId]);
                }
                app()->instance('current_school_id', $schoolId);
            }
        } elseif ($isAdmin) {
            // If super admin has switched context in session, bind it; otherwise leave null for global view
            if (session()->has('current_school_id')) {
                app()->instance('current_school_id', session('current_school_id'));
            }
        }

        return $next($request);
    }
}
