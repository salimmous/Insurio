<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'central' => \App\Http\Middleware\PreventAccessFromTenantDomains::class,
            'tenant.api' => \App\Http\Middleware\TenantApiAuth::class,
            'CheckTenantSubscription' => \App\Http\Middleware\CheckTenantSubscription::class,
            'SecurityHeaders' => \App\Http\Middleware\SecurityHeaders::class,
            'AccountLockout' => \App\Http\Middleware\AccountLockout::class,
            'SessionTimeout' => \App\Http\Middleware\SessionTimeout::class,
            'RequireTwoFactor' => \App\Http\Middleware\RequireTwoFactor::class,
            'RequirePasswordChange' => \App\Http\Middleware\RequirePasswordChange::class,
        ]);
        
        $middleware->redirectGuestsTo(fn ($request) => $request->is('super-admin*') ? route('platform.login') : route('login'));
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->command('contracts:check-expiry')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
