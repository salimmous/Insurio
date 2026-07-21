<?php

namespace App\Services\Auth;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class TwoFactorService
{
    /**
     * Send email OTP to user and return the generated code (for testing).
     */
    public function sendCode(User $user): string
    {
        $code = $user->generateTwoFactorCode();

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($user, $code));
        } catch (\Throwable $e) {
            \Log::error('Failed to send 2FA code', ['user_id' => $user->id, 'error' => $e->getMessage()]);
        }

        return $code;
    }

    /**
     * Verify the submitted OTP code.
     */
    public function verify(User $user, string $code): bool
    {
        // Rate limit: max 5 attempts per 15 minutes
        $key     = 'mfa_attempts_' . $user->id;
        $attempts = Cache::get($key, 0);

        if ($attempts >= 5) {
            return false;
        }

        if (!$user->verifyTwoFactorCode($code)) {
            Cache::put($key, $attempts + 1, now()->addMinutes(15));
            return false;
        }

        // Clear code + reset attempts
        $user->clearTwoFactorCode();
        Cache::forget($key);

        return true;
    }

    /**
     * Enable 2FA for a user (set confirmed_at).
     */
    public function enable(User $user): void
    {
        $user->update(['two_factor_confirmed_at' => now()]);
    }

    /**
     * Disable 2FA for a user.
     */
    public function disable(User $user): void
    {
        $user->update([
            'two_factor_confirmed_at'  => null,
            'two_factor_secret'        => null,
            'two_factor_recovery_codes' => null,
            'two_factor_code'          => null,
            'two_factor_expires_at'    => null,
        ]);
    }

    /**
     * Trust the current device for 30 days.
     */
    public function trustDevice(User $user, string $fingerprint, string $deviceName, string $ip): void
    {
        // Remove existing entry if any
        $user->trustedDevices()->where('device_fingerprint', $fingerprint)->delete();

        $user->trustedDevices()->create([
            'device_fingerprint' => $fingerprint,
            'device_name'        => $deviceName,
            'ip_address'         => $ip,
            'confirmed_at'       => now(),
            'expires_at'         => now()->addDays(30),
        ]);
    }
}
