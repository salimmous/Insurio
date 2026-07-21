<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    private const EXCLUDED = [
        'force-password-change',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Skip excluded routes
        foreach (self::EXCLUDED as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        if ($user->isPasswordExpired(90)) {
            return redirect()->route('force-password-change')
                ->with('warning', __('Your password has expired. Please set a new password to continue.'));
        }

        return $next($request);
    }
}
