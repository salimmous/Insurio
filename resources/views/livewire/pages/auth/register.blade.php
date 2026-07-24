<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Form Header -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <span>✨ CRÉATION D'AGENCE</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Créer un Compte Agence
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Rejoignez Insurio SaaS et automatisez la gestion de votre cabinet d'assurance.
        </p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Nom Complet / Raison Sociale
            </label>
            <input wire:model="name" id="name" 
                   type="text" name="name" 
                   placeholder="ex: Agence Atlas Assurance" 
                   required autofocus autocomplete="name" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Adresse Email Professionnelle
            </label>
            <input wire:model="email" id="email" 
                   type="email" name="email" 
                   placeholder="contact@agence-atlas.ma" 
                   required autocomplete="username" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Mot de Passe Sécurisé
            </label>
            <input wire:model="password" id="password"
                   type="password"
                   name="password"
                   placeholder="••••••••••••"
                   required autocomplete="new-password" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Confirmer le Mot de Passe
            </label>
            <input wire:model="password_confirmation" id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   placeholder="••••••••••••"
                   required autocomplete="new-password" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span>Créer Mon Compte Insurio ➔</span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-200 text-xs text-slate-500 font-medium">
            Vous avez déjà un compte ? 
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                Se connecter ➔
            </a>
        </div>
    </form>
</div>
