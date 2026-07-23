<div class="space-y-6">
    <!-- Header Title -->
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
            <span>🛡️ HARDENED TOTP SECURITY</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            {{ $needsSetup ? 'Configuration 2FA Obligatoire' : 'Authentification à Deux Facteurs' }}
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            {{ $needsSetup ? 'Scannez le QR code ci-dessous avec votre application d\'authentification (Google/Microsoft Authenticator, Authy, 1Password).' : 'Entrez le code TOTP à 6 chiffres généré par votre application d\'authentification.' }}
        </p>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-start gap-3">
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
                <div class="inline-block p-4 bg-white rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="p-3 bg-slate-100 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 font-mono text-xs text-left">
                    <span class="text-[10px] text-slate-400 uppercase font-sans font-bold block mb-0.5">Clé Secrète Manuelle:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-sm select-all">{{ $setupSecret }}</span>
                </div>

                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-900 text-left text-xs space-y-2">
                    <span class="font-bold text-amber-800 dark:text-amber-300 block">⚠️ Sauvegardez vos 10 codes de secours:</span>
                    <div class="grid grid-cols-2 gap-1.5 font-mono text-[11px] text-amber-900 dark:text-amber-200">
                        @foreach($setupRecoveryCodes as $rcode)
                            <div>{{ $rcode }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="text-left">
                    <x-input-label for="code" value="Code TOTP de confirmation (6 chiffres) *" />
                    <input
                        wire:model="code"
                        id="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        class="w-full px-4 py-3.5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 rounded-xl font-mono font-black text-center text-2xl tracking-widest focus:ring-2 focus:ring-indigo-600 shadow-sm"
                        autofocus
                    >
                </div>
            </div>
        @elseif(!$useRecoveryCode)
            <!-- Standard TOTP Challenge -->
            <div>
                <x-input-label for="code" value="Code Authentificateur TOTP à 6 Chiffres *" />
                <input
                    wire:model="code"
                    id="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    placeholder="123456"
                    class="w-full px-4 py-3.5 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 rounded-xl font-mono font-black text-center text-2xl tracking-widest focus:ring-2 focus:ring-indigo-600 shadow-sm"
                    autofocus
                >
            </div>
        @else
            <!-- Recovery Code Input -->
            <div>
                <x-input-label for="recoveryCode" value="Code de Récupération (Recovery Code) *" />
                <input
                    wire:model="recoveryCode"
                    id="recoveryCode"
                    type="text"
                    placeholder="xxxxxx-xxxxxx"
                    class="w-full px-4 py-3 border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 rounded-xl font-mono font-bold text-center text-sm focus:ring-2 focus:ring-indigo-600 shadow-sm"
                    autofocus
                >
            </div>
        @endif

        <div class="pt-2">
            <x-primary-button>
                <span>Vérifier & Accéder au Dashboard ➔</span>
            </x-primary-button>
        </div>
    </form>

    @if(!$needsSetup)
        <div class="text-center pt-3 border-t border-slate-200 dark:border-slate-800">
            <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-bold">
                {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
            </button>
        </div>
    @endif
</div>
