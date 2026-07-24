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

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Form Header -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <span>🔑 RÉCUPÉRATION DU COMPTE</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Mot de passe oublié ?
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Saisissez l'adresse email associée à votre compte agence. Nous vous enverrons un lien sécurisé pour réinitialiser votre mot de passe.
        </p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-5">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Adresse Email Professionnelle
            </label>
            <input wire:model="email" id="email" 
                   type="email" name="email" 
                   placeholder="nom@agence-assurance.ma" 
                   required autofocus 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span>Envoyer le Lien de Réinitialisation ➔</span>
            </button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center pt-4 border-t border-slate-200 text-xs text-slate-500 font-medium">
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center justify-center gap-1">
                <span>← Retour à la page de connexion</span>
            </a>
        </div>
    </form>
</div>
