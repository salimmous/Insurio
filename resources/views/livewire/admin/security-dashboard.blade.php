<div class="space-y-6 font-sans text-slate-900 dark:text-slate-100">

    <!-- HEADER TITLE & QUICK NAV -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-teal-500 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-6 h-6 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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
            <a href="{{ route('admin.security-audit') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-teal-400 font-bold text-xs border border-slate-800 transition shadow-md inline-flex items-center gap-2">
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Registre d'Audit Immuable</span>
            </a>
            <a href="{{ route('admin.security') }}" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-md inline-flex items-center gap-2">
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Sessions & Paramètres 2FA</span>
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session()->has('message'))
        <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-2xl text-xs font-bold font-mono flex items-center gap-2">
            <svg class="w-4 h-4 stroke-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- 11 KPI CARDS GRID -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        
        <!-- 1. Total Users -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                <span>Total Utilisateurs</span>
                <svg class="w-4 h-4 stroke-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
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
                <svg class="w-4 h-4 stroke-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
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
                        <svg class="w-5 h-5 stroke-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Activité des Connexions (7 Derniers Jours)</span>
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
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Taux d'Adoption 2FA</span>
                    </span>
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
                <svg class="w-5 h-5 stroke-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Alertes de Risque & Actions Prioritaires</span>
            </h3>
            <span class="text-xs font-mono text-amber-400 font-bold">Audit en temps réel</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 text-xs font-mono">
            
            <!-- Alert 1: Brute Force Risks -->
            <div class="bg-slate-900 p-4 rounded-2xl border border-rose-900/50 space-y-3">
                <div class="flex items-center justify-between text-rose-400 font-bold uppercase tracking-wider text-[11px]">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 stroke-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Tentatives Répétées (24h)</span>
                    </span>
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
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 stroke-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Liens d'Activation Expirés</span>
                    </span>
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
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 stroke-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Comptes Verrouillés</span>
                    </span>
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
                <svg class="w-5 h-5 stroke-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Flux d'Activité de Sécurité en Temps Réel</span>
            </h3>
            <a href="{{ route('admin.security-audit') }}" class="text-xs text-indigo-600 dark:text-teal-400 hover:underline font-bold inline-flex items-center gap-1">
                <span>Voir le journal d'audit complet</span>
                <svg class="w-3.5 h-3.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
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
                            @if(str_contains($evt->event_type, 'login'))
                                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            @elseif(str_contains($evt->event_type, 'activated'))
                                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            @elseif(str_contains($evt->event_type, '2fa'))
                                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            @elseif(str_contains($evt->event_type, 'password'))
                                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            @else
                                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @endif
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
