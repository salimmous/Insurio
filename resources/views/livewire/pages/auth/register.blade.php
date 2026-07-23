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

<div class="space-y-6">
    <!-- Form Header -->
    <div class="space-y-2">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            Créer un Compte Agence
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            Rejoignez Insurio SaaS et automatisez la gestion de votre cabinet d'assurance.
        </p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nom Complet / Raison Sociale" />
            <x-text-input wire:model="name" id="name" class="block w-full" type="text" name="name" placeholder="ex: Agence Atlas Assurance" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse Email Professionnelle" />
            <x-text-input wire:model="email" id="email" class="block w-full" type="email" name="email" placeholder="contact@agence-atlas.ma" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Mot de Passe Sécurisé" />
            <x-text-input wire:model="password" id="password" class="block w-full"
                            type="password"
                            name="password"
                            placeholder="••••••••••••"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" value="Confirmer le Mot de Passe" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full"
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••••••"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button>
                <span>Créer Mon Compte Insurio ➔</span>
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-200 dark:border-slate-800/80 text-xs text-slate-500">
            Vous avez déjà un compte ? 
            <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                Se connecter ➔
            </a>
        </div>
    </form>
</div>
