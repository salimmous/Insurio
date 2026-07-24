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

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Form Header -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <span>🔐 ENTERPRISE AUTHENTICATION</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Connexion au Portail Agence
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Saisissez vos identifiants d'administrateur d'agence pour accéder à la plateforme.
        </p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Adresse Email Professionnelle
            </label>
            <input wire:model="form.email" id="email" 
                   type="email" name="email" 
                   placeholder="admin@agence-assurance.ma" 
                   required autofocus autocomplete="username" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Mot de Passe
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors" href="{{ route('password.request') }}" wire:navigate>
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <input wire:model="form.password" id="password"
                   type="password"
                   name="password"
                   placeholder="••••••••••••"
                   required autocomplete="current-password" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                <span class="ms-2.5 text-xs font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Maintenir la session active</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span>Se connecter à l'Espace Agence ➔</span>
            </button>
        </div>
    </form>
</div>
