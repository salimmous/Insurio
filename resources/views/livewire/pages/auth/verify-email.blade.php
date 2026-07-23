<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Form Header -->
    <div class="space-y-2">
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            Vérification de l'Adresse Email
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            Merci de vous être inscrit sur Insurio SaaS ! Veuillez vérifier votre boîte de réception et cliquer sur le lien de confirmation que nous venons de vous envoyer.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
            Un nouveau lien de vérification a été envoyé à l'adresse email indiquée lors de votre inscription.
        </div>
    @endif

    <div class="space-y-4 pt-2">
        <x-primary-button wire:click="sendVerification">
            <span>Renvoyer l'Email de Vérification ➔</span>
        </x-primary-button>

        <div class="text-center pt-3 border-t border-slate-200 dark:border-slate-800">
            <button wire:click="logout" type="button" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition">
                Se déconnecter
            </button>
        </div>
    </div>
</div>
