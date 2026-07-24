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

<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Form Header -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <svg class="w-4 h-4 text-indigo-600 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>VÉRIFICATION EMAIL</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Vérification de l'Adresse Email
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Merci de vous être inscrit sur Insurio SaaS ! Veuillez vérifier votre boîte de réception et cliquer sur le lien de confirmation que nous venons de vous envoyer.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>Un nouveau lien de vérification a été envoyé à l'adresse email indiquée lors de votre inscription.</span>
        </div>
    @endif

    <div class="space-y-4 pt-2">
        <button wire:click="sendVerification" type="button" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
            <span>Renvoyer l'Email de Vérification</span>
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>

        <div class="text-center pt-3 border-t border-slate-200">
            <button wire:click="logout" type="button" class="text-xs font-bold text-slate-500 hover:text-slate-900 transition">
                Se déconnecter
            </button>
        </div>
    </div>
</div>
