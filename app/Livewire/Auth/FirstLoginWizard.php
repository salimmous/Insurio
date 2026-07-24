<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Employe;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Layout('layouts.guest')]
class FirstLoginWizard extends Component
{
    public int $currentStep = 1;
    public string $token = '';
    public ?User $user = null;
    public ?Employe $employe = null;

    // Step 2: Password Change
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    // Step 3 & 4: 2FA Setup
    public string $secretKey = '';
    public string $qrCodeSvg = '';
    public string $totpCode = '';

    // Step 5: Recovery Codes
    public array $recoveryCodes = [];
    public bool $savedRecoveryCodes = false;

    // Error & Expiration States
    public string $errorMessage = '';
    public bool $isExpiredOrInvalid = false;

    public function mount(?string $token = null)
    {
        if ($token) {
            $this->token = $token;
            $this->user = User::where('activation_token', $token)
                ->orWhere('invitation_token', $token)
                ->first();

            if (!$this->user) {
                $this->isExpiredOrInvalid = true;
                return;
            }

            // Check 24h expiration
            $expiration = $this->user->activation_token_expires_at ?: $this->user->invitation_expires_at;
            if ($expiration && $expiration->isPast()) {
                $this->isExpiredOrInvalid = true;
                return;
            }

            // Auto-login user if valid token
            if (!Auth::check() || Auth::id() !== $this->user->id) {
                Auth::login($this->user);
            }
        } else {
            $this->user = Auth::user();
        }

        if (!$this->user) {
            return redirect()->route('login');
        }

        // If user has already completed activation, redirect to dashboard
        if ($this->user->isActivated()) {
            session(['two_factor_verified' => true]);
            return redirect()->route('dashboard');
        }

        $this->employe = $this->user->employe;

        // Initialize 2FA Secret Key & QR Code
        $google2fa = new Google2FA();
        $this->secretKey = $this->user->two_factor_secret 
            ? Crypt::decryptString($this->user->two_factor_secret) 
            : $google2fa->generateSecretKey();

        $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
            ? tenant('name') 
            : (\App\Models\Setting::get('agency_name') ?: 'Insurio Agency');

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $agencyName,
            $this->user->email,
            $this->secretKey
        );

        $this->qrCodeSvg = base64_encode(QrCode::size(200)->margin(1)->generate($qrCodeUrl));

        // Generate 10 recovery codes
        $this->recoveryCodes = array_map(fn() => Str::random(10) . '-' . Str::random(10), range(1, 10));
    }

    public function goToStep(int $step)
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    public function goToStep2()
    {
        $this->currentStep = 2;
    }

    public function saveNewPassword()
    {
        $this->resetErrorBag();

        $this->validate([
            'new_password' => [
                'required',
                'string',
                'min:12',
                'regex:/[A-Z]/', // Uppercase
                'regex:/[a-z]/', // Lowercase
                'regex:/[0-9]/', // Number
                'regex:/[@$!%*?&#^()_+\-=\[\]{};\':"\\|,.<>\/?]/', // Symbol
                'confirmed',
            ],
        ], [
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le mot de passe doit contenir au moins 12 caractères.',
            'new_password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un symbole spécial.',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $this->user->forceFill([
            'password' => Hash::make($this->new_password),
            'password_changed_at' => now(),
        ])->save();

        $this->currentStep = 3;
    }

    public function goToStep4()
    {
        $this->currentStep = 4;
    }

    public function verifyTotpCode()
    {
        $this->resetErrorBag();

        $this->validate([
            'totpCode' => 'required|string|size:6',
        ], [
            'totpCode.required' => 'Le code d\'authentification à 6 chiffres est obligatoire.',
            'totpCode.size' => 'Le code TOTP doit contenir exactement 6 chiffres.',
        ]);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($this->secretKey, $this->totpCode, 2);

        if (!$valid) {
            $this->addError('totpCode', 'Le code d\'authentification 2FA est incorrect. Veuillez vérifier l\'heure de votre téléphone et réessayer.');
            return;
        }

        // Enable 2FA & Confirm
        $this->user->forceFill([
            'two_factor_secret' => Crypt::encryptString($this->secretKey),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->currentStep = 5;
    }

    public function completeRecoveryStep()
    {
        if (!$this->savedRecoveryCodes) {
            $this->addError('savedRecoveryCodes', 'Vous devez confirmer avoir sauvegardé vos codes de récupération.');
            return;
        }

        // Save Recovery Codes
        $this->user->forceFill([
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode($this->recoveryCodes)),
        ])->save();

        $this->currentStep = 6;
    }

    public function finishWizard()
    {
        // Mark account as fully activated
        $this->user->forceFill([
            'first_login' => false,
            'activated_at' => now(),
            'status' => 'active',
            'activation_token' => null,
            'invitation_token' => null,
        ])->save();

        if ($this->employe) {
            $this->employe->update(['statut' => 'actif']);
        }

        session(['two_factor_verified' => true]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.first-login-wizard');
    }
}
