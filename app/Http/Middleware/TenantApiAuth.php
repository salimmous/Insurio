<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class TenantApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?? $request->header('X-Insurio-Token');
        
        $expectedToken = Setting::get('api_token');
        
        // If not set in database, seed a default fallback token for development/tests
        if (empty($expectedToken)) {
            $expectedToken = 'insurio_dev_secret_token';
            Setting::set('api_token', $expectedToken);
        }

        if (!$token || $token !== $expectedToken) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé. Jeton d\'API invalide ou manquant.',
            ], 401);
        }

        return $next($request);
    }
}
