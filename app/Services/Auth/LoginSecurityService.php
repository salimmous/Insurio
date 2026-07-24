<?php

namespace App\Services\Auth;

use App\Mail\AccountLockedMail;
use App\Mail\LoginNotificationMail;
use App\Mail\SuspiciousLoginMail;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LoginSecurityService
{
    public function __construct(private readonly Request $request)
    {
    }

    /**
     * Called after successful login — records history, sends alerts, updates metadata.
     */
    public function onSuccessfulLogin(User $user): void
    {
        $fingerprint   = $this->getDeviceFingerprint();
        $isSuspicious  = $this->isSuspiciousLogin($user, $fingerprint);
        $isNewDevice   = !$user->isDeviceTrusted($fingerprint);

        // Record login history
        LoginHistory::create([
            'user_id'           => $user->id,
            'ip_address'        => $this->request->ip(),
            'user_agent'        => $this->request->userAgent(),
            'device_fingerprint' => $fingerprint,
            'status'            => 'success',
            'is_suspicious'     => $isSuspicious,
            'created_at'        => now(),
        ]);

        // Update last login metadata
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $this->request->ip(),
        ]);

        // Reset failed attempts on successful login
        $user->resetFailedAttempts();

        // Send login notification for new devices
        if ($isNewDevice) {
            try {
                Mail::to($user->email)->queue(new LoginNotificationMail($user, $this->request));
            } catch (\Throwable) {
                // Non-critical — never block login because of mail failure
            }
        }

        // Send suspicious login alert
        if ($isSuspicious) {
            try {
                Mail::to($user->email)->queue(new SuspiciousLoginMail($user, $this->request));
            } catch (\Throwable) {
            }
        }

        // Store OTP challenge in session if MFA is enabled
        if ($user->hasTwoFactorEnabled()) {
            session(['two_factor_verified' => false]);
        } else {
            session(['two_factor_verified' => true]);
        }

        \App\Services\Audit\SecurityAuditService::log(
            \App\Services\Audit\SecurityAuditService::EVENT_LOGIN_SUCCESS,
            'success',
            $user,
            "Connexion réussie"
        );
    }

    /**
     * Called after failed login attempt.
     */
    public function onFailedLogin(User $user): void
    {
        $user->incrementFailedAttempts();
        $user->refresh();

        // Record failed attempt
        LoginHistory::create([
            'user_id'           => $user->id,
            'ip_address'        => $this->request->ip(),
            'user_agent'        => $this->request->userAgent(),
            'device_fingerprint' => $this->getDeviceFingerprint(),
            'status'            => 'failed',
            'failure_reason'    => 'invalid_credentials',
            'created_at'        => now(),
        ]);

        \App\Services\Audit\SecurityAuditService::log(
            \App\Services\Audit\SecurityAuditService::EVENT_LOGIN_FAILED,
            'failed',
            $user,
            "Échec de connexion (Identifiants invalides)"
        );
    }

        // Send lockout notification if just locked
        if ($user->isLocked()) {
            try {
                Mail::to($user->email)->queue(new AccountLockedMail($user));
            } catch (\Throwable) {
            }

            // Update login history status to locked
            LoginHistory::create([
                'user_id'           => $user->id,
                'ip_address'        => $this->request->ip(),
                'user_agent'        => $this->request->userAgent(),
                'device_fingerprint' => $this->getDeviceFingerprint(),
                'status'            => 'locked',
                'failure_reason'    => 'account_locked',
                'created_at'        => now(),
            ]);
        }
    }

    /**
     * Generate a consistent device fingerprint from request data.
     */
    public function getDeviceFingerprint(): string
    {
        return hash('sha256', implode('|', [
            $this->request->userAgent() ?? '',
            $this->request->header('Accept-Language') ?? '',
        ]));
    }

    /**
     * Detect suspicious login (IP subnet change, etc.)
     */
    private function isSuspiciousLogin(User $user, string $fingerprint): bool
    {
        if (!$user->last_login_ip) {
            return false;
        }

        // Different subnet = suspicious
        $lastSubnet    = $this->getSubnet($user->last_login_ip);
        $currentSubnet = $this->getSubnet($this->request->ip());

        if ($lastSubnet !== $currentSubnet) {
            return true;
        }

        return false;
    }

    private function getSubnet(string $ip): string
    {
        // For IPv4: return first 3 octets (class C subnet)
        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            return implode('.', array_slice($parts, 0, 3));
        }
        // For IPv6: return first 4 groups
        return $ip;
    }
}
