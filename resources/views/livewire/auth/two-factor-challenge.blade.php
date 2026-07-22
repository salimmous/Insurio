<div class="min-h-screen bg-gradient-to-br from-slate-900 to-indigo-950 flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md">

        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-2xl font-black text-white">Insurio</span>
            </div>
            <p class="text-slate-400 text-xs font-mono uppercase tracking-wider">Enterprise Security Gateway</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700/80 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-black text-white">Authentification 2FA TOTP</h1>
                        <p class="text-indigo-100 text-xs">Application Authentificateur (Google, MS, Authy)</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5">

                @if($errorMessage)
                <div class="bg-rose-950/80 border border-rose-700 rounded-xl p-3.5 flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-rose-200 font-semibold">{{ $errorMessage }}</p>
                </div>
                @endif

                @if(!$useRecoveryCode)
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">Code TOTP (6 Chiffres)</label>
                        <input
                            wire:model="code"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="6"
                            placeholder="123456"
                            class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-900 text-emerald-400 font-mono text-center text-2xl font-black tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            autofocus
                        >
                    </div>
                @else
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">Code de Récupération (Recovery Code)</label>
                        <input
                            wire:model="recoveryCode"
                            type="text"
                            placeholder="xxxxxx-xxxxxx"
                            class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-900 text-indigo-300 font-mono text-center text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            autofocus
                        >
                    </div>
                @endif

                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="rememberDevice" type="checkbox" class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-indigo-600 focus:ring-indigo-500">
                    <div>
                        <span class="text-xs font-bold text-slate-200 block">Faire confiance à cet appareil pendant 30 jours</span>
                        <span class="text-[10px] text-slate-400 block">Ne plus demander de code TOTP sur ce navigateur pendant 30j</span>
                    </div>
                </label>

                <button
                    wire:click="verify"
                    wire:loading.attr="disabled"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-xs"
                >
                    <span wire:loading.remove wire:target="verify">Valider & Se Connecter ➔</span>
                    <span wire:loading wire:target="verify">Vérification en cours...</span>
                </button>

                <div class="text-center pt-2">
                    <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-400 hover:underline font-semibold">
                        {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
