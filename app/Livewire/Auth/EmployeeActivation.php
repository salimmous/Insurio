<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Employe;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

#[Layout('layouts.guest')]
class EmployeeActivation extends Component
{
    public string $token = '';
    public ?User $user = null;
    public ?Employe $employe = null;

    public string $password = '';
    public string $password_confirmation = '';
    public string $totpCode = '';

    public string $secret = '';
    public string $qrCodeSvg = '';
    public array $recoveryCodes = [];

    public string $errorMessage = '';
    public bool $isExpiredOrInvalid = false;

    public function mount(string $token)
    {
        $this->token = $token;
        
        $this->user = User::where('invitation_token', $token)->first();

        if (!$this->user || !$this->user->invitation_expires_at || $this->user->invitation_expires_at->isPast()) {
            $this->isExpiredOrInvalid = true;
            return;
        }

        $this->employe = $this->user->employe;

        // Generate 2FA Secret for activation
        $google2fa = new Google2FA();
        if (!$this->user->two_factor_secret) {
            $this->secret = $google2fa->generateSecretKey();
        } else {
            $this->secret = decrypt($this->user->two_factor_secret);
        }

        $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
            ? tenant('name') 
            : (\App\Models\Setting::get('agency_name') ?: 'Insurio Agency');

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $agencyName,
            $this->user->email,
            $this->secret
        );

        $this->qrCodeSvg = QrCode::size(180)->margin(1)->generate($qrCodeUrl);

        // Generate 10 recovery codes
        $this->recoveryCodes = array_map(fn () => Str::random(10), range(1, 10));
    }

    public function activate()
    {
        $this->errorMessage = '';

        if ($this->isExpiredOrInvalid || !$this->user) {
            $this->errorMessage = "Lien d'activation invalide ou expiré.";
            return;
        }

        $this->validate([
            'password' => 'required|string|min:8|confirmed',
            'totpCode' => 'required|string|size:6',
        ], [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'totpCode.required' => 'Le code d\'authentification à 6 chiffres est obligatoire.',
            'totpCode.size' => 'Le code TOTP doit contenir exactement 6 chiffres.',
        ]);

        // Verify TOTP Code
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($this->secret, $this->totpCode, 2);

        if (!$valid) {
            $this->errorMessage = 'Le code d\'authentification 2FA saisi est incorrect. Veuillez réessayer.';
            return;
        }

        // Activate User Account
        $this->user->forceFill([
            'password' => Hash::make($this->password),
            'email_verified_at' => now(),
            'status' => 'active',
            'invitation_token' => null,
            'invitation_expires_at' => null,
            'two_factor_secret' => encrypt($this->secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode($this->recoveryCodes)),
        ])->save();

        // Update Employe record status
        if ($this->employe) {
            $this->employe->update([
                'statut' => 'actif',
            ]);
        }

        // Automatically log the user in
        Auth::login($this->user);

        return redirect()->route('dashboard')->with('status', 'Votre compte employé a été activé avec succès !');
    }

    public function render()
    {
        return view('livewire.auth.employee-activation');
    }
}
