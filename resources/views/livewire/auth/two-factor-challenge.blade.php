<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Header Title -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <svg class="w-4 h-4 text-indigo-600 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>SÉCURITÉ HARDENED TOTP</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            {{ $needsSetup ? 'Configuration 2FA Obligatoire' : 'Authentification à Deux Facteurs' }}
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            {{ $needsSetup ? 'Scannez le QR code ci-dessous avec votre application d\'authentification (Google/Microsoft Authenticator, Authy, 1Password).' : 'Entrez le code TOTP à 6 chiffres généré par votre application d\'authentification.' }}
        </p>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-700 flex items-start gap-3">
            <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errorMessage }}</span>
        </div>
    @endif

    <form wire:submit.prevent="verify" class="space-y-5">
        @if($needsSetup)
            <!-- Inline First-Time Setup -->
            <div class="text-center space-y-4">
                <div class="inline-block p-4 bg-white rounded-2xl border border-slate-200 shadow-md">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 font-mono text-xs text-left">
                    <span class="text-[10px] text-slate-400 uppercase font-sans font-bold block mb-0.5">Clé Secrète Manuelle:</span>
                    <span class="font-bold text-indigo-600 text-sm select-all">{{ $setupSecret }}</span>
                </div>

                <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-left text-xs space-y-2">
                    <span class="font-bold text-amber-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Sauvegardez vos 10 codes de secours:</span>
                    </span>
                    <div class="grid grid-cols-2 gap-1.5 font-mono text-[11px] text-amber-900">
                        @foreach($setupRecoveryCodes as $rcode)
                            <div>{{ $rcode }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="text-left space-y-1.5">
                    <label for="code" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Code TOTP de confirmation (6 chiffres) *
                    </label>
                    <input
                        wire:model="code"
                        id="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        class="w-full px-4 py-3.5 border border-slate-300 bg-white text-slate-900 rounded-xl font-mono font-black text-center text-2xl tracking-widest focus:ring-2 focus:ring-indigo-600 shadow-xs"
                        autofocus
                    >
                </div>
            </div>
        @elseif(!$useRecoveryCode)
            <!-- Standard TOTP Challenge -->
            <div class="space-y-1.5">
                <label for="code" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Code Authentificateur TOTP à 6 Chiffres *
                </label>
                <input
                    wire:model="code"
                    id="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    placeholder="123456"
                    class="w-full px-4 py-3.5 border border-slate-300 bg-white text-slate-900 rounded-xl font-mono font-black text-center text-2xl tracking-widest focus:ring-2 focus:ring-indigo-600 shadow-xs"
                    autofocus
                >
            </div>
        @else
            <!-- Recovery Code Input -->
            <div class="space-y-1.5">
                <label for="recoveryCode" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    Code de Récupération (Recovery Code) *
                </label>
                <input
                    wire:model="recoveryCode"
                    id="recoveryCode"
                    type="text"
                    placeholder="xxxxxx-xxxxxx"
                    class="w-full px-4 py-3 border border-slate-300 bg-white text-slate-900 rounded-xl font-mono font-bold text-center text-sm focus:ring-2 focus:ring-indigo-600 shadow-xs"
                    autofocus
                >
            </div>
        @endif

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span>Vérifier & Accéder au Dashboard</span>
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>

    @if(!$needsSetup)
        <div class="text-center pt-3 border-t border-slate-200">
            <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-600 hover:text-indigo-800 font-bold transition-colors inline-flex items-center gap-1.5">
                @if($useRecoveryCode)
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Utiliser le code TOTP Authentificateur</span>
                @else
                    <svg class="w-4 h-4 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>Utiliser un code de récupération</span>
                @endif
            </button>
        </div>
    @endif
</div>
