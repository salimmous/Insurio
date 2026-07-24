<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">

    @if($isExpiredOrInvalid)
        <!-- Expired / Invalid Token Screen -->
        <div class="text-center space-y-6 py-4">
            <div class="w-16 h-16 rounded-full bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center mx-auto shadow-xs">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <div class="space-y-2">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Lien d'Activation Expiré</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto leading-relaxed font-medium">
                    Ce lien d'activation a expiré (validité 24h) ou est invalide. Veuillez contacter votre Administrateur Système pour générer un nouveau lien.
                </p>
            </div>

            <div class="pt-4">
                <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all">
                    <span>Retour à la Connexion ➔</span>
                </a>
            </div>
        </div>
    @else

        <!-- PREMIUM NUMBERED CIRCLE STEPPER -->
        <div class="relative px-1 pb-2">
            <div class="flex items-center justify-between relative">
                <!-- Progress Line Background -->
                <div class="absolute top-4 left-4 right-4 h-0.5 bg-slate-200 z-0"></div>
                <div class="absolute top-4 left-4 h-0.5 bg-indigo-600 transition-all duration-500 z-0" style="width: {{ (($currentStep - 1) / 5) * 100 }}%"></div>

                @php
                    $steps = [
                        1 => 'Accueil',
                        2 => 'Mot de Passe',
                        3 => 'Scan 2FA',
                        4 => 'Validation',
                        5 => 'Secours',
                        6 => 'Finalisé'
                    ];
                @endphp

                @foreach($steps as $stepNum => $stepLabel)
                    <div class="relative z-10 flex flex-col items-center gap-1.5 cursor-pointer" wire:click="goToStep({{ $stepNum }})">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all duration-300 {{ $currentStep === $stepNum ? 'bg-indigo-600 text-white ring-4 ring-indigo-100 shadow-md shadow-indigo-600/30 scale-110' : ($currentStep > $stepNum ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-400 border border-slate-300') }}">
                            @if($currentStep > $stepNum)
                                <svg class="w-4 h-4 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span class="text-[10px] font-extrabold font-mono tracking-tight hidden sm:block {{ $currentStep === $stepNum ? 'text-indigo-600' : ($currentStep > $stepNum ? 'text-emerald-700' : 'text-slate-400') }}">
                            {{ $stepLabel }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- STEP 1: WELCOME TO INSURIO -->
        @if($currentStep === 1)
            <div class="space-y-6 text-left animate-fadeIn">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
                        <span>👋 BIENVENUE SUR INSURIO</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Activation de votre Compte
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed">
                        Vous avez été accrédité sur la plateforme d'assurance. Veuillez compléter cet assistant pour sécuriser votre compte.
                    </p>
                </div>

                <!-- User & Agency Profile Details Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-mono uppercase text-[10px] block font-bold">Plateforme Enterprise</span>
                            <span class="text-slate-900 font-black text-sm">Insurio SaaS Infrastructure</span>
                        </div>
                        <div>
                            <span class="text-slate-400 font-mono uppercase text-[10px] block font-bold">Agence d'Assurance</span>
                            <span class="text-indigo-600 font-black text-sm">{{ (function_exists('tenant') && tenant() && tenant('name')) ? tenant('name') : 'Insurio Agency' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200">
                            <span class="text-slate-400 font-mono uppercase text-[10px] block font-bold">Nom de l'Utilisateur</span>
                            <span class="text-slate-900 font-bold text-sm">{{ $user->name }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-200">
                            <span class="text-slate-400 font-mono uppercase text-[10px] block font-bold">Rôle & Privilèges</span>
                            <span class="text-emerald-700 font-mono font-bold text-xs uppercase">{{ $user->roles->first()?->name ?? 'Membre d\'équipe' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button wire:click="goToStep2" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        <span>Commencer l'Activation ➔</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- STEP 2: FORCE PASSWORD CHANGE -->
        @if($currentStep === 2)
            <form wire:submit.prevent="saveNewPassword" class="space-y-5 text-left animate-fadeIn">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-xs font-mono font-bold text-amber-700">
                        <span>🔑 ÉTAPE 2 : CHANGEMENT DE MOT DE PASSE</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Définissez votre Mot de Passe
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Votre mot de passe temporaire doit être remplacé par un mot de passe fort respectant les exigences de sécurité Insurio.
                    </p>
                </div>

                <div class="space-y-4">
                    <!-- New Password Input with Show/Hide Toggle -->
                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Nouveau Mot de Passe *</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model.live="new_password" placeholder="••••••••••••"
                                   class="w-full h-12 px-4 pr-11 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400">
                            <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.98-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.177-6.177a3 3 0 004.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('new_password') <span class="text-xs font-semibold text-rose-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirm Password Input with Show/Hide Toggle -->
                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">Confirmer le Nouveau Mot de Passe *</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model.live="new_password_confirmation" placeholder="••••••••••••"
                                   class="w-full h-12 px-4 pr-11 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400">
                            <button type="button" @click="show = !show" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 013.98-.863c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-6.177-6.177a3 3 0 004.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- REDESIGNED TWO-COLUMN PASSWORD REQUIREMENTS -->
                <div class="bg-slate-50 p-4.5 rounded-2xl border border-slate-200 space-y-2.5">
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 block">Exigences de Sécurité :</span>
                    <div class="grid grid-cols-2 gap-2.5 text-xs font-semibold">
                        <div class="flex items-center gap-2 {{ strlen($new_password) >= 12 ? 'text-emerald-700 font-extrabold' : 'text-slate-400' }}">
                            <svg class="w-4 h-4 shrink-0 {{ strlen($new_password) >= 12 ? 'text-emerald-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Au moins 12 caractères</span>
                        </div>
                        <div class="flex items-center gap-2 {{ preg_match('/[A-Z]/', $new_password) ? 'text-emerald-700 font-extrabold' : 'text-slate-400' }}">
                            <svg class="w-4 h-4 shrink-0 {{ preg_match('/[A-Z]/', $new_password) ? 'text-emerald-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Une majuscule (A-Z)</span>
                        </div>
                        <div class="flex items-center gap-2 {{ preg_match('/[a-z]/', $new_password) ? 'text-emerald-700 font-extrabold' : 'text-slate-400' }}">
                            <svg class="w-4 h-4 shrink-0 {{ preg_match('/[a-z]/', $new_password) ? 'text-emerald-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Une minuscule (a-z)</span>
                        </div>
                        <div class="flex items-center gap-2 {{ preg_match('/[0-9]/', $new_password) ? 'text-emerald-700 font-extrabold' : 'text-slate-400' }}">
                            <svg class="w-4 h-4 shrink-0 {{ preg_match('/[0-9]/', $new_password) ? 'text-emerald-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Un chiffre (0-9)</span>
                        </div>
                        <div class="flex items-center gap-2 col-span-2 {{ preg_match('/[@$!%*?&#^()_+\-=\[\]{};\':"\\|,.<>\/?]/', $new_password) ? 'text-emerald-700 font-extrabold' : 'text-slate-400' }}">
                            <svg class="w-4 h-4 shrink-0 {{ preg_match('/[@$!%*?&#^()_+\-=\[\]{};\':"\\|,.<>\/?]/', $new_password) ? 'text-emerald-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Un symbole spécial (@, #, $, %, etc.)</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        <span>Enregistrer le Mot de Passe & Continuer ➔</span>
                    </button>
                </div>
            </form>
        @endif

        <!-- STEP 3: SETUP TWO-FACTOR AUTHENTICATION -->
        @if($currentStep === 3)
            <div class="space-y-6 text-center animate-fadeIn">
                <div class="space-y-2 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-mono font-bold text-emerald-700">
                        <span>🛡️ ÉTAPE 3 : CONFIGURATION 2FA</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Scannez le QR Code de Sécurité
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Scannez le QR code ci-dessous avec votre application d'authentification (Google/Microsoft Authenticator, Authy, 1Password).
                    </p>
                </div>

                <!-- QR Code Container -->
                <div class="bg-white p-4 rounded-2xl inline-block shadow-md border border-slate-200">
                    <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" class="w-48 h-48 mx-auto">
                </div>

                <!-- Secret Key Copy Box -->
                <div class="max-w-md mx-auto bg-slate-50 p-4 rounded-xl border border-slate-200 text-left space-y-1">
                    <span class="text-[10px] font-mono font-bold text-slate-500 uppercase block">Clé Secrète Manuel (Si QR Code illisible) :</span>
                    <div class="font-mono text-base font-black text-indigo-600 tracking-wider select-all">
                        {{ chunk_split($secretKey, 4, ' ') }}
                    </div>
                </div>

                <div class="pt-2">
                    <button wire:click="goToStep4" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        <span>J'ai scanné le QR Code / Continuer ➔</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- STEP 4: VERIFICATION -->
        @if($currentStep === 4)
            <form wire:submit.prevent="verifyTotpCode" class="space-y-6 text-left animate-fadeIn">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-mono font-bold text-indigo-700">
                        <span>🔢 ÉTAPE 4 : VÉRIFICATION DU CODE 2FA</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Saisissez le Code à 6 Chiffres
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Saisissez le code temporaire généré actuellement par votre application d'authentification.
                    </p>
                </div>

                <div class="space-y-1.5">
                    <label for="totpCode" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                        Code Authentificateur TOTP (6 chiffres) *
                    </label>
                    <input type="text" wire:model.live="totpCode" id="totpCode" maxlength="6" autofocus placeholder="123456"
                           class="w-full text-center text-3xl font-mono font-black tracking-widest px-4 py-4 border border-slate-300 bg-white text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-600 shadow-xs outline-none">
                    @error('totpCode') <span class="text-xs font-semibold text-rose-600 mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 flex justify-between gap-3">
                    <button type="button" wire:click="goToStep(3)" class="px-5 py-3.5 border border-slate-300 rounded-xl text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all">
                        ↵ Retour
                    </button>
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        <span>Valider le Code 2FA ➔</span>
                    </button>
                </div>
            </form>
        @endif

        <!-- STEP 5: RECOVERY CODES -->
        @if($currentStep === 5)
            <div class="space-y-6 text-left animate-fadeIn">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-xs font-mono font-bold text-emerald-700">
                        <span>🔑 ÉTAPE 5 : CODES DE SECOURS</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Sauvegardez vos Codes de Secours
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Ces 10 codes à usage unique vous permettront d'accéder à votre compte en cas de perte de votre téléphone 2FA. Conservez-les en lieu sûr.
                    </p>
                </div>

                <!-- 10 Recovery Codes Grid -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 grid grid-cols-2 gap-2.5 font-mono text-xs text-center">
                    @foreach($recoveryCodes as $code)
                        <div class="bg-white border border-slate-200 p-2.5 rounded-xl font-bold text-slate-900 tracking-wider select-all shadow-xs">
                            {{ $code }}
                        </div>
                    @endforeach
                </div>

                <!-- Mandatory Checkbox Confirmation -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex items-start gap-3">
                    <input type="checkbox" id="savedRecovery" wire:model.live="savedRecoveryCodes" class="mt-0.5 w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                    <label for="savedRecovery" class="text-xs text-slate-700 font-semibold cursor-pointer select-none leading-relaxed">
                        J'atteste avoir téléchargé, imprimé ou sauvegardé mes 10 codes de récupération de secours dans un lieu sécurisé.
                    </label>
                </div>
                @error('savedRecoveryCodes') <span class="text-xs font-semibold text-rose-600 block">{{ $message }}</span> @enderror

                <div class="pt-2 flex justify-between gap-3">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ implode("\n", $recoveryCodes) }}'); alert('Codes copiés dans le presse-papier !');" class="px-4 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all border border-slate-300">
                        📋 Copier
                    </button>

                    <button wire:click="completeRecoveryStep" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-600/30 transition-all cursor-pointer">
                        <span>Confirmer & Finaliser ➔</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- STEP 6: ACTIVATION COMPLETE -->
        @if($currentStep === 6)
            <div class="space-y-6 text-center py-2 animate-fadeIn">
                <div class="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mx-auto shadow-sm">
                    <svg class="w-8 h-8 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Compte Activé avec Succès !</h2>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium leading-relaxed max-w-md mx-auto">
                        Votre profil d'accréditation et vos paramètres de sécurité 2FA sont entièrement validés et opérationnels.
                    </p>
                </div>

                <!-- Summary Checklist Badges -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 text-xs font-semibold text-left max-w-md mx-auto space-y-2.5">
                    <div class="flex items-center justify-between text-emerald-700">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Compte Utilisateur Activé</span>
                        <span class="font-mono text-[10px] text-slate-400">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-emerald-700">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Mot de Passe Sécurisé</span>
                        <span class="font-mono text-[10px] text-slate-400">Mot de passe fort</span>
                    </div>
                    <div class="flex items-center justify-between text-emerald-700">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Double Authentification (2FA)</span>
                        <span class="font-mono text-[10px] text-slate-400">TOTP Hardened</span>
                    </div>
                    <div class="flex items-center justify-between text-emerald-700">
                        <span class="flex items-center gap-1.5"><svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Codes de Secours Générés</span>
                        <span class="font-mono text-[10px] text-slate-400">10 Codes</span>
                    </div>
                </div>

                <div class="pt-4">
                    <button wire:click="finishWizard" class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 transition-all cursor-pointer">
                        <span>Accéder au Tableau de Bord ➔</span>
                    </button>
                </div>
            </div>
        @endif

    @endif

</div>
