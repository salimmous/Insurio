<?php

namespace App\Services\Audit;

use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Support\Str;

class SecurityAuditService
{
    /**
     * Event Constant Mapping for Security Audit Center
     */
    public const EVENT_ACCOUNT_CREATED           = 'account.created';
    public const EVENT_ACCOUNT_ACTIVATED         = 'account.activated';
    public const EVENT_ACCOUNT_SUSPENDED         = 'account.suspended';
    public const EVENT_ACCOUNT_REACTIVATED       = 'account.reactivated';
    public const EVENT_ACCOUNT_DELETED           = 'account.deleted';

    public const EVENT_PASSWORD_CHANGED          = 'password.changed';
    public const EVENT_PASSWORD_RESET            = 'password.reset';
    public const EVENT_PASSWORD_TEMP_GENERATED   = 'password.temporary_generated';

    public const EVENT_ACTIVATION_LINK_GENERATED   = 'activation_link.generated';
    public const EVENT_ACTIVATION_LINK_REGENERATED = 'activation_link.regenerated';
    public const EVENT_ACTIVATION_LINK_USED        = 'activation_link.used';
    public const EVENT_ACTIVATION_LINK_EXPIRED     = 'activation_link.expired';
    public const EVENT_ACTIVATION_LINK_REVOKED     = 'activation_link.revoked';

    public const EVENT_LOGIN_FIRST               = 'login.first';
    public const EVENT_LOGIN_SUCCESS             = 'login.success';
    public const EVENT_LOGIN_FAILED              = 'login.failed';
    public const EVENT_LOGOUT                    = 'logout';

    public const EVENT_2FA_ENABLED               = '2fa.enabled';
    public const EVENT_2FA_DISABLED              = '2fa.disabled';
    public const EVENT_2FA_VERIFY_SUCCESS        = '2fa.verify_success';
    public const EVENT_2FA_VERIFY_FAILED         = '2fa.verify_failed';
    public const EVENT_RECOVERY_CODE_USED        = 'recovery_code.used';
    public const EVENT_RECOVERY_CODES_REGENERATED = 'recovery_codes.regenerated';
    public const EVENT_SESSION_REVOKED           = 'session.revoked';

    public const EVENT_ROLE_CHANGED              = 'role.changed';
    public const EVENT_PERMISSION_CHANGED        = 'permission.changed';

    /**
     * Permanent security audit log writer.
     */
    public static function log(
        string $eventType,
        string $status = 'success',
        ?User $user = null,
        ?string $notes = null,
        array $metadata = []
    ): SecurityAuditLog {
        $request = request();
        $user = $user ?: auth()->user();

        $userAgent = $request ? ($request->userAgent() ?: 'Unknown') : 'System Process';
        [$browser, $os, $device] = static::parseUserAgent($userAgent);

        $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
            ? tenant('name') 
            : (\App\Models\Setting::get('agency_name') ?: 'Insurio Enterprise SaaS');

        $branchName = $user?->branch?->nom ?? 'Siège Agence';
        $roleName = $user?->roles->first()?->name ?? 'Membre';

        return SecurityAuditLog::create([
            'uuid' => (string) Str::uuid(),
            'event_type' => $eventType,
            'status' => $status,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?: 'Utilisateur Système',
            'user_email' => $user?->email ?: 'system@insurio.com',
            'agency_name' => $agencyName,
            'branch_name' => $branchName,
            'role_name' => $roleName,
            'ip_address' => $request ? ($request->ip() ?: '127.0.0.1') : '127.0.0.1',
            'user_agent' => $userAgent,
            'browser' => $browser,
            'os' => $os,
            'device' => $device,
            'country' => 'Maroc',
            'city' => 'Casablanca',
            'notes' => $notes,
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * User Agent parser helper.
     */
    public static function parseUserAgent(string $ua): array
    {
        $browser = 'Navigateur Web';
        if (preg_match('/Chrome\/([0-9.]+)/i', $ua, $matches)) {
            $browser = 'Chrome ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Safari\/([0-9.]+)/i', $ua, $matches) && !preg_match('/Chrome/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/Firefox\/([0-9.]+)/i', $ua, $matches)) {
            $browser = 'Firefox ' . explode('.', $matches[1])[0];
        } elseif (preg_match('/Edg\/([0-9.]+)/i', $ua, $matches)) {
            $browser = 'Edge ' . explode('.', $matches[1])[0];
        }

        $os = 'OS';
        if (preg_match('/Macintosh|Mac OS X/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/Windows|Win64/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        }

        $device = 'Desktop';
        if (preg_match('/Mobile|iPhone|Android/i', $ua)) {
            $device = 'Mobile';
        } elseif (preg_match('/iPad|Tablet/i', $ua)) {
            $device = 'Tablette';
        }

        return [$browser, $os, $device];
    }
}
