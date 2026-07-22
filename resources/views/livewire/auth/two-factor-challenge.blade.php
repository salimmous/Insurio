<div class="min-h-screen bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md">

        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl border border-indigo-400/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-3xl font-black text-white tracking-tight">Insurio</span>
            </div>
            <p class="text-indigo-200/70 text-xs font-mono uppercase tracking-widest">Banking-Level 2FA Security Gateway</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-900/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-800 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base font-black text-white">
                            {{ $needsSetup ? 'Configuration 2FA Obligatoire' : 'Vérification 2FA Requis' }}
                        </h1>
                        <p class="text-indigo-100 text-xs">
                            {{ $needsSetup ? 'Scannez le QR Code pour configurer votre application' : 'Code TOTP à 6 chiffres obligatoire pour accéder' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5">

                @if($errorMessage)
                <div class="bg-rose-950/90 border border-rose-700/80 rounded-2xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-rose-200 font-semibold">{{ $errorMessage }}</p>
                </div>
                @endif

                @if($needsSetup)
                    <!-- Initial Setup Flow -->
                    <div class="text-center space-y-4">
                        <div class="inline-block p-4 bg-white rounded-2xl border border-slate-200 shadow-md">
                            {!! $qrCodeSvg !!}
                        </div>

                        <div class="p-3 bg-slate-950 rounded-xl border border-slate-800 font-mono text-xs">
                            <span class="text-[10px] text-slate-400 block font-sans">Clé Secrète Manuelle:</span>
                            <span class="font-bold text-indigo-400 text-sm select-all">{{ $setupSecret }}</span>
                        </div>

                        <div class="p-3 bg-slate-800/80 rounded-xl border border-slate-700 text-left text-xs space-y-2">
                            <span class="font-bold text-amber-400 block">⚠️ Sauvegardez vos 10 codes de secours:</span>
                            <div class="grid grid-cols-2 gap-1.5 font-mono text-[11px] text-emerald-400">
                                @foreach($setupRecoveryCodes as $rcode)
                                    <div>{{ $rcode }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-2">Entrez le code TOTP à 6 chiffres *</label>
                            <input
                                wire:model="code"
                                type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="6"
                                placeholder="123456"
                                class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-950 text-emerald-400 font-mono text-center text-2xl font-black tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                autofocus
                            >
                        </div>
                    </div>
                @elseif(!$useRecoveryCode)
                    <!-- Standard TOTP Code Flow -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">Code TOTP Authentificateur (Google, MS, Authy, 1Password) *</label>
                        <input
                            wire:model="code"
                            type="text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            maxlength="6"
                            placeholder="123456"
                            class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-950 text-emerald-400 font-mono text-center text-2xl font-black tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            autofocus
                        >
                    </div>
                @else
                    <!-- Recovery Code Flow -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-2">Code de Récupération (Recovery Code) *</label>
                        <input
                            wire:model="recoveryCode"
                            type="text"
                            placeholder="xxxxxx-xxxxxx"
                            class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-950 text-indigo-300 font-mono text-center text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            autofocus
                        >
                    </div>
                @endif

                <button
                    wire:click="verify"
                    wire:loading.attr="disabled"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-xs"
                >
                    <span wire:loading.remove wire:target="verify">Vérifier & Valider l'Accès ➔</span>
                    <span wire:loading wire:target="verify">Vérification en cours...</span>
                </button>

                @if(!$needsSetup)
                    <div class="text-center pt-2">
                        <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-400 hover:underline font-semibold">
                            {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
