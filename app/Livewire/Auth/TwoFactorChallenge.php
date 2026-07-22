<?php

namespace App\Livewire\Auth;

use App\Services\Auth\LoginSecurityService;
use App\Services\Auth\TwoFactorService;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code = '';
    public string $recoveryCode = '';
    public bool $useRecoveryCode = false;
    public bool $rememberDevice = false;
    public string $errorMessage = '';

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user || !$user->two_factor_confirmed_at) {
            redirect()->route('dashboard');
            return;
        }

        // Check if device is already trusted
        $fingerprint = md5(request()->ip() . request()->userAgent());
        if (app(TwoFactorService::class)->isDeviceTrusted($user, $fingerprint)) {
            session(['two_factor_verified' => true]);
            redirect()->route('dashboard');
            return;
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
                $this->errorMessage = 'Code Authenticator TOTP invalide. Veuillez réessayer.';
                $service->logEvent($user, 'Failed Verification');
                return;
            }
        }

        // Mark 2FA verified in session
        session(['two_factor_verified' => true]);
        $service->logEvent($user, 'Successful Verification');

        // Trust device for 30 days if selected
        if ($this->rememberDevice) {
            $fingerprint = md5(request()->ip() . request()->userAgent());
            $service->trustDevice($user, $fingerprint, 'Navigateur Confiance (30j)', request()->ip());
        }

        redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')
            ->layout('layouts.guest');
    }
}
