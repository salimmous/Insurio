<?php

namespace App\Livewire\Auth;

use App\Services\Auth\LoginSecurityService;
use App\Services\Auth\TwoFactorService;
use Livewire\Component;

class TwoFactorChallenge extends Component
{
    public string $code = '';
    public bool $rememberDevice = false;
    public bool $codeSent = false;
    public string $errorMessage = '';

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user || !$user->hasTwoFactorEnabled()) {
            redirect()->route('dashboard');
            return;
        }

        // Auto-send code on mount
        $this->sendCode();
    }

    public function sendCode(): void
    {
        $user = auth()->user();
        if (!$user) return;

        app(TwoFactorService::class)->sendCode($user);
        $this->codeSent = true;
        $this->errorMessage = '';
    }

    public function verify(): void
    {
        $this->validate([
            'code' => 'required|digits:6',
        ]);

        $user = auth()->user();
        if (!$user) {
            redirect()->route('login');
            return;
        }

        $service = app(TwoFactorService::class);

        if (!$service->verify($user, $this->code)) {
            $this->errorMessage = __('Invalid or expired verification code. Please try again.');
            return;
        }

        // Mark MFA as verified in session
        session(['two_factor_verified' => true]);

        // Trust device if requested
        if ($this->rememberDevice) {
            $loginService  = app(LoginSecurityService::class);
            $fingerprint   = $loginService->getDeviceFingerprint();
            $deviceName    = $this->getDeviceName();
            $service->trustDevice($user, $fingerprint, $deviceName, request()->ip());
        }

        redirect()->route('dashboard');
    }

    private function getDeviceName(): string
    {
        $ua = request()->userAgent() ?? '';
        $browser = match(true) {
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Chrome')  => 'Chrome',
            str_contains($ua, 'Safari')  => 'Safari',
            str_contains($ua, 'Edge')    => 'Edge',
            default                      => 'Browser',
        };
        $os = match(true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac')     => 'macOS',
            str_contains($ua, 'Linux')   => 'Linux',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone')  => 'iPhone',
            default                      => 'Device',
        };
        return "$browser on $os";
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge')
            ->layout('layouts.guest');
    }
}
