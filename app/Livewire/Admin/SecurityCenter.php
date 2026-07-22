<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\Auth\TwoFactorService;
use App\Models\User;
use App\Models\TwoFactorSetting;
use App\Models\TwoFactorAuditLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SecurityCenter extends Component
{
    public string $activeTab = 'security'; // security, admin_2fa, audit_logs, sessions

    // 2FA Setup State
    public bool $showSetupModal = false;
    public string $setupSecret = '';
    public string $qrCodeSvg = '';
    public string $verificationCode = '';
    public int $setupStep = 1; // 1: QR Scan & Verify, 2: Display Recovery Codes
    public array $recoveryCodes = [];

    // Trusted Devices & Password
    public bool $trustCurrentDevice = true;

    // Super Admin 2FA Force Policies
    public bool $force_2fa_all = false;
    public bool $force_2fa_admins = false;
    public bool $force_2fa_finance = false;
    public bool $force_2fa_managers = false;

    public function mount()
    {
        $setting = TwoFactorSetting::first();
        if ($setting) {
            $this->force_2fa_all = (bool) $setting->force_2fa_all;
            $this->force_2fa_admins = (bool) $setting->force_2fa_admins;
            $this->force_2fa_finance = (bool) $setting->force_2fa_finance;
            $this->force_2fa_managers = (bool) $setting->force_2fa_managers;
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function start2faSetup()
    {
        $service = app(TwoFactorService::class);
        $this->setupSecret = $service->generateSecretKey();
        $this->qrCodeSvg = $service->getQrCodeSvg(auth()->user(), $this->setupSecret);
        $this->verificationCode = '';
        $this->setupStep = 1;
        $this->showSetupModal = true;
    }

    public function confirm2faSetup()
    {
        $this->validate([
            'verificationCode' => 'required|string|size:6',
        ]);

        $service = app(TwoFactorService::class);

        if (!$service->verifyTotp($this->setupSecret, $this->verificationCode)) {
            $this->addError('verificationCode', 'Code TOTP invalide. Veuillez vérifier votre application authentificateur.');
            $service->logEvent(auth()->user(), 'Failed Verification');
            return;
        }

        $this->recoveryCodes = $service->generateRecoveryCodes();
        $service->confirmTwoFactor(auth()->user(), $this->setupSecret, $this->recoveryCodes);

        if ($this->trustCurrentDevice) {
            $fingerprint = md5(request()->ip() . request()->userAgent());
            $service->trustDevice(auth()->user(), $fingerprint, 'Navigateur Actuel', request()->ip());
        }

        $service->logEvent(auth()->user(), 'Successful Verification');
        $this->setupStep = 2;
    }

    public function closeSetupModal()
    {
        $this->showSetupModal = false;
        $this->reset(['setupSecret', 'qrCodeSvg', 'verificationCode', 'setupStep', 'recoveryCodes']);
    }

    public function disable2fa()
    {
        $service = app(TwoFactorService::class);
        $service->disableTwoFactor(auth()->user());
        $this->dispatch('swal:success', ['message' => 'L\'authentification à deux facteurs a été désactivée.']);
    }

    public function regenerateRecoveryCodes()
    {
        $service = app(TwoFactorService::class);
        $this->recoveryCodes = $service->generateRecoveryCodes();
        $encryptedCodes = Crypt::encryptString(json_encode($this->recoveryCodes));
        auth()->user()->update(['two_factor_recovery_codes' => $encryptedCodes]);
        $service->logEvent(auth()->user(), 'Recovery Codes Regenerated');
        $this->dispatch('swal:success', ['message' => 'Nouveaux codes de récupération générés.']);
    }

    public function removeTrustedDevice($deviceId)
    {
        $service = app(TwoFactorService::class);
        auth()->user()->trustedDevices()->where('id', $deviceId)->delete();
        $service->logEvent(auth()->user(), 'Device Removed');
        $this->dispatch('swal:success', ['message' => 'Appareil de confiance supprimé.']);
    }

    public function saveForce2faPolicies()
    {
        TwoFactorSetting::updateOrCreate(
            ['id' => 1],
            [
                'force_2fa_all' => $this->force_2fa_all,
                'force_2fa_admins' => $this->force_2fa_admins,
                'force_2fa_finance' => $this->force_2fa_finance,
                'force_2fa_managers' => $this->force_2fa_managers,
            ]
        );

        $this->dispatch('swal:success', ['message' => 'Politiques d\'obligation 2FA enregistrées.']);
    }

    public function revokeSession(string $sessionId): void
    {
        $user = auth()->user();
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        $this->dispatch('swal:success', ['message' => 'Session révoquée.']);
    }

    public function render()
    {
        $user = auth()->user();
        $service = app(TwoFactorService::class);

        $is2faEnabled = (bool) $user->two_factor_confirmed_at;
        $decryptedCodes = $service->getDecryptedRecoveryCodes($user);
        $trustedDevices = $user->trustedDevices()->where('expires_at', '>', now())->get();
        $auditLogs = TwoFactorAuditLog::where('user_id', $user->id)->latest()->take(15)->get();

        $score = 40;
        if ($is2faEnabled) $score += 40;
        if ($user->password_changed_at && $user->password_changed_at->diffInDays(now()) < 90) $score += 10;
        if ($trustedDevices->count() > 0) $score += 10;

        $allUsersSecurity = User::withCount('trustedDevices')->get();

        return view('livewire.admin.security-center', [
            'user' => $user,
            'is2faEnabled' => $is2faEnabled,
            'securityScore' => $score,
            'decryptedCodes' => $decryptedCodes,
            'trustedDevices' => $trustedDevices,
            'auditLogs' => $auditLogs,
            'allUsersSecurity' => $allUsersSecurity,
        ])->layout('layouts.app');
    }
}
