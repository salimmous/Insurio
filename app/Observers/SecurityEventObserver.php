<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Services\Auth\LoginSecurityService;

class SecurityEventObserver
{
    /**
     * Handle successful login event.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;

        // Run the security service
        $service = app(LoginSecurityService::class);
        $service->onSuccessfulLogin($user);

        ActivityLog::writeLog('auth.login');
    }

    /**
     * Handle failed login event.
     */
    public function handleFailed(Failed $event): void
    {
        if (!$event->user) {
            return; // Unknown email — don't log user_id
        }

        $user    = $event->user;
        $service = app(LoginSecurityService::class);
        $service->onFailedLogin($user);
    }

    /**
     * Handle logout event.
     */
    public function handleLogout(Logout $event): void
    {
        $user = $event->user;
        if (!$user) return;

        ActivityLog::writeLog('auth.logout');
    }
}
