<div class="w-full sm:max-w-md space-y-6">
    <!-- Header Title & Tenant Logo -->
    <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 mb-1 border border-indigo-100 dark:border-indigo-900/50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">
            {{ $needsSetup ? 'Configuration 2FA Obligatoire' : 'Authentification à Deux Facteurs' }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ $needsSetup ? 'Veuillez scanner le QR code ci-dessous avec votre application TOTP.' : 'Entrez le code à 6 chiffres généré par votre application d\'authentification.' }}
        </p>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="p-3.5 rounded-lg bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-xs font-semibold text-red-700 dark:text-red-300 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errorMessage }}</span>
        </div>
    @endif

    <form wire:submit.prevent="verify" class="space-y-5 text-sm">
        @if($needsSetup)
            <!-- Inline First-Time Setup -->
            <div class="text-center space-y-4">
                <div class="inline-block p-3 bg-white rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="p-2.5 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 font-mono text-xs text-left">
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider block font-sans font-bold mb-0.5">Clé Secrète Manuelle:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 text-sm select-all">{{ $setupSecret }}</span>
                </div>

                <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-lg border border-amber-200 dark:border-amber-900 text-left text-xs space-y-1.5">
                    <span class="font-bold text-amber-800 dark:text-amber-300 block">⚠️ Sauvegardez vos 10 codes de secours:</span>
                    <div class="grid grid-cols-2 gap-1 font-mono text-[11px] text-amber-900 dark:text-amber-200">
                        @foreach($setupRecoveryCodes as $rcode)
                            <div>{{ $rcode }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="text-left">
                    <label class="block font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1">Code TOTP de confirmation (6 chiffres) *</label>
                    <input
                        wire:model="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg font-mono font-bold text-center text-xl tracking-widest focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        autofocus
                    >
                </div>
            </div>
        @elseif(!$useRecoveryCode)
            <!-- Standard TOTP Challenge -->
            <div>
                <label class="block font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1">Code Authentificateur TOTP (6 Chiffres) *</label>
                <input
                    wire:model="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    placeholder="123456"
                    class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg font-mono font-bold text-center text-xl tracking-widest focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    autofocus
                >
            </div>
        @else
            <!-- Recovery Code Input -->
            <div>
                <label class="block font-semibold text-xs text-gray-700 dark:text-gray-300 mb-1">Code de Récupération (Recovery Code) *</label>
                <input
                    wire:model="recoveryCode"
                    type="text"
                    placeholder="xxxxxx-xxxxxx"
                    class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg font-mono font-bold text-center text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    autofocus
                >
            </div>
        @endif

        <div class="pt-2">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
            >
                <span wire:loading.remove wire:target="verify">Valider & Continuer ➔</span>
                <span wire:loading wire:target="verify">Vérification...</span>
            </button>
        </div>
    </form>

    @if(!$needsSetup)
        <div class="text-center pt-1 border-t border-gray-100 dark:border-gray-700">
            <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
            </button>
        </div>
    @endif
</div>
