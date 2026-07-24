<div class="space-y-6 font-sans text-slate-900 dark:text-slate-100">

    <!-- HEADER TITLE & QUICK NAV -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-teal-500 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    🛡️
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                        Tableau de Bord de Sécurité Enterprise
                    </h1>
                    <p class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                        Insurio Identity & Threat Monitoring Engine
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.security-audit') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-teal-400 font-bold text-xs border border-slate-800 transition shadow-md flex items-center gap-2">
                <span>📋 Registre d'Audit Immuable</span>
            </a>
            <a href="{{ route('admin.security') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md flex items-center gap-2">
                <span>🖥️ Sessions & Paramètres 2FA</span>
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session()->has('message'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-2xl text-xs font-bold font-mono">
            ✓ {{ session('message') }}
        </div>
    @endif

    <!-- 11 KPI CARDS GRID -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        
        <!-- 1. Total Users -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                <span>Total Utilisateurs</span>
                <span>👥</span>
            </div>
            <div class="text-2xl font-black text-slate-900 dark:text-white font-mono">
                {{ number_format($totalUsers) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Comptes enregistrés</span>
        </div>

        <!-- 2. Activated Accounts -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-emerald-600 dark:text-emerald-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Comptes Activés</span>
                <span>✅</span>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">
                {{ number_format($activatedAccounts) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Utilisateurs actifs</span>
        </div>

        <!-- 3. Pending Activations -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-amber-600 dark:text-amber-400 text-[11px] font-bold uppercase tracking-wider">
                <span>En Attente</span>
                <span>⏳</span>
            </div>
            <div class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">
                {{ number_format($pendingActivations) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Invitation 24h valide</span>
        </div>

        <!-- 4. Expired Activation Links -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-rose-600 dark:text-rose-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Liens Expirés</span>
                <span>⚠️</span>
            </div>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono">
                {{ number_format($expiredActivationLinks) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Action admin requise</span>
        </div>

        <!-- 5. 2FA Enabled -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-indigo-600 dark:text-indigo-400 text-[11px] font-bold uppercase tracking-wider">
                <span>2FA Activé</span>
                <span>🛡️</span>
            </div>
            <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-mono">
                {{ number_format($twoFactorEnabled) }}
            </div>
            <span class="text-[10px] text-indigo-500 font-mono font-bold block">{{ $twoFactorAdoptionRate }}% adoption</span>
        </div>

        <!-- 6. 2FA Disabled -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                <span>2FA Désactivé</span>
                <span>🔓</span>
            </div>
            <div class="text-2xl font-black text-slate-700 dark:text-slate-300 font-mono">
                {{ number_format($twoFactorDisabled) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Sans protection TOTP</span>
        </div>

        <!-- 7. Today's Logins -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-teal-600 dark:text-teal-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Logins du Jour</span>
                <span>🔑</span>
            </div>
            <div class="text-2xl font-black text-teal-600 dark:text-teal-400 font-mono">
                {{ number_format($todaysLogins) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Dernières 24h</span>
        </div>

        <!-- 8. Failed Logins -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-rose-600 dark:text-rose-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Échecs Connexion</span>
                <span>❌</span>
            </div>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono">
                {{ number_format($failedLogins) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Mot de passe incorrect</span>
        </div>

        <!-- 9. Locked Accounts -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-amber-600 dark:text-amber-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Verrouillés</span>
                <span>🔒</span>
            </div>
            <div class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">
                {{ number_format($lockedAccounts) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Blocage sécurité</span>
        </div>

        <!-- 10. Active Sessions -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-blue-600 dark:text-blue-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Sessions Actives</span>
                <span>💻</span>
            </div>
            <div class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">
                {{ number_format($activeSessions) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Appareils connectés</span>
        </div>

        <!-- 11. Suspended Accounts -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2 col-span-2 sm:col-span-1">
            <div class="flex justify-between items-center text-orange-600 dark:text-orange-400 text-[11px] font-bold uppercase tracking-wider">
                <span>Suspendus</span>
                <span>🚫</span>
            </div>
            <div class="text-2xl font-black text-orange-600 dark:text-orange-400 font-mono">
                {{ number_format($suspendedAccounts) }}
            </div>
            <span class="text-[10px] text-slate-400 font-mono block">Accès révoqué</span>
        </div>

    </div>

    <!-- CHARTS & ANALYTICS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Daily Logins & Failures Bar Graph -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b border-slate-200 dark:border-slate-800 pb-3">
                <div>
                    <h3 class="font-black text-base text-slate-900 dark:text-white flex items-center gap-2">
                        📊 Activité des Connexions (7 Derniers Jours)
                    </h3>
                    <p class="text-xs text-slate-500 font-mono">Comparatif entre connexions réussies et échecs</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-mono font-bold">
                    <span class="flex items-center gap-1 text-emerald-500">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Réussies
                    </span>
                    <span class="flex items-center gap-1 text-rose-500">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span> Échecs
                    </span>
                </div>
            </div>

            <!-- SVG Bar Chart -->
            <div class="h-48 flex items-end justify-between gap-3 pt-4 px-2">
                @foreach($dates as $d)
                    @php
                        $succ = $dailyLogins[$d] ?? 0;
                        $fail = $dailyFailedLogins[$d] ?? 0;
                        $maxVal = max(10, max($dailyLogins->max(), $dailyFailedLogins->max()));
                        $succHeight = round(($succ / $maxVal) * 100);
                        $failHeight = round(($fail / $maxVal) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                        <div class="w-full flex items-end justify-center gap-1 h-36">
                            <!-- Success Bar -->
                            <div class="w-1/2 bg-emerald-500 hover:bg-emerald-400 rounded-t-lg transition-all relative" style="height: {{ max(4, $succHeight) }}%" title="Logins réussis: {{ $succ }}">
                                <span class="opacity-0 group-hover:opacity-100 transition absolute -top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] font-mono font-bold px-1.5 py-0.5 rounded shadow">
                                    {{ $succ }}
                                </span>
                            </div>
                            <!-- Failure Bar -->
                            <div class="w-1/2 bg-rose-500 hover:bg-rose-400 rounded-t-lg transition-all relative" style="height: {{ max(4, $failHeight) }}%" title="Échecs: {{ $fail }}">
                                <span class="opacity-0 group-hover:opacity-100 transition absolute -top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] font-mono font-bold px-1.5 py-0.5 rounded shadow">
                                    {{ $fail }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[10px] font-mono font-bold text-slate-500 border-t border-slate-200 dark:border-slate-800 pt-1 w-full text-center">
                            {{ \Carbon\Carbon::parse($d)->format('d/m') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2FA Adoption & Security Events Breakdown -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <div>
                <h3 class="font-black text-base text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center justify-between">
                    <span>🛡️ Taux d'Adoption 2FA</span>
                    <span class="text-indigo-500 font-mono">{{ $twoFactorAdoptionRate }}%</span>
                </h3>

                <!-- Adoption Progress Bar -->
                <div class="space-y-2 pt-2">
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden p-0.5">
                        <div class="bg-gradient-to-r from-indigo-500 to-teal-400 h-full rounded-full transition-all duration-500" style="width: {{ $twoFactorAdoptionRate }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] font-mono text-slate-500">
                        <span>{{ $twoFactorEnabled }} comptes avec 2FA</span>
                        <span>{{ $twoFactorDisabled }} sans 2FA</span>
                    </div>
                </div>
            </div>

            <!-- Distribution of Security Events -->
            <div class="space-y-3 pt-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Répartition des Événements</span>
                <div class="space-y-2">
                    @foreach($eventDistribution as $dist)
                        @php
                            $totalEvts = max(1, $recentSecurityEvents->count());
                            $pct = round(($dist->count / $totalEvts) * 100);
                        @endphp
                        <div class="space-y-1 text-xs font-mono">
                            <div class="flex justify-between">
                                <span class="font-bold text-slate-700 dark:text-slate-300 uppercase text-[10px]">{{ $dist->event_type }}</span>
                                <span class="text-slate-500">{{ $dist->count }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full rounded-full" style="width: {{ min(100, $pct * 2) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- RISK ALERTS & SECURITY WARNINGS PANEL -->
    <div class="bg-slate-950 p-6 rounded-3xl border border-slate-800 text-white space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="font-black text-base text-white uppercase tracking-wider flex items-center gap-2">
                <span>🚨 Alertes de Risque & Actions Prioritaires</span>
            </h3>
            <span class="text-xs font-mono text-amber-400 font-bold">Audit en temps réel</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 text-xs font-mono">
            
            <!-- Alert 1: Brute Force Risks -->
            <div class="bg-slate-900 p-4 rounded-2xl border border-rose-900/50 space-y-3">
                <div class="flex items-center justify-between text-rose-400 font-bold uppercase tracking-wider text-[11px]">
                    <span>💥 Tentatives Répétées (24h)</span>
                    <span class="px-2 py-0.5 bg-rose-500/20 rounded-full text-[10px]">{{ $bruteForceAlerts->count() }} alertes</span>
                </div>
                <div class="space-y-2">
                    @forelse($bruteForceAlerts as $bf)
                        <div class="p-2.5 bg-slate-950 rounded-xl border border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="text-slate-200 font-bold block">{{ $bf->user_email ?: 'IP Anonyme' }}</span>
                                <span class="text-[10px] text-slate-500 block">IP: {{ $bf->ip_address }}</span>
                            </div>
                            <span class="text-rose-400 font-bold font-mono">{{ $bf->attempts }} échecs</span>
                        </div>
                    @empty
                        <div class="text-slate-500 py-2">Aucune attaque par force brute détectée.</div>
                    @endforelse
                </div>
            </div>

            <!-- Alert 2: Expired Activation Links -->
            <div class="bg-slate-900 p-4 rounded-2xl border border-amber-900/50 space-y-3">
                <div class="flex items-center justify-between text-amber-400 font-bold uppercase tracking-wider text-[11px]">
                    <span>⏳ Liens d'Activation Expirés</span>
                    <span class="px-2 py-0.5 bg-amber-500/20 rounded-full text-[10px]">{{ $expiredLinkUsers->count() }} en attente</span>
                </div>
                <div class="space-y-2">
                    @forelse($expiredLinkUsers as $exU)
                        <div class="p-2.5 bg-slate-950 rounded-xl border border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="text-slate-200 font-bold block">{{ $exU->name }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $exU->email }}</span>
                            </div>
                            <button wire:click="resendActivationLink({{ $exU->id }})" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-[10px] font-bold">
                                Régénérer (24h)
                            </button>
                        </div>
                    @empty
                        <div class="text-slate-500 py-2">Aucun lien d'activation expiré.</div>
                    @endforelse
                </div>
            </div>

            <!-- Alert 3: Locked Accounts -->
            <div class="bg-slate-900 p-4 rounded-2xl border border-indigo-900/50 space-y-3">
                <div class="flex items-center justify-between text-indigo-400 font-bold uppercase tracking-wider text-[11px]">
                    <span>🔒 Comptes Verrouillés</span>
                    <span class="px-2 py-0.5 bg-indigo-500/20 rounded-full text-[10px]">{{ $lockedUserList->count() }} comptes</span>
                </div>
                <div class="space-y-2">
                    @forelse($lockedUserList as $lckU)
                        <div class="p-2.5 bg-slate-950 rounded-xl border border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="text-slate-200 font-bold block">{{ $lckU->name }}</span>
                                <span class="text-[10px] text-slate-500 block">{{ $lckU->email }}</span>
                            </div>
                            <button wire:click="unlockAccount({{ $lckU->id }})" class="px-2.5 py-1 bg-teal-600 hover:bg-teal-500 text-white rounded-lg text-[10px] font-bold">
                                Déverrouiller
                            </button>
                        </div>
                    @empty
                        <div class="text-slate-500 py-2">Aucun compte verrouillé actuellement.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- RECENT SECURITY ACTIVITY STREAM -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="font-black text-base text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span>⚡ Flux d'Activité de Sécurité en Temps Réel</span>
            </h3>
            <a href="{{ route('admin.security-audit') }}" class="text-xs text-indigo-600 dark:text-teal-400 hover:underline font-bold">
                Voir le journal d'audit complet →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($recentSecurityEvents as $evt)
                <div class="p-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800/80 flex items-center justify-between gap-4 font-mono text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-sm
                            @if($evt->status === 'success') bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                            @elseif($evt->status === 'failed') bg-rose-500/10 text-rose-600 dark:text-rose-400
                            @elseif($evt->status === 'warning') bg-amber-500/10 text-amber-600 dark:text-amber-400
                            @else bg-purple-500/10 text-purple-600 dark:text-purple-400 @endif">
                            @if(str_contains($evt->event_type, 'login')) 🔑
                            @elseif(str_contains($evt->event_type, 'activated')) ✅
                            @elseif(str_contains($evt->event_type, '2fa')) 🛡️
                            @elseif(str_contains($evt->event_type, 'password')) 🔒
                            @else ⚡ @endif
                        </div>

                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 dark:text-white font-sans">{{ $evt->user_name }}</span>
                                <span class="text-[10px] text-slate-400">({{ $evt->event_type }})</span>
                            </div>
                            <p class="text-[11px] text-slate-600 dark:text-slate-300 font-sans">
                                {{ $evt->notes }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right space-y-0.5">
                        <span class="text-[11px] text-slate-500 font-bold block">
                            {{ $evt->created_at ? $evt->created_at->diffForHumans() : '' }}
                        </span>
                        <span class="text-[10px] text-slate-400 block">IP: {{ $evt->ip_address }}</span>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400">
                    Aucun événement de sécurité récent enregistré.
                </div>
            @endforelse
        </div>
    </div>

</div>
