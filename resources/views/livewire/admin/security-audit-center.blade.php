<div class="space-y-6 font-sans text-slate-900 dark:text-slate-100">

    <!-- HEADER TITLE & METRICS -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 text-teal-400 font-black text-2xl flex items-center justify-center shadow-lg shadow-indigo-950/40 border border-slate-800">
                    <svg class="w-6 h-6 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                        Centre d'Audit de Sécurité Enterprise
                    </h1>
                    <p class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                        Insurio Security Core Engine • Registre Immuable d'Événements
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- View Mode Switch -->
            <div class="bg-slate-100 dark:bg-slate-800 p-1.5 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center gap-1">
                <button wire:click="$set('activeView', 'ledger')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 {{ $activeView === 'ledger' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-teal-400 shadow-sm' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Tableau Ledger</span>
                </button>
                <button wire:click="$set('activeView', 'timeline')" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 {{ $activeView === 'timeline' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-teal-400 shadow-sm' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Timeline Chronologique</span>
                </button>
            </div>

            <!-- Export Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.security-audit.pdf', request()->query()) }}" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-teal-400 font-bold text-xs border border-slate-800 transition shadow-md inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>PDF</span>
                </a>
                <button wire:click="exportCsv" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-emerald-400 font-bold text-xs border border-slate-800 transition shadow-md inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>CSV / Excel</span>
                </button>
                <a href="{{ route('admin.security-audit.pdf', array_merge(request()->query(), ['print' => 1])) }}" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 font-bold text-xs border border-slate-800 transition shadow-md inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimer</span>
                </a>
            </div>
        </div>
    </div>

    <!-- IMMUTABILITY GUARANTEE NOTICE -->
    <div class="bg-gradient-to-r from-slate-950 via-slate-900 to-indigo-950 p-4 rounded-2xl border border-slate-800 text-slate-300 text-xs flex flex-col md:flex-row items-center justify-between gap-3 shadow-lg">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-teal-400 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <div>
                <span class="font-bold text-white uppercase tracking-wider block">Garantie d'Inaltérabilité & Conformité ISO/IEC 27001</span>
                <span class="text-slate-400 text-[11px]">Chaque événement de sécurité est cryptographiquement consigné en écriture seule. Aucune donnée ne peut être modifiée ou supprimée.</span>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-teal-500/10 text-teal-400 border border-teal-500/20 rounded-full font-mono text-[10px] font-bold inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>CONSERVATION PERPÉTUELLE ENFORCED</span>
            </span>
        </div>
    </div>

    <!-- METRICS CARDS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Security Events -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-slate-500 text-xs font-bold uppercase tracking-wider">
                <span>Événements Traçés</span>
                <svg class="w-4 h-4 stroke-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="text-2xl font-black text-slate-900 dark:text-white font-mono">
                {{ number_format($totalEvents) }}
            </div>
            <span class="text-[10px] text-slate-500 block font-mono">Audit complet des activités</span>
        </div>

        <!-- Successful Logins & Activations -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider">
                <span>Connexions & Activations</span>
                <svg class="w-4 h-4 stroke-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">
                {{ number_format($successLogins) }}
            </div>
            <span class="text-[10px] text-slate-500 block font-mono">Authentifications validées</span>
        </div>

        <!-- Failed & Expired Token Attempts -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-wider">
                <span>Échecs & Refus</span>
                <svg class="w-4 h-4 stroke-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono">
                {{ number_format($failedAttempts) }}
            </div>
            <span class="text-[10px] text-slate-500 block font-mono">Mots de passe / TOTP incorrects</span>
        </div>

        <!-- Critical Security Operations -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md space-y-2">
            <div class="flex justify-between items-center text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider">
                <span>Modifications Critiques</span>
                <svg class="w-4 h-4 stroke-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">
                {{ number_format($criticalAlerts) }}
            </div>
            <span class="text-[10px] text-slate-500 block font-mono">Rôles, suspensions & reset 2FA</span>
        </div>

    </div>

    <!-- ADVANCED FILTER BAR -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
            <h3 class="font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-600 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>Filtres de Recherche Approfondie</span>
            </h3>
            <button wire:click="resetFilters" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-bold">
                Réinitialiser Tous les Filtres
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            
            <!-- General Search -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Recherche globale (UUID, Nom, Email, IP)</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..." class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 font-mono">
            </div>

            <!-- Event Type Filter -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Type d'Événement de Sécurité</label>
                <select wire:model.live="eventTypeFilter" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 font-mono">
                    <option value="">Tous les Événements</option>
                    @foreach($eventTypes as $evt)
                        <option value="{{ $evt }}">{{ $evt }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Statut de l'Opération</label>
                <select wire:model.live="statusFilter" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tous les Statuts</option>
                    <option value="success">Success (Succès)</option>
                    <option value="failed">Failed (Échec)</option>
                    <option value="warning">Warning (Avertissement)</option>
                    <option value="critical">Critical (Critique)</option>
                </select>
            </div>

            <!-- User Filter -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Utilisateur / Opérateur</label>
                <select wire:model.live="userFilter" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tous les Utilisateurs</option>
                    @foreach($users as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Agency Filter -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Agence / Tenant</label>
                <select wire:model.live="agencyFilter" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="">Toutes les Agences</option>
                    @foreach($agencies as $ag)
                        <option value="{{ $ag }}">{{ $ag }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Branch Filter -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Branche / Succursale</label>
                <select wire:model.live="branchFilter" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
                    <option value="">Toutes les Branches</option>
                    @foreach($branches as $br)
                        <option value="{{ $br }}">{{ $br }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Date Début</label>
                <input type="date" wire:model.live="dateFrom" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Date To -->
            <div>
                <label class="block font-bold mb-1 text-slate-700 dark:text-slate-300">Date Fin</label>
                <input type="date" wire:model.live="dateTo" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500">
            </div>

        </div>
    </div>

    <!-- MAIN VIEW CONTENT: LEDGER TABLE OR TIMELINE -->
    @if($activeView === 'ledger')
        <!-- TABLE LEDGER VIEW -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-slate-100 font-mono text-[11px] uppercase tracking-wider border-b border-slate-800">
                            <th class="py-4 px-4 font-bold">UUID & Date</th>
                            <th class="py-4 px-4 font-bold">Utilisateur / Rôle</th>
                            <th class="py-4 px-4 font-bold">Agence & Branche</th>
                            <th class="py-4 px-4 font-bold">IP & Appareil</th>
                            <th class="py-4 px-4 font-bold">Événement de Sécurité</th>
                            <th class="py-4 px-4 font-bold">Statut</th>
                            <th class="py-4 px-4 font-bold">Notes & Contexte</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 font-sans">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                
                                <!-- UUID & Date -->
                                <td class="py-3.5 px-4 font-mono text-[11px] space-y-1">
                                    <div class="font-bold text-indigo-600 dark:text-teal-400 select-all" title="{{ $log->uuid }}">
                                        {{ substr($log->uuid, 0, 13) }}...
                                    </div>
                                    <div class="text-[10px] text-slate-500">
                                        {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '' }}
                                    </div>
                                </td>

                                <!-- User & Role -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $log->user_name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $log->user_email }}</div>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 font-mono text-[9px] font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        {{ $log->role_name }}
                                    </span>
                                </td>

                                <!-- Agency & Branch -->
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-800 dark:text-slate-200">{{ $log->agency_name }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $log->branch_name }}</div>
                                </td>

                                <!-- IP & Device -->
                                <td class="py-3.5 px-4 font-mono text-[11px] space-y-0.5">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 select-all">{{ $log->ip_address }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->browser }} • {{ $log->os }}</div>
                                    <div class="text-[9px] text-slate-400 font-sans inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span>{{ $log->device }}</span>
                                    </div>
                                </td>

                                <!-- Security Event -->
                                <td class="py-3.5 px-4 font-mono">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider inline-block border
                                        @if(str_contains($log->event_type, 'login') || str_contains($log->event_type, 'activated'))
                                            bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20
                                        @elseif(str_contains($log->event_type, 'password') || str_contains($log->event_type, '2fa'))
                                            bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20
                                        @elseif(str_contains($log->event_type, 'suspended') || str_contains($log->event_type, 'revoked'))
                                            bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20
                                        @else
                                            bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20
                                        @endif">
                                        {{ $log->event_type }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 font-mono">
                                    @if($log->status === 'success')
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            <span>SUCCESS</span>
                                        </span>
                                    @elseif($log->status === 'failed')
                                        <span class="px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-600 dark:text-rose-400 text-[10px] font-bold inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>FAILED</span>
                                        </span>
                                    @elseif($log->status === 'warning')
                                        <span class="px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <span>WARNING</span>
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-red-600/20 text-red-400 text-[10px] font-bold inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            <span>CRITICAL</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Notes -->
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed max-w-xs">
                                    {{ $log->notes }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500 font-bold">
                                    Aucun événement d'audit de sécurité trouvé correspondant à vos filtres.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $logs->links() }}
            </div>
        </div>
    @else
        <!-- TIMELINE CHRONOLOGIQUE VIEW -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
            <h3 class="font-bold text-base text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
                <svg class="w-5 h-5 stroke-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Flux Chronologique des Événements de Sécurité</span>
            </h3>

            <div class="relative border-l-2 border-slate-200 dark:border-slate-800 ml-4 space-y-8 pl-6">
                @forelse($logs as $log)
                    <div class="relative group">
                        
                        <!-- Timeline Node Dot -->
                        <div class="absolute -left-[31px] top-1.5 w-5 h-5 rounded-full border-2 border-white dark:border-slate-900 shadow-md flex items-center justify-center text-[10px]
                            @if($log->status === 'success') bg-emerald-500 text-white
                            @elseif($log->status === 'failed') bg-rose-500 text-white
                            @elseif($log->status === 'warning') bg-amber-500 text-white
                            @else bg-red-600 text-white @endif">
                        </div>

                        <!-- Timeline Content Card -->
                        <div class="bg-slate-50 dark:bg-slate-950 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 border-b border-slate-200 dark:border-slate-800/80 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-xs uppercase px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                        {{ $log->event_type }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">
                                        {{ $log->user_name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-mono">({{ $log->role_name }})</span>
                                </div>
                                <span class="text-xs font-mono font-bold text-slate-500">
                                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '' }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-700 dark:text-slate-300 font-sans">
                                {{ $log->notes }}
                            </p>

                            <!-- Metadata details row -->
                            <div class="flex flex-wrap items-center gap-4 text-[10px] font-mono text-slate-500 pt-1">
                                <span>🌐 IP: <strong class="text-slate-700 dark:text-slate-300 select-all">{{ $log->ip_address }}</strong></span>
                                <span>💻 {{ $log->browser }} ({{ $log->os }})</span>
                                <span>🏢 {{ $log->agency_name }} • {{ $log->branch_name }}</span>
                                <span>🔑 UUID: <strong class="text-indigo-500 dark:text-teal-400 select-all">{{ $log->uuid }}</strong></span>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="py-12 text-center text-slate-500 font-bold">
                        Aucun événement à afficher dans la chronologie.
                    </div>
                @endforelse
            </div>

            <!-- PAGINATION -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                {{ $logs->links() }}
            </div>
        </div>
    @endif

</div>
