<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Form Header -->
    <div class="space-y-2">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            Zone Sécurisée — Confirmation
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            Il s'agit d'une zone hautement sécurisée de l'application. Veuillez confirmer votre mot de passe pour continuer.
        </p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-5">
        <!-- Password -->
        <div>
            <x-input-label for="password" value="Mot de Passe Actuel" />

            <x-text-input wire:model="password"
                          id="password"
                          class="block w-full"
                          type="password"
                          name="password"
                          placeholder="••••••••••••"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="pt-2">
            <x-primary-button>
                <span>Confirmer et Continuer ➔</span>
            </x-primary-button>
        </div>
    </form>
</div>
