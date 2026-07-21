<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountLockout
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isLocked()) {
            auth()->guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => __('Your account has been temporarily locked due to multiple failed login attempts. Please try again in :minutes minutes.', [
                    'minutes' => (int) now()->diffInMinutes($user->locked_until, false),
                ]),
            ]);
        }

        return $next($request);
    }
}
