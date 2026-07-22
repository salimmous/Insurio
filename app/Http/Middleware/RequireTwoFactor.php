<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TwoFactorSetting;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    private const EXCLUDED = [
        'two-factor-challenge',
        'logout',
        'force-password-change',
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

        // Check if 2FA is forced globally or by role, or confirmed by user
        $setting = TwoFactorSetting::first();
        $isEnforced = false;
        if ($setting) {
            if ($setting->force_2fa_all) {
                $isEnforced = true;
            } elseif ($setting->force_2fa_admins && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
                $isEnforced = true;
            } elseif ($setting->force_2fa_finance && ($user->hasRole('finance') || $user->hasRole('caisse'))) {
                $isEnforced = true;
            } elseif ($setting->force_2fa_managers && $user->hasRole('manager')) {
                $isEnforced = true;
            }
        }

        $requires2fa = $isEnforced || (bool)$user->two_factor_confirmed_at;

        // Mandatory check: no trusted device bypass, every session requires TOTP verification
        if ($requires2fa && !session('two_factor_verified')) {
            return redirect()->route('two-factor-challenge');
        }

        return $next($request);
    }
}
