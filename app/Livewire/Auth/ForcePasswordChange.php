<?php

namespace App\Livewire\Auth;

use App\Services\Auth\PasswordPolicyService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForcePasswordChange extends Component
{
    public string $password = '';
    public string $password_confirmation = '';
    public array $policyErrors = [];
    public bool $success = false;

    public function save(): void
    {
        $this->validate([
            'password'              => 'required|string|confirmed',
            'password_confirmation' => 'required|string',
        ]);

        $user    = auth()->user();
        $service = app(PasswordPolicyService::class);

        // Check policy
        $errors = $service->validate($this->password, $user);
        if (!empty($errors)) {
            $this->policyErrors = $errors;
            return;
        }

        // Check reuse
        if ($service->isReused($this->password, $user)) {
            $this->policyErrors = [__('You cannot reuse any of your last 5 passwords.')];
            return;
        }

        // All checks passed — update password
        $user->recordPasswordHistory(); // Save current password before overwriting
        $user->update([
            'password'           => Hash::make($this->password),
            'password_changed_at' => now(),
        ]);

        // Invalidate all other sessions
        auth()->logoutOtherDevices($this->password);

        $this->policyErrors = [];
        $this->success = true;
        $this->password = '';
        $this->password_confirmation = '';

        redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.force-password-change')
            ->layout('layouts.guest');
    }
}
