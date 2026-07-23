@php
    $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
        ? tenant('name') 
        : (\App\Models\Setting::get('agency_name') ?: 'Insurio Agency');
    
    $agencyLogo = (function_exists('tenant') && tenant() && tenant('logo_path')) 
        ? asset('storage/' . tenant('logo_path')) 
        : null;
@endphp

<div class="space-y-6">
    @if($isExpiredOrInvalid)
        <!-- Expired or Invalid Link Alert -->
        <div class="p-6 rounded-3xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-center space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-600/20 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center font-bold text-2xl">
                ⚠️
            </div>
            <h3 class="font-black text-xl text-rose-950 dark:text-rose-200">Lien d'activation expiré ou invalide</h3>
            <p class="text-xs text-rose-700 dark:text-rose-300 leading-relaxed">
                Ce lien d'invitation n'est plus valide ou a expiré après 48 heures. Veuillez contacter l'administrateur de votre agence (<strong>{{ $agencyName }}</strong>) pour demander un nouvel envoi d'invitation.
            </p>
            <div class="pt-2">
                <a href="{{ route('login') }}" class="inline-block bg-slate-900 text-white font-bold text-xs px-6 py-3 rounded-xl hover:bg-black transition">
                    Retour à la Connexion ➔
                </a>
            </div>
        </div>
    @else
        <!-- Header -->
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400">
                <span>🚀 ACTIVATION COMPTE EMPLOYÉ</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                Bienvenue {{ $employe ? $employe->prenom : '' }} {{ $employe ? $employe->nom : '' }} !
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Activez votre compte pour l'agence <strong>{{ $agencyName }}</strong> en définissant votre mot de passe et votre double authentification 2FA.
            </p>
        </div>

        @if($errorMessage)
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-start gap-3">
                <span class="text-rose-500 text-base">⚠️</span>
                <span>{{ $errorMessage }}</span>
            </div>
        @endif

        <form wire:submit.prevent="activate" class="space-y-6">
            
            <!-- STEP 1: Create Password -->
            <div class="space-y-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    1. Définir votre Mot de Passe Professionnel
                </h3>

                <div>
                    <x-input-label for="password" value="Nouveau Mot de Passe *" />
                    <x-text-input wire:model="password" id="password" type="password" required placeholder="••••••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmer le Mot de Passe *" />
                    <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password" required placeholder="••••••••••••" />
                </div>
            </div>

            <!-- STEP 2: Setup Google Authenticator 2FA -->
            <div class="space-y-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <h3 class="text-xs font-mono font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    2. Configuration Google Authenticator (2FA Obligatoire)
                </h3>

                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Scannez ce QR Code avec l'application <strong>Google Authenticator</strong> ou <strong>Microsoft Authenticator</strong> sur votre téléphone portable.
                </p>

                <div class="text-center space-y-3">
                    <div class="inline-block p-3 bg-white rounded-2xl border border-slate-200 shadow-md">
                        {!! $qrCodeSvg !!}
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 text-left">
                        <span class="text-[10px] font-mono text-slate-400 uppercase font-bold block mb-0.5">Clé Secrète Manuelle:</span>
                        <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 text-xs select-all">{{ $secret }}</span>
                    </div>

                    <!-- Recovery Codes Notification -->
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-900 text-left text-xs space-y-2">
                        <span class="font-bold text-amber-800 dark:text-amber-300 block">🔑 Vos 10 Codes de Secours (À conserver précieusement):</span>
                        <div class="grid grid-cols-2 gap-1 font-mono text-[10px] text-amber-900 dark:text-amber-200">
                            @foreach($recoveryCodes as $rcode)
                                <div>{{ $rcode }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <x-input-label for="totpCode" value="Code de vérification 2FA (6 chiffres) *" />
                    <input
                        wire:model="totpCode"
                        id="totpCode"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        class="w-full px-4 py-3.5 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 rounded-xl font-mono font-black text-center text-2xl tracking-widest focus:ring-2 focus:ring-indigo-600 shadow-sm"
                        required
                    >
                    <x-input-error :messages="$errors->get('totpCode')" class="mt-1" />
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <x-primary-button>
                    <span>Activer Mon Compte Employé & Accéder au Dashboard ➔</span>
                </x-primary-button>
            </div>
        </form>
    @endif
</div>
