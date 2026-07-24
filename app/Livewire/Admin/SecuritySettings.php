<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\Auth\TwoFactorService;
use App\Models\User;
use App\Models\TwoFactorSetting;
use App\Models\TwoFactorAuditLog;
use Illuminate\Support\Facades\Hash;

class SecuritySettings extends Component
{
    public $activeTab = 'security'; // security, admin_2fa, audit_logs

    // 2FA Enable Setup State
    public $showSetupModal = false;
    public $setupSecret = '';
    public $qrCodeSvg = '';
    public $verificationCode = '';
    public $setupStep = 1; // 1: QR Scan & Verify, 2: Display Recovery Codes
    public $recoveryCodes = [];

    // Trusted Devices & Password
    public $trustCurrentDevice = true;
    public $currentPassword = '';

    // Super Admin 2FA Force Policies
    public $force_2fa_all = false;
    public $force_2fa_admins = false;
    public $force_2fa_finance = false;
    public $force_2fa_managers = false;

    public function mount()
    {
        $setting = TwoFactorSetting::first();
        if ($setting) {
            $this->force_2fa_all = $setting->force_2fa_all;
            $this->force_2fa_admins = $setting->force_2fa_admins;
            $this->force_2fa_finance = $setting->force_2fa_finance;
            $this->force_2fa_managers = $setting->force_2fa_managers;
        }
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
        $this->setupStep = 2; // Show Recovery Codes
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
        $encryptedCodes = \Illuminate\Support\Facades\Crypt::encryptString(json_encode($this->recoveryCodes));
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

    // SESSION MANAGEMENT ACTIONS
    public function logoutCurrentDevice()
    {
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $currentSessionId = session()->getId();
        $sessionManager->terminateSession($currentSessionId, auth()->user()->name);

        auth()->guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Vous avez été déconnecté de cet appareil.');
    }

    public function logoutOtherDevices()
    {
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $currentSessionId = session()->getId();
        $count = $sessionManager->terminateOtherSessions(auth()->user(), $currentSessionId);

        session()->flash('message', "{$count} autre(s) session(s) active(s) révoquée(s) avec succès.");
    }

    public function logoutEverywhere()
    {
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $sessionManager->terminateAllUserSessions(auth()->user(), auth()->user()->name);

        auth()->guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Toutes vos sessions actives ont été fermées.');
    }

    public function terminateSingleSession($sessionId)
    {
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $sessionManager->terminateSession($sessionId, auth()->user()->name);

        session()->flash('message', 'La session sélectionnée a été fermée.');
    }

    public function forceLogoutUserAdmin($userId)
    {
        $targetUser = User::findOrFail($userId);
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $count = $sessionManager->terminateAllUserSessions($targetUser, auth()->user()->name);

        session()->flash('message', "Déconnexion forcée effectuée. {$count} session(s) fermée(s) pour {$targetUser->name}.");
    }

    public function terminateAllSystemSessionsAdmin()
    {
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);
        $count = $sessionManager->terminateSystemWideSessions(auth()->user()->name);

        session()->flash('message', "Urgence Système: {$count} sessions actives fermées sur l'ensemble du réseau.");
    }

    public function render()
    {
        $user = auth()->user();
        $service = app(TwoFactorService::class);
        $sessionManager = app(\App\Services\Auth\SessionManagementService::class);

        $is2faEnabled = (bool) $user->two_factor_confirmed_at;
        $decryptedCodes = $service->getDecryptedRecoveryCodes($user);
        $trustedDevices = \Illuminate\Support\Facades\Schema::hasTable('trusted_devices')
            ? $user->trustedDevices()->where('expires_at', '>', now())->get()
            : collect();
        $auditLogs = \Illuminate\Support\Facades\Schema::hasTable('two_factor_audit_logs')
            ? TwoFactorAuditLog::where('user_id', $user->id)->latest()->take(15)->get()
            : collect();

        // Active Sessions & History
        $userSessions = $sessionManager->getActiveSessionsForUser($user);
        $currentSessionId = session()->getId();
        $lastSuccessfulLogin = \Illuminate\Support\Facades\Schema::hasTable('login_histories')
            ? $sessionManager->getLastSuccessfulLogin($user)
            : null;
        $lastFailedLogin = \Illuminate\Support\Facades\Schema::hasTable('login_histories')
            ? $sessionManager->getLastFailedLogin($user)
            : null;

        // Super Admin Global View
        $allSystemSessions = $sessionManager->getAllActiveSessions();
        $recentLoginHistory = \Illuminate\Support\Facades\Schema::hasTable('login_histories')
            ? \App\Models\LoginHistory::with('user')->latest('created_at')->take(40)->get()
            : collect();
        $recentFailedLogins = \Illuminate\Support\Facades\Schema::hasTable('login_histories')
            ? \App\Models\LoginHistory::with('user')->where('status', 'failed')->latest('created_at')->take(40)->get()
            : collect();

        // Calculate Security Score
        $score = 40;
        if ($is2faEnabled) $score += 40;
        if ($user->password_changed_at && $user->password_changed_at->diffInDays(now()) < 90) $score += 10;
        if ($trustedDevices->count() > 0) $score += 10;

        // All Users for Super Admin view
        $allUsersSecurity = \Illuminate\Support\Facades\Schema::hasTable('trusted_devices')
            ? User::withCount('trustedDevices')->get()
            : User::all();

        return view('livewire.admin.security-settings', [
            'user' => $user,
            'is2faEnabled' => $is2faEnabled,
            'securityScore' => $score,
            'decryptedCodes' => $decryptedCodes,
            'trustedDevices' => $trustedDevices,
            'auditLogs' => $auditLogs,
            'allUsersSecurity' => $allUsersSecurity,
            'userSessions' => $userSessions,
            'currentSessionId' => $currentSessionId,
            'lastSuccessfulLogin' => $lastSuccessfulLogin,
            'lastFailedLogin' => $lastFailedLogin,
            'allSystemSessions' => $allSystemSessions,
            'recentLoginHistory' => $recentLoginHistory,
            'recentFailedLogins' => $recentFailedLogins,
        ])->layout('layouts.app');
    }
}
