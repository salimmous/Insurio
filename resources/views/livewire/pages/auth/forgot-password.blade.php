<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-6">
    <!-- Form Header -->
    <div class="space-y-2">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            Mot de passe oublié ?
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            Saisissez l'adresse email associée à votre compte agence. Nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.
        </p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse Email Professionnelle" />
            <x-text-input wire:model="email" id="email" class="block w-full" type="email" name="email" placeholder="nom@agence-assurance.ma" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button>
                <span>Envoyer le Lien de Réinitialisation ➔</span>
            </x-primary-button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center pt-4 border-t border-slate-200 dark:border-slate-800/80 text-xs text-slate-500">
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center justify-center gap-1">
                <span>← Retour à la page de connexion</span>
            </a>
        </div>
    </form>
</div>
