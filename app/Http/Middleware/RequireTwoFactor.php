<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    // Routes that don't require MFA check (challenge + logout)
    private const EXCLUDED = [
        'two-factor-challenge',
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

        // If MFA is enabled and not yet verified in this session
        if ($user->hasTwoFactorEnabled() && !session('two_factor_verified')) {
            return redirect()->route('two-factor-challenge');
        }

        return $next($request);
    }
}
