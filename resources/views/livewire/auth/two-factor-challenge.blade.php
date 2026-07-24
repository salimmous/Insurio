<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Header Title -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
            <span>🛡️ HARDENED TOTP SECURITY</span>
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
            <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <span class="font-bold text-amber-800 block">⚠️ Sauvegardez vos 10 codes de secours:</span>
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
                <span>Vérifier & Accéder au Dashboard ➔</span>
            </button>
        </div>
    </form>

    @if(!$needsSetup)
        <div class="text-center pt-3 border-t border-slate-200">
            <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-600 hover:text-indigo-800 hover:underline font-bold transition-colors">
                {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
            </button>
        </div>
    @endif
</div>
