<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\TwoFactorAuditLog;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class TwoFactorService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new TOTP secret for user.
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Get QR Code SVG string for TOTP setup locally (offline).
     */
    public function getQrCodeSvg(User $user, string $secret): string
    {
        $appName = config('app.name', 'Insurio');
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            $appName,
            $user->email,
            $secret
        );

        return QrCode::size(220)->style('round')->margin(1)->generate($qrCodeUrl);
    }

    /**
     * Verify submitted TOTP code against secret.
     */
    public function verifyTotp(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Generate 10 single-use recovery codes.
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 10; $i++) {
            $codes[] = Str::random(10) . '-' . Str::random(10);
        }
        return $codes;
    }

    /**
     * Confirm and enable 2FA for user.
     */
    public function confirmTwoFactor(User $user, string $secret, array $recoveryCodes): void
    {
        $encryptedSecret = Crypt::encryptString($secret);
        $encryptedRecoveryCodes = Crypt::encryptString(json_encode($recoveryCodes));

        $user->update([
            'two_factor_secret' => $encryptedSecret,
            'two_factor_recovery_codes' => $encryptedRecoveryCodes,
            'two_factor_confirmed_at' => now(),
        ]);

        $this->logEvent($user, '2FA Enabled');
    }

    /**
     * Disable 2FA for user.
     */
    public function disableTwoFactor(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        $this->logEvent($user, '2FA Disabled');
    }

    /**
     * Decrypt secret key.
     */
    public function getDecryptedSecret(User $user): ?string
    {
        if (!$user->two_factor_secret) {
            return null;
        }

        try {
            return Crypt::decryptString($user->two_factor_secret);
        } catch (\Throwable $e) {
            return $user->two_factor_secret; // Fallback unencrypted if legacy
        }
    }

    /**
     * Decrypt recovery codes array.
     */
    public function getDecryptedRecoveryCodes(User $user): array
    {
        if (!$user->two_factor_recovery_codes) {
            return [];
        }

        try {
            $json = Crypt::decryptString($user->two_factor_recovery_codes);
            return json_decode($json, true) ?: [];
        } catch (\Throwable $e) {
            return json_decode($user->two_factor_recovery_codes, true) ?: [];
        }
    }

    /**
     * Verify and consume a recovery code (one-time use).
     */
    public function verifyAndConsumeRecoveryCode(User $user, string $submittedCode): bool
    {
        $codes = $this->getDecryptedRecoveryCodes($user);

        if (empty($codes)) {
            return false;
        }

        $index = array_search(trim($submittedCode), $codes);

        if ($index !== false) {
            unset($codes[$index]); // Invalidate code
            $updatedCodes = array_values($codes);

            $user->update([
                'two_factor_recovery_codes' => Crypt::encryptString(json_encode($updatedCodes)),
            ]);

            $this->logEvent($user, 'Recovery Code Used');
            return true;
        }

        return false;
    }

    /**
     * Trust device for 30 days.
     */
    public function trustDevice(User $user, string $fingerprint, string $deviceName, string $ip): void
    {
        $user->trustedDevices()->where('device_fingerprint', $fingerprint)->delete();

        $user->trustedDevices()->create([
            'device_fingerprint' => $fingerprint,
            'device_name' => $deviceName,
            'ip_address' => $ip,
            'confirmed_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $this->logEvent($user, 'Device Trusted');
    }

    /**
     * Check if device is trusted.
     */
    public function isDeviceTrusted(User $user, string $fingerprint): bool
    {
        return $user->trustedDevices()
            ->where('device_fingerprint', $fingerprint)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Log 2FA Security Audit Event.
     */
    public function logEvent(User $user, string $event): void
    {
        TwoFactorAuditLog::create([
            'user_id' => $user->id,
            'event' => $event,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device' => request()->header('User-Agent') ? Str::limit(request()->header('User-Agent'), 100) : 'Browser',
            'country' => 'Morocco',
            'created_at' => now(),
        ]);
    }
}
