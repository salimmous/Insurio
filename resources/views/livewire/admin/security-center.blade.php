<div class="p-6 space-y-6 font-sans">
    <!-- Header Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Paramètres de Sécurité & 2FA Enterprise</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                    TOTP HARDENED SECURITY
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Authentification à deux facteurs TOTP locale, codes de secours et gestion des appareils de confiance.</p>
        </div>
    </div>

    <!-- Security Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- 2FA Status Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Statut Authentification 2FA</span>
                @if($is2faEnabled)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                        <svg class="w-4 h-4 text-emerald-700 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Protégé (2FA Actif)</span>
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                        <svg class="w-4 h-4 text-rose-700 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Non Protégé (2FA Inactif)</span>
                    </span>
                @endif
                <span class="text-[10px] text-slate-400 block pt-1">
                    {{ $is2faEnabled ? 'Confirmé le ' . $user->two_factor_confirmed_at->format('d/m/Y H:i') : 'Recommandé d\'activer immédiatement.' }}
                </span>
            </div>
        </div>

        <!-- Security Score Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Score de Sécurité Compte</span>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black font-mono {{ $securityScore >= 80 ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $securityScore }}/100
                    </span>
                    <div class="w-24 bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="h-full {{ $securityScore >= 80 ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ $securityScore }}%"></div>
                    </div>
                </div>
                <span class="text-[10px] text-slate-400 block">Basé sur 2FA, mot de passe et appareils</span>
            </div>
        </div>

        <!-- Device & Last Login Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Dernière Connexion</span>
                <span class="text-xs font-bold text-slate-800 font-mono block">
                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Récemment' }}
                </span>
                <span class="text-[10px] text-slate-400 block">IP: {{ $user->last_login_ip ?? request()->ip() }}</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 text-xs font-bold gap-6 overflow-x-auto pb-2">
        <button wire:click="$set('activeTab', 'security')" class="pb-2 transition inline-flex items-center gap-2 {{ $activeTab === 'security' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Mon Authentification 2FA</span>
        </button>
        <button wire:click="$set('activeTab', 'admin_2fa')" class="pb-2 transition inline-flex items-center gap-2 {{ $activeTab === 'admin_2fa' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>Supervision 2FA Utilisateurs & Politiques</span>
        </button>
        <button wire:click="$set('activeTab', 'audit_logs')" class="pb-2 transition inline-flex items-center gap-2 {{ $activeTab === 'audit_logs' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Journal d'Audit de Sécurité</span>
        </button>
    </div>

    <!-- TAB 1: 2FA SETUP & SECURITY -->
    @if($activeTab === 'security')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left Panel: 2FA Activation -->
            <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 space-y-6 shadow-xs">
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h3 class="font-black text-base text-slate-900">Authentification TOTP (Google / Microsoft Authenticator)</h3>
                        <p class="text-xs text-slate-500">Protection locale hors-ligne sans SMS ni dépendance externe.</p>
                    </div>

                    @if($is2faEnabled)
                        <button wire:click="disable2fa" onclick="confirm('Désactiver la double authentification ?') || event.stopImmediatePropagation()" class="px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-100">
                            Désactiver 2FA
                        </button>
                    @else
                        <button wire:click="start2faSetup" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Activer le 2FA TOTP</span>
                        </button>
                    @endif
                </div>

                <!-- Compatible Authenticator Applications -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <span class="text-xs font-bold text-slate-800 block">Applications Compatibles (Scan QR Code):</span>
                    <div class="flex flex-wrap gap-2 text-[11px] font-semibold text-slate-600">
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Google Authenticator
                        </span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Microsoft Authenticator
                        </span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Authy
                        </span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            1Password
                        </span>
                    </div>
                </div>

                <!-- Recovery Codes Section -->
                @if($is2faEnabled)
                    <div class="space-y-4 pt-4 border-t border-slate-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Codes de Récupération (Recovery Codes)</h4>
                                <p class="text-xs text-slate-500">Chaque code ne peut être utilisé qu'une seule fois en cas de perte de téléphone.</p>
                            </div>
                            <button wire:click="regenerateRecoveryCodes" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold">
                                Régénérer les Codes
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2 font-mono text-xs bg-slate-900 text-emerald-400 p-4 rounded-xl">
                            @foreach($decryptedCodes as $c)
                                <div class="p-1">key: {{ $c }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Panel: Trusted Devices -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs">
                <h3 class="font-black text-base text-slate-900 border-b pb-3">Appareils de Confiance (30 Jours)</h3>
                <div class="space-y-3 text-xs">
                    @forelse($trustedDevices as $dev)
                        <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-slate-800 block">{{ $dev->device_name }}</span>
                                <span class="text-[10px] text-slate-400 block">IP: {{ $dev->ip_address }}</span>
                                <span class="text-[10px] text-emerald-600 block">Expire le: {{ $dev->expires_at->format('d/m/Y') }}</span>
                            </div>
                            <button wire:click="removeTrustedDevice({{ $dev->id }})" class="text-rose-600 hover:underline font-bold text-[11px]">Révoker</button>
                        </div>
                    @empty
                        <div class="text-slate-400 text-xs py-4 text-center">
                            Aucun appareil mémorisé. Le code 2FA sera demandé à chaque connexion.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    <!-- TAB 2: SUPER ADMIN USER 2FA SUPERVISION -->
    @elseif($activeTab === 'admin_2fa')
        <div class="space-y-6">
            <!-- Force 2FA Policy Settings -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs">
                <h3 class="font-black text-base text-slate-900 border-b pb-3">Politiques d'Obligation 2FA par Rôle</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-xs font-bold text-slate-700">
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50 cursor-pointer">
                        <input type="checkbox" wire:model="force_2fa_all" class="rounded text-indigo-600">
                        <span>Obliger pour TOUS les Utilisateurs</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50 cursor-pointer">
                        <input type="checkbox" wire:model="force_2fa_admins" class="rounded text-indigo-600">
                        <span>Administrateurs d'Agence</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50 cursor-pointer">
                        <input type="checkbox" wire:model="force_2fa_finance" class="rounded text-indigo-600">
                        <span>Utilisateurs Finance & Caisses</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50 cursor-pointer">
                        <input type="checkbox" wire:model="force_2fa_managers" class="rounded text-indigo-600">
                        <span>Managers & Chefs d'Équipe</span>
                    </label>
                </div>
                <div class="flex justify-end pt-2">
                    <button wire:click="saveForce2faPolicies" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md">Enregistrer les Politiques</button>
                </div>
            </div>

            <!-- Users 2FA Status Supervision Table -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-left">
                        <tr>
                            <th class="px-6 py-3.5">Utilisateur</th>
                            <th class="px-6 py-3.5">Rôle</th>
                            <th class="px-6 py-3.5">Statut 2FA</th>
                            <th class="px-6 py-3.5">Dernière Validation</th>
                            <th class="px-6 py-3.5">Appareils de Confiance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800 font-medium">
                        @foreach($allUsersSecurity as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-900 block">{{ $u->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $u->email }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-600">
                                    {{ $u->getRoleNames()->first() ?? 'Utilisateur' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($u->two_factor_confirmed_at)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <svg class="w-3 h-3 text-emerald-700 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            2FA Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                            <svg class="w-3 h-3 text-rose-700 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            2FA Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-[11px]">
                                    {{ $u->two_factor_confirmed_at ? $u->two_factor_confirmed_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700">
                                    {{ $u->trusted_devices_count }} appareils
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <!-- TAB 3: SECURITY AUDIT LOGS -->
    @elseif($activeTab === 'audit_logs')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs">
            <h3 class="font-black text-base text-slate-900 border-b pb-4">Journal d'Événements de Sécurité 2FA</h3>
            <div class="space-y-3 font-mono text-xs">
                @foreach($auditLogs as $log)
                    <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-indigo-600 block">[{{ $log->created_at->format('d/m/Y H:i:s') }}] {{ $log->event }}</span>
                            <span class="text-slate-500 text-[10px] block">IP: {{ $log->ip_address }} • Pays: {{ $log->country }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400">{{ Str::limit($log->user_agent, 40) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modal: 2FA Setup Step-by-Step -->
    @if($showSetupModal)
        <div class="fixed inset-0 bg-slate-900/60 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-6 shadow-2xl">
                @if($setupStep === 1)
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-black text-slate-900">Étape 1: Scannez le QR Code TOTP</h3>
                        <button wire:click="closeSetupModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                    </div>

                    <div class="text-center space-y-4">
                        <div class="inline-block p-4 bg-white rounded-2xl border border-slate-200 shadow-md">
                            {!! $qrCodeSvg !!}
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl border font-mono text-xs">
                            <span class="text-[10px] text-slate-400 block font-sans">Clé Secrète Manuelle:</span>
                            <span class="font-bold text-indigo-600 text-sm select-all">{{ $setupSecret }}</span>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 text-xs mb-1">Entrez le code à 6 chiffres généré par votre application *</label>
                            <input type="text" maxlength="6" wire:model="verificationCode" placeholder="ex: 123456" class="w-full border border-slate-300 rounded-xl p-3 font-mono font-black text-center text-xl tracking-widest">
                            @error('verificationCode') <span class="text-rose-500 text-xs font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <label class="flex items-center justify-center gap-2 text-xs font-bold text-slate-700 pt-2">
                            <input type="checkbox" wire:model="trustCurrentDevice" class="rounded text-indigo-600">
                            <span>Faire confiance à cet appareil pendant 30 jours</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeSetupModal" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 font-bold text-xs">Annuler</button>
                        <button type="button" wire:click="confirm2faSetup" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 shadow-md inline-flex items-center gap-1.5">
                            <span>Vérifier & Activer</span>
                            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                @else
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-black text-emerald-600 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 stroke-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>2FA Activé avec Succès!</span>
                        </h3>
                        <button wire:click="closeSetupModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                    </div>

                    <div class="space-y-4 text-xs">
                        <p class="font-bold text-slate-700">Conservez précieusement vos 10 codes de secours en lieu sûr:</p>
                        <div class="grid grid-cols-2 gap-2 font-mono bg-slate-900 text-emerald-400 p-4 rounded-xl">
                            @foreach($recoveryCodes as $code)
                                <div>{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="button" wire:click="closeSetupModal" class="px-6 py-2 bg-emerald-600 text-white rounded-xl font-bold text-xs">J'ai Sauvegardé Mes Codes</button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
