<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    // Session idle timeout in seconds (30 minutes for enterprise)
    private const TIMEOUT = 1800;

    // Warn at 5 minutes before expiry
    private const WARN_BEFORE = 300;

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $lastActivity = session('_last_activity');

        if ($lastActivity) {
            $idle = time() - $lastActivity;

            if ($idle > self::TIMEOUT) {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => __('Your session has expired due to inactivity. Please log in again.'),
                ]);
            }

            // Store remaining seconds in session for frontend warning
            $remaining = self::TIMEOUT - $idle;
            session(['_session_remaining' => $remaining]);
            session(['_session_warn' => $remaining <= self::WARN_BEFORE]);
        }

        session(['_last_activity' => time()]);

        return $next($request);
    }
}
