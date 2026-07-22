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
                        🛡️ Protege (2FA Actif)
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
                        ⚠️ Non Protégé (2FA Inactif)
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
        <button wire:click="$set('activeTab', 'security')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'security' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>🔐 Mon Authentification 2FA</span>
        </button>
        <button wire:click="$set('activeTab', 'admin_2fa')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'admin_2fa' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>🏢 Supervision 2FA Utilisateurs & Politiques</span>
        </button>
        <button wire:click="$set('activeTab', 'audit_logs')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'audit_logs' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>📋 Journal d'Audit de Sécurité</span>
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
                        <button wire:click="start2faSetup" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-md">
                            ⚡ Activer le 2FA TOTP
                        </button>
                    @endif
                </div>

                <!-- Compatible Authenticator Applications -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                    <span class="text-xs font-bold text-slate-800 block">Applications Compatibles (Scan QR Code):</span>
                    <div class="flex flex-wrap gap-2 text-[11px] font-semibold text-slate-600">
                        <span class="px-2.5 py-1 bg-white border rounded-lg shadow-2xs">📱 Google Authenticator</span>
                        <span class="px-2.5 py-1 bg-white border rounded-lg shadow-2xs">🔑 Microsoft Authenticator</span>
                        <span class="px-2.5 py-1 bg-white border rounded-lg shadow-2xs">🛡️ Authy</span>
                        <span class="px-2.5 py-1 bg-white border rounded-lg shadow-2xs">🔐 1Password</span>
                    </div>
                </div>

                <!-- Recovery Codes Section -->
                @if($is2faEnabled)
                    <div class="space-y-4 pt-4 border-t">
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
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50">
                        <input type="checkbox" wire:model="force_2fa_all" class="rounded text-indigo-600">
                        <span>Obliger pour TOUS les Utilisateurs</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50">
                        <input type="checkbox" wire:model="force_2fa_admins" class="rounded text-indigo-600">
                        <span>Administrateurs d'Agence</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50">
                        <input type="checkbox" wire:model="force_2fa_finance" class="rounded text-indigo-600">
                        <span>Utilisateurs Finance & Caisses</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 border rounded-xl bg-slate-50">
                        <input type="checkbox" wire:model="force_2fa_managers" class="rounded text-indigo-600">
                        <span>Managers & Chefs d'Équipe</span>
                    </label>
                </div>
                <div class="flex justify-end pt-2">
                    <button wire:click="saveForce2faPolicies" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700">Enregistrer les Politiques</button>
                </div>
            </div>

            <!-- Users 2FA Status Supervision Table (NO SECRET KEYS SHOWN) -->
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">2FA Actif ✅</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">2FA Inactif ❌</span>
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
                        <button type="button" wire:click="confirm2faSetup" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 shadow-md">Vérifier & Activer ➔</button>
                    </div>
                @else
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-black text-emerald-600">✅ 2FA Activer avec Succès!</h3>
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
