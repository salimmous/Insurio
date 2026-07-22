<?php

namespace App\Livewire\Auth;

use App\Services\Auth\TwoFactorService;
use App\Models\TwoFactorSetting;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code = '';
    public string $recoveryCode = '';
    public bool $useRecoveryCode = false;
    public string $errorMessage = '';

    // Setup mode if forced 2FA and user has not configured TOTP yet
    public bool $needsSetup = false;
    public string $setupSecret = '';
    public string $qrCodeSvg = '';
    public array $setupRecoveryCodes = [];

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user) {
            redirect()->route('login');
            return;
        }

        // Check if 2FA already verified in session
        if (session('two_factor_verified')) {
            redirect()->route('dashboard');
            return;
        }

        // If user has not configured TOTP yet, trigger initial setup inline
        if (!$user->two_factor_confirmed_at) {
            $this->needsSetup = true;
            $service = app(TwoFactorService::class);
            $this->setupSecret = $service->generateSecretKey();
            $this->qrCodeSvg = $service->getQrCodeSvg($user, $this->setupSecret);
            $this->setupRecoveryCodes = $service->generateRecoveryCodes();
        }
    }

    public function toggleRecoveryMode(): void
    {
        $this->useRecoveryCode = !$this->useRecoveryCode;
        $this->code = '';
        $this->recoveryCode = '';
        $this->errorMessage = '';
    }

    public function verify(): void
    {
        $user = auth()->user();
        if (!$user) {
            redirect()->route('login');
            return;
        }

        $service = app(TwoFactorService::class);

        // First-time Setup Verification
        if ($this->needsSetup) {
            $this->validate(['code' => 'required|string|size:6']);

            if (!$service->verifyTotp($this->setupSecret, $this->code)) {
                $this->errorMessage = 'Code TOTP invalide. Veuillez vérifier votre application authentificateur (Google/Microsoft Authenticator, Authy, 1Password).';
                $service->logEvent($user, 'Failed Verification');
                return;
            }

            $service->confirmTwoFactor($user, $this->setupSecret, $this->setupRecoveryCodes);
            session(['two_factor_verified' => true]);
            $service->logEvent($user, 'Successful Verification');
            redirect()->route('dashboard');
            return;
        }

        // Standard Login 2FA Challenge Verification
        if ($this->useRecoveryCode) {
            $this->validate(['recoveryCode' => 'required|string']);

            if (!$service->verifyAndConsumeRecoveryCode($user, $this->recoveryCode)) {
                $this->errorMessage = 'Code de récupération invalide ou déjà utilisé.';
                $service->logEvent($user, 'Failed Verification');
                return;
            }
        } else {
            $this->validate(['code' => 'required|string|size:6']);

            $secret = $service->getDecryptedSecret($user);
            if (!$secret || !$service->verifyTotp($secret, $this->code)) {
                $this->errorMessage = 'Code TOTP invalide. Veuillez réessayer.';
                $service->logEvent($user, 'Failed Verification');
                return;
            }
        }

        // Success: Grant session access
        session(['two_factor_verified' => true]);
        $service->logEvent($user, 'Successful Verification');

        redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')
            ->layout('layouts.guest');
    }
}
