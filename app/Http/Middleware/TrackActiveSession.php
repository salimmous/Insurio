<?php

namespace App\Http\Middleware;

use App\Models\UserActiveSession;
use App\Services\Auth\SessionManagementService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackActiveSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $sessionId = session()->getId();

            // Check if current session was revoked
            $activeSession = UserActiveSession::where('session_id', $sessionId)->first();

            if ($activeSession && $activeSession->status === 'revoked') {
                auth()->guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Votre session a été révoquée à distance par l\'administrateur ou depuis un autre appareil. Veuillez vous re-connecter.',
                ]);
            }

            // Register or refresh activity timestamp
            app(SessionManagementService::class)->registerOrUpdateSession($user, $sessionId);
        }

        return $next($request);
    }
}
