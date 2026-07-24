<div class="w-full font-sans antialiased text-slate-100">

    <!-- Header Logo & Branding Badge -->
    <div class="text-center mb-6 space-y-2">
        <div class="inline-flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-600/30 border border-indigo-400/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div class="text-left">
                <span class="text-xl font-black text-white tracking-tight block">Insurio Enterprise</span>
                <span class="text-[9px] font-mono font-bold text-indigo-400 uppercase tracking-widest block -mt-1">First-Time Account Activation</span>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-slate-900/90 border border-slate-800/90 rounded-3xl shadow-2xl overflow-hidden p-6 sm:p-8 space-y-6">

        @if($isExpiredOrInvalid)
            <!-- Expired / Invalid Token Screen -->
            <div class="text-center space-y-6 py-6">
                <div class="w-16 h-16 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <div class="space-y-2">
                    <h2 class="text-2xl font-black text-white">Lien d'Activation Expiré ou Invalide</h2>
                    <p class="text-sm text-slate-400 max-w-md mx-auto leading-relaxed">
                        Ce lien d'activation a expiré (validité 24h) ou est invalide. Veuillez contacter votre Administrateur Système pour générer un nouveau lien d'activation.
                    </p>
                </div>

                <div class="pt-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30">
                        Retour à la Connexion ➔
                    </a>
                </div>
            </div>
        @else

            <!-- STEP PROGRESS BAR & STEPPER BADGES -->
            <div class="space-y-3">
                <div class="overflow-hidden h-2 rounded-full bg-slate-950 border border-slate-800/80">
                    <div style="width: {{ ($currentStep / 6) * 100 }}%" class="h-full shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-indigo-500 via-purple-500 to-emerald-400 transition-all duration-500 ease-out"></div>
                </div>
                
                <div class="grid grid-cols-6 gap-1.5 text-center text-[10px] font-mono font-semibold text-slate-400">
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 1 ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">1. Intro</div>
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 2 ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">2. Passe</div>
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 3 ? 'bg-teal-500/10 text-teal-400 border border-teal-500/20 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">3. 2FA</div>
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 4 ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">4. Code</div>
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 5 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">5. Secours</div>
                    <div class="py-1 px-1 rounded-md transition-all {{ $currentStep >= 6 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold' : 'bg-slate-950/40 border border-slate-800/40' }}">6. Fin</div>
                </div>
            </div>

            <!-- STEP 1: WELCOME TO INSURIO -->
            @if($currentStep === 1)
                <div class="space-y-6 animate-fadeIn">
                    <div class="text-center space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-400">
                            <span>👋 BIENVENUE SUR INSURIO</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Activation de votre Compte Professionnel</h2>
                        <p class="text-sm text-slate-400 leading-relaxed max-w-lg mx-auto">
                            Vous avez été accrédité sur la plateforme d'assurance Insurio. Avant de pouvoir accéder à votre espace de travail, veuillez compléter cet assistant de sécurité.
                        </p>
                    </div>

                    <!-- User & Agency Profile Details Card -->
                    <div class="bg-slate-950 p-5 rounded-2xl border border-slate-800/90 space-y-4 font-sans shadow-inner">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <span class="text-slate-500 font-mono uppercase text-[10px] block font-bold">Plateforme Enterprise</span>
                                <span class="text-white font-black text-sm">Insurio SaaS Infrastructure</span>
                            </div>
                            <div>
                                <span class="text-slate-500 font-mono uppercase text-[10px] block font-bold">Agence d'Assurance</span>
                                <span class="text-indigo-400 font-black text-sm">{{ (function_exists('tenant') && tenant() && tenant('name')) ? tenant('name') : 'Insurio Agency' }}</span>
                            </div>
                            <div class="pt-2 border-t border-slate-900">
                                <span class="text-slate-500 font-mono uppercase text-[10px] block font-bold">Nom de l'Utilisateur</span>
                                <span class="text-white font-bold text-sm">{{ $user->name }}</span>
                            </div>
                            <div class="pt-2 border-t border-slate-900">
                                <span class="text-slate-500 font-mono uppercase text-[10px] block font-bold">Rôle & Privilèges</span>
                                <span class="text-teal-400 font-mono font-bold text-xs uppercase">{{ $user->roles->first()?->name ?? 'Membre d\'équipe' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 text-center">
                        <button wire:click="goToStep2" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl shadow-indigo-600/30 cursor-pointer">
                            Commencer l'Activation ➔
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 2: FORCE PASSWORD CHANGE -->
            @if($currentStep === 2)
                <form wire:submit.prevent="saveNewPassword" class="space-y-6 animate-fadeIn">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-xs font-mono font-bold text-amber-400">
                            <span>🔑 ÉTAPE 2 : CHANGEMENT DE MOT DE PASSE OBLIGATOIRE</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Définissez votre Mot de Passe Personnel</h2>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Votre mot de passe temporaire doit être remplacé par un mot de passe fort respectant les exigences de sécurité Insurio.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nouveau Mot de Passe *</label>
                            <input type="password" wire:model.live="new_password" placeholder="••••••••••••"
                                   class="w-full px-4 py-3 text-sm font-medium border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all">
                            @error('new_password') <span class="text-xs font-semibold text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Confirmer le Nouveau Mot de Passe *</label>
                            <input type="password" wire:model.live="new_password_confirmation" placeholder="••••••••••••"
                                   class="w-full px-4 py-3 text-sm font-medium border border-slate-800 bg-slate-950 text-slate-100 placeholder-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>

                    <!-- Live Password Rules Checklist -->
                    <div class="bg-slate-950 p-4.5 rounded-2xl border border-slate-800/90 text-xs space-y-2.5 font-mono">
                        <span class="text-slate-400 font-bold uppercase text-[10px] block tracking-wider">Exigences de Sécurité du Mot de Passe :</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div class="flex items-center gap-2 p-1.5 rounded-lg transition-all {{ strlen($new_password) >= 12 ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-bold' : 'text-slate-500 bg-slate-900/50' }}">
                                <span class="text-xs font-bold">{{ strlen($new_password) >= 12 ? '✓' : '○' }}</span>
                                <span>Au moins 12 caractères</span>
                            </div>
                            <div class="flex items-center gap-2 p-1.5 rounded-lg transition-all {{ preg_match('/[A-Z]/', $new_password) ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-bold' : 'text-slate-500 bg-slate-900/50' }}">
                                <span class="text-xs font-bold">{{ preg_match('/[A-Z]/', $new_password) ? '✓' : '○' }}</span>
                                <span>Une majuscule (A-Z)</span>
                            </div>
                            <div class="flex items-center gap-2 p-1.5 rounded-lg transition-all {{ preg_match('/[a-z]/', $new_password) ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-bold' : 'text-slate-500 bg-slate-900/50' }}">
                                <span class="text-xs font-bold">{{ preg_match('/[a-z]/', $new_password) ? '✓' : '○' }}</span>
                                <span>Une minuscule (a-z)</span>
                            </div>
                            <div class="flex items-center gap-2 p-1.5 rounded-lg transition-all {{ preg_match('/[0-9]/', $new_password) ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-bold' : 'text-slate-500 bg-slate-900/50' }}">
                                <span class="text-xs font-bold">{{ preg_match('/[0-9]/', $new_password) ? '✓' : '○' }}</span>
                                <span>Un chiffre (0-9)</span>
                            </div>
                            <div class="flex items-center gap-2 col-span-1 sm:col-span-2 p-1.5 rounded-lg transition-all {{ preg_match('/[@$!%*?&#^()_+\-=\[\]{};\':"\\|,.<>\/?]/', $new_password) ? 'text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 font-bold' : 'text-slate-500 bg-slate-900/50' }}">
                                <span class="text-xs font-bold">{{ preg_match('/[@$!%*?&#^()_+\-=\[\]{};\':"\\|,.<>\/?]/', $new_password) ? '✓' : '○' }}</span>
                                <span>Un symbole spécial (@, #, $, %, etc.)</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30 cursor-pointer disabled:opacity-50">
                            <span wire:loading.remove wire:target="saveNewPassword">Enregistrer le Nouveau Mot de Passe & Continuer ➔</span>
                            <span wire:loading wire:target="saveNewPassword" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Enregistrement en cours...
                            </span>
                        </button>
                    </div>
                </form>
            @endif

            <!-- STEP 3: SETUP TWO-FACTOR AUTHENTICATION -->
            @if($currentStep === 3)
                <div class="space-y-6 animate-fadeIn text-center">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-mono font-bold text-teal-400">
                            <span>🛡️ ÉTAPE 3 : CONFIGURATION DOUBLE AUTHENTIFICATION (2FA)</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Scannez le QR Code de Sécurité</h2>
                        <p class="text-sm text-slate-400 max-w-md mx-auto">
                            Insurio exige la validation à deux facteurs TOTP sur chaque compte. Scannez le QR code ci-dessous avec votre application d'authentification.
                        </p>
                    </div>

                    <!-- Compatible Apps Badges -->
                    <div class="flex flex-wrap justify-center gap-2 text-[10px] font-mono font-bold text-slate-400">
                        <span class="px-2.5 py-1 rounded-md bg-slate-950 border border-slate-800">Google Authenticator</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-950 border border-slate-800">Microsoft Authenticator</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-950 border border-slate-800">Authy</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-950 border border-slate-800">1Password</span>
                    </div>

                    <!-- QR Code Container -->
                    <div class="bg-white p-4 rounded-2xl inline-block shadow-2xl border-4 border-teal-500/30">
                        <img src="data:image/svg+xml;base64,{{ $qrCodeSvg }}" class="w-44 h-44 mx-auto">
                    </div>

                    <!-- Secret Key Copy Box -->
                    <div class="max-w-md mx-auto bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-1">
                        <span class="text-[10px] font-mono font-bold text-slate-500 uppercase block">Clé Secrète Manuel (Si QR Code illisible) :</span>
                        <div class="font-mono text-base font-black text-teal-400 tracking-wider select-all">
                            {{ chunk_split($secretKey, 4, ' ') }}
                        </div>
                    </div>

                    <div class="pt-2">
                        <button wire:click="goToStep4" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-xl shadow-indigo-600/30 cursor-pointer">
                            J'ai scanné le QR Code / Continuer ➔
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 4: VERIFICATION -->
            @if($currentStep === 4)
                <form wire:submit.prevent="verifyTotpCode" class="space-y-6 animate-fadeIn text-center max-w-md mx-auto">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-400">
                            <span>🔢 ÉTAPE 4 : VÉRIFICATION DU CODE 2FA</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Saisissez le Code à 6 Chiffres</h2>
                        <p class="text-sm text-slate-400">
                            Saisissez le code temporaire généré actuellement par votre application d'authentification.
                        </p>
                    </div>

                    <div>
                        <input type="text" wire:model.live="totpCode" maxlength="6" autofocus placeholder="123456"
                               class="w-full text-center text-3xl font-mono font-black tracking-widest px-4 py-4 border-2 border-indigo-500/50 bg-slate-950 text-indigo-400 rounded-2xl focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all">
                        @error('totpCode') <span class="text-xs font-semibold text-rose-500 mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex justify-between gap-3">
                        <button type="button" wire:click="goToStep(3)" class="px-5 py-3 border border-slate-800 rounded-xl text-slate-400 font-bold text-xs hover:bg-slate-800 transition-all">
                            ↵ Retour au Scan
                        </button>
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-indigo-600/30 cursor-pointer">
                            Valider le Code 2FA ➔
                        </button>
                    </div>
                </form>
            @endif

            <!-- STEP 5: RECOVERY CODES -->
            @if($currentStep === 5)
                <div class="space-y-6 animate-fadeIn">
                    <div class="space-y-2 text-center">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-mono font-bold text-emerald-400">
                            <span>🔑 ÉTAPE 5 : CODES DE RÉCUPÉRATION DE SECOURS</span>
                        </div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Sauvegardez vos Codes de Secours</h2>
                        <p class="text-sm text-slate-400 max-w-lg mx-auto">
                            Ces 10 codes à usage unique vous permettront d'accéder à votre compte en cas de perte de votre téléphone 2FA. Conservez-les en lieu sûr.
                        </p>
                    </div>

                    <!-- 10 Recovery Codes Grid -->
                    <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 grid grid-cols-2 gap-3 font-mono text-xs text-center">
                        @foreach($recoveryCodes as $code)
                            <div class="bg-slate-900 border border-slate-800 p-2.5 rounded-xl font-bold text-emerald-400 tracking-wider select-all">
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Mandatory Checkbox Confirmation -->
                    <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 flex items-start gap-3">
                        <input type="checkbox" id="savedRecovery" wire:model.live="savedRecoveryCodes" class="mt-0.5 w-5 h-5 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <label for="savedRecovery" class="text-xs text-slate-300 font-semibold cursor-pointer select-none leading-relaxed">
                            J'atteste avoir téléchargé, imprimé ou sauvegardé mes 10 codes de récupération de secours dans un coffre-fort numérique sécurisé.
                        </label>
                    </div>
                    @error('savedRecoveryCodes') <span class="text-xs font-semibold text-rose-500 block">{{ $message }}</span> @enderror

                    <div class="pt-2 flex justify-between gap-3">
                        <button type="button" onclick="navigator.clipboard.writeText('{{ implode("\n", $recoveryCodes) }}'); alert('Codes copiés dans le presse-papier !');" class="px-4 py-3 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs rounded-xl transition-all">
                            📋 Copier les Codes
                        </button>

                        <button wire:click="completeRecoveryStep" class="flex-1 inline-flex items-center justify-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-emerald-600/30 cursor-pointer">
                            Confirmer & Finaliser L'Activation ➔
                        </button>
                    </div>
                </div>
            @endif

            <!-- STEP 6: ACTIVATION COMPLETE -->
            @if($currentStep === 6)
                <div class="space-y-8 animate-fadeIn text-center py-4">
                    <div class="w-20 h-20 rounded-full bg-emerald-500/10 border-2 border-emerald-500/30 text-emerald-400 flex items-center justify-center mx-auto shadow-xl shadow-emerald-500/20">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-3xl font-black text-white tracking-tight">Compte Activé avec Succès !</h2>
                        <p class="text-sm text-slate-400 max-w-md mx-auto leading-relaxed">
                            Votre profil d'accréditation et vos paramètres de sécurité 2FA sont entièrement validés et opérationnels.
                        </p>
                    </div>

                    <!-- Summary Checklist Badges -->
                    <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 text-xs font-semibold text-left max-w-md mx-auto space-y-3">
                        <div class="flex items-center justify-between text-emerald-400">
                            <span>✅ Compte Utilisateur Activé</span>
                            <span class="font-mono text-[10px] text-slate-500">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-emerald-400">
                            <span>✅ Mot de Passe Personnel Sécurisé</span>
                            <span class="font-mono text-[10px] text-slate-500">Mot de passe fort</span>
                        </div>
                        <div class="flex items-center justify-between text-emerald-400">
                            <span>✅ Double Authentification (2FA) Activée</span>
                            <span class="font-mono text-[10px] text-slate-500">TOTP Hardened</span>
                        </div>
                        <div class="flex items-center justify-between text-emerald-400">
                            <span>✅ Codes de Secours Générés & Confirmés</span>
                            <span class="font-mono text-[10px] text-slate-500">10 Codes</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button wire:click="finishWizard" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-sm uppercase tracking-wider rounded-xl transition-all shadow-xl shadow-emerald-500/30 cursor-pointer">
                            Accéder au Tableau de Bord ➔
                        </button>
                    </div>
                </div>
            @endif

        @endif

    </div>
</div>
