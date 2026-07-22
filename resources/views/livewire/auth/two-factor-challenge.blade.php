<div class="space-y-6">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
            {{ $needsSetup ? 'Configuration 2FA Obligatoire' : 'Authentification à Deux Facteurs' }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ $needsSetup ? 'Veuillez scanner le QR code ci-dessous avec votre application TOTP.' : 'Entrez le code à 6 chiffres généré par votre application d\'authentification.' }}
        </p>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="p-3 rounded-md bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-xs font-medium text-red-700 dark:text-red-300">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="verify" class="space-y-4 text-sm">
        @if($needsSetup)
            <!-- Inline First-Time Setup -->
            <div class="text-center space-y-3">
                <div class="inline-block p-2 bg-white rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="p-2 bg-gray-50 dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 font-mono text-xs text-left">
                    <span class="text-[10px] text-gray-400 uppercase font-sans font-bold block">Clé Secrète Manuelle:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400 select-all">{{ $setupSecret }}</span>
                </div>

                <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded border border-amber-200 dark:border-amber-900 text-left text-xs space-y-1">
                    <span class="font-bold text-amber-800 dark:text-amber-300 block">⚠️ Sauvegardez vos 10 codes de secours:</span>
                    <div class="grid grid-cols-2 gap-1 font-mono text-[11px] text-amber-900 dark:text-amber-200">
                        @foreach($setupRecoveryCodes as $rcode)
                            <div>{{ $rcode }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="text-left">
                    <label class="block font-medium text-xs text-gray-700 dark:text-gray-300 mb-1">Code TOTP (6 chiffres) *</label>
                    <input
                        wire:model="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md font-mono font-bold text-center text-lg tracking-widest focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        autofocus
                    >
                </div>
            </div>
        @elseif(!$useRecoveryCode)
            <!-- Standard TOTP Challenge -->
            <div>
                <label class="block font-medium text-xs text-gray-700 dark:text-gray-300 mb-1">Code Authentificateur TOTP (6 Chiffres) *</label>
                <input
                    wire:model="code"
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    placeholder="123456"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md font-mono font-bold text-center text-lg tracking-widest focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    autofocus
                >
            </div>
        @else
            <!-- Recovery Code Input -->
            <div>
                <label class="block font-medium text-xs text-gray-700 dark:text-gray-300 mb-1">Code de Récupération (Recovery Code) *</label>
                <input
                    wire:model="recoveryCode"
                    type="text"
                    placeholder="xxxxxx-xxxxxx"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md font-mono font-bold text-center text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    autofocus
                >
            </div>
        @endif

        <div class="pt-1">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
            >
                <span wire:loading.remove wire:target="verify">Valider & Se Connecter</span>
                <span wire:loading wire:target="verify">Vérification...</span>
            </button>
        </div>
    </form>

    @if(!$needsSetup)
        <div class="text-center pt-2 border-t border-gray-100 dark:border-gray-700">
            <button wire:click="toggleRecoveryMode" type="button" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                {{ $useRecoveryCode ? '← Utiliser le code TOTP Authentificateur' : '🔑 Utiliser un code de récupération' }}
            </button>
        </div>
    @endif
</div>
