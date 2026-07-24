<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Form Header -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <span>🔒 NOUVEAU MOT DE PASSE</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Nouveau Mot de Passe
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Définissez votre nouveau mot de passe sécurisé pour finaliser la réinitialisation.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-5">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Adresse Email Professionnelle
            </label>
            <input wire:model="email" id="email" 
                   type="email" name="email" 
                   required autofocus autocomplete="username" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Nouveau Mot de Passe
            </label>
            <input wire:model="password" id="password" 
                   type="password" name="password" 
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
                   type="password" name="password_confirmation" 
                   required autocomplete="new-password" 
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span>Réinitialiser Mon Mot de Passe ➔</span>
            </button>
        </div>
    </form>
</div>
