<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    private const EXCLUDED = [
        'two-factor-challenge',
        'logout',
        'force-password-change',
        'employee.activate',
        'activation.wizard',
        'activation.token',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        foreach (self::EXCLUDED as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // 1. FIRST LOGIN FLOW: If user has not completed onboarding wizard yet,
        // redirect to First Time Account Activation Wizard. Never ask for 2FA yet.
        if ($user->first_login || is_null($user->activated_at)) {
            return redirect()->route('activation.wizard');
        }

        // 2. AFTER ACTIVATION: 2FA is strictly mandatory for every single login.
        // No Remember Device, No Trust Browser, No Skip.
        if (!session('two_factor_verified')) {
            return redirect()->route('two-factor-challenge');
        }

        return $next($request);
    }
}
