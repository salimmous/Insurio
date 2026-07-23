<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Form Header -->
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
            <span>🔐 ENTERPRISE AUTHENTICATION</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            Connexion au Portail Agence
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            Saisissez vos identifiants d'administrateur d'agence pour accéder à la plateforme.
        </p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse Email Professionnelle" />
            <x-text-input wire:model="form.email" id="email" class="block w-full" type="email" name="email" placeholder="admin@agence-assurance.ma" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <x-input-label for="password" value="Mot de Passe" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <x-text-input wire:model="form.password" id="password" class="block w-full"
                            type="password"
                            name="password"
                            placeholder="••••••••••••"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-2 focus:ring-indigo-500">
                <span class="ms-2 text-xs font-medium text-slate-600 dark:text-slate-400">Maintenir la session active</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button>
                <span>Se connecter à l'Espace Agence ➔</span>
            </x-primary-button>
        </div>
    </form>
</div>
