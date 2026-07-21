<?php

namespace App\Livewire\Admin;

use App\Services\Auth\TwoFactorService;
use App\Services\Auth\PasswordPolicyService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SecurityCenter extends Component
{
    public string $activeTab = 'sessions';

    // Password change
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public array $passwordErrors = [];
    public string $passwordSuccess = '';

    // MFA
    public string $mfa_code = '';
    public string $mfaError = '';
    public bool $mfaSuccess = false;

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // -------------------------------------------------------------------
    // Sessions
    // -------------------------------------------------------------------

    public function revokeSession(string $sessionId): void
    {
        $user = auth()->user();
        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $user->id)
            ->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Session revoked.']);
    }

    public function revokeAllSessions(): void
    {
        $user = auth()->user();
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', session()->getId())
            ->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'All other sessions revoked.']);
    }

    // -------------------------------------------------------------------
    // Trusted Devices
    // -------------------------------------------------------------------

    public function revokeDevice(int $deviceId): void
    {
        auth()->user()->trustedDevices()->where('id', $deviceId)->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Device removed.']);
    }

    // -------------------------------------------------------------------
    // Two-Factor Auth
    // -------------------------------------------------------------------

    public function enableMfa(): void
    {
        $user    = auth()->user();
        $service = app(TwoFactorService::class);
        $service->sendCode($user);
        $this->mfaError   = '';
        $this->mfaSuccess = false;
        $this->dispatch('notify', ['type' => 'info', 'message' => 'A verification code has been sent to your email.']);
    }

    public function confirmEnableMfa(): void
    {
        $user    = auth()->user();
        $service = app(TwoFactorService::class);

        if (!$service->verify($user, $this->mfa_code)) {
            $this->mfaError = 'Invalid or expired code.';
            return;
        }

        $service->enable($user);
        $this->mfaError   = '';
        $this->mfaSuccess = true;
        $this->mfa_code   = '';
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Two-factor authentication enabled.']);
    }

    public function disableMfa(): void
    {
        $user = auth()->user();
        $this->validate(['current_password' => 'required']);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Incorrect password.');
            return;
        }

        app(TwoFactorService::class)->disable($user);
        session(['two_factor_verified' => true]);
        $this->dispatch('notify', ['type' => 'warning', 'message' => 'Two-factor authentication disabled.']);
    }

    // -------------------------------------------------------------------
    // Password Change
    // -------------------------------------------------------------------

    public function changePassword(): void
    {
        $this->validate([
            'current_password'        => 'required',
            'new_password'            => 'required|string|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->passwordErrors = ['Current password is incorrect.'];
            return;
        }

        $service = app(PasswordPolicyService::class);
        $errors  = $service->validate($this->new_password, $user);

        if (!empty($errors)) {
            $this->passwordErrors = $errors;
            return;
        }

        if ($service->isReused($this->new_password, $user)) {
            $this->passwordErrors = ['You cannot reuse any of your last 5 passwords.'];
            return;
        }

        $user->recordPasswordHistory();
        $user->update([
            'password'            => Hash::make($this->new_password),
            'password_changed_at' => now(),
        ]);

        auth()->logoutOtherDevices($this->new_password);

        $this->passwordErrors = [];
        $this->passwordSuccess = 'Password updated successfully. All other devices have been logged out.';
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function render()
    {
        $user     = auth()->user();
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                $ua = $session->user_agent ?? '';
                return (object) [
                    'id'            => $session->id,
                    'ip_address'    => $session->ip_address,
                    'user_agent'    => $ua,
                    'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                    'is_current'    => $session->id === session()->getId(),
                    'browser'       => match(true) {
                        str_contains($ua, 'Firefox') => 'Firefox',
                        str_contains($ua, 'Chrome')  => 'Chrome',
                        str_contains($ua, 'Safari')  => 'Safari',
                        str_contains($ua, 'Edge')    => 'Edge',
                        default                      => 'Browser',
                    },
                    'os'            => match(true) {
                        str_contains($ua, 'Windows') => 'Windows',
                        str_contains($ua, 'Mac')     => 'macOS',
                        str_contains($ua, 'Linux')   => 'Linux',
                        str_contains($ua, 'Android') => 'Android',
                        str_contains($ua, 'iPhone')  => 'iOS',
                        default                      => 'Device',
                    },
                ];
            });

        $loginHistories = $user->loginHistories()->take(30)->get();
        $trustedDevices = $user->trustedDevices()->where('expires_at', '>', now())->get();

        return view('livewire.admin.security-center', compact(
            'sessions', 'loginHistories', 'trustedDevices'
        ));
    }
}
