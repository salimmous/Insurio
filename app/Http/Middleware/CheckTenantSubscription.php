<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if tenancy is initialized and we have a tenant
        if (function_exists('tenant') && tenant()) {
            $tenant = tenant();
            
            // Check if status is suspended or if the tenant is expired
            if ($tenant->status === 'suspended' || $tenant->isExpired()) {
                // If it is a JSON/AJAX request, return a JSON error
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Abonnement suspendu',
                        'message' => 'L\'accès à ce cabinet a été temporairement suspendu pour des raisons de facturation.'
                    ], 403);
                }

                // Otherwise return a beautiful blade view
                return response()->view('errors.suspended', compact('tenant'), 403);
            }

            // Enforce custom role & page restrictions configured by the agency admin
            if (auth()->check()) {
                $user = auth()->user();
                
                // 1. Role Restrictions (Exclude agency-admin themselves)
                if (!$user->hasRole('agency-admin')) {
                    $enabledRoles = json_decode(\App\Models\Setting::get('enabled_roles', '[]'), true) ?: ['responsable-succursale', 'agent-commercial', 'comptable', 'consultation'];
                    $userRole = $user->roles->first()?->name;
                    if ($userRole && !in_array($userRole, $enabledRoles)) {
                        auth()->logout();
                        abort(403, 'Ce rôle a été désactivé par l\'administrateur de l\'agence.');
                    }
                }

                // 2. Page / Module Restrictions
                $route = $request->route();
                if ($route) {
                    $routeName = $route->getName();
                    $enabledPages = json_decode(\App\Models\Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
                    
                    $routeMap = [
                        'dashboard' => 'dashboard',
                        'automobile.index' => 'automobile',
                        'automobile.create' => 'automobile',
                        'automobile.edit' => 'automobile',
                        'admin.succursales' => 'succursales',
                        'admin.employes' => 'employes',
                        'admin.commissions' => 'commissions',
                        'admin.charges' => 'charges',
                    ];

                    foreach ($routeMap as $pattern => $module) {
                        if ($routeName === $pattern && !in_array($module, $enabledPages)) {
                            abort(403, 'Ce module a été désactivé par l\'administrateur de l\'agence.');
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
