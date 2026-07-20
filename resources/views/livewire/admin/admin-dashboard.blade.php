<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Filter & Branch Selection Row (Sleek Modern Control Bar) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                    <!-- Branch Filter Dropdown -->
                    <div class="min-w-[200px]">
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filtrer par Succursale</label>
                        <select wire:model.live="selectedBranch" class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3 py-1.5 text-xs font-semibold outline-none transition-all text-slate-700">
                            <option value="">Toutes les succursales</option>
                            @foreach($branchList as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nom }} ({{ $branch->ville }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Succursale Active</span>
                        <span class="text-xs font-bold text-slate-700">
                            {{ $branchList->firstWhere('id', $selectedBranch)->nom ?? 'Siège Central' }}
                        </span>
                    </div>
                @endif

                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Période Active</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-extrabold rounded-md border border-indigo-150 uppercase">Tous les contrats</span>
                    </div>
                </div>
            </div>
            
            <div class="text-[10px] text-slate-400 font-mono font-bold self-end md:self-center">
                Dernier Refresh: {{ now()->format('H:i:s') }}
            </div>
        </div>

        <!-- Connection Banner & Status -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-teal-50 text-teal-650 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-sm text-slate-850">Console Cabinet Active:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-250/60 uppercase">
                            {{ App\Models\Setting::get('commission_trigger', 'vente') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Calculateur financier et charges synchronisé par succursale.</p>
                </div>
            </div>

            <!-- Mini stats row -->
            <div class="flex gap-6 text-center text-xs">
                <div>
                    <span class="block font-bold text-slate-800">{{ App\Models\Succursale::count() }}</span>
                    <span class="text-slate-400 text-[10px] font-semibold uppercase">Succursales</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-800">{{ App\Models\Employe::count() }}</span>
                    <span class="text-slate-400 text-[10px] font-semibold uppercase">Employés</span>
                </div>
                <div>
                    <span class="block font-bold text-slate-800">{{ $clientsCount }}</span>
                    <span class="text-slate-400 text-[10px] font-semibold uppercase">Clients</span>
                </div>
            </div>
        </div>

        <!-- Financial Flow cards (+ and -) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Total Cash Inflow (+) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-36 border-t-4 border-t-emerald-500">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Production Global (+)
                    </span>
                    <div class="text-3xl font-black text-slate-800 font-mono mt-3">
                        {{ number_format($totalProduction, 2) }} <span class="text-xs font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Cumul total des primes d'assurance actives</span>
            </div>

            <!-- 2. Total Outflow (-) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-36 border-t-4 border-t-rose-500">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        Charges & Commissions (-)
                    </span>
                    <div class="text-3xl font-black text-slate-800 font-mono mt-3">
                        {{ number_format($totalExpenses, 2) }} <span class="text-xs font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Somme des charges succursales et commissions payées</span>
            </div>

            <!-- 3. Net Cash Balance (+/-) -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-36 border-t-4 {{ $netCashflow >= 0 ? 'border-t-teal-500' : 'border-t-red-500' }}">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $netCashflow >= 0 ? 'text-teal-650' : 'text-rose-650' }}">
                        Résultat Net Estimé
                    </span>
                    <div class="text-3xl font-black font-mono mt-3 {{ $netCashflow >= 0 ? 'text-teal-650' : 'text-rose-600' }}">
                        {{ number_format($netCashflow, 2) }} <span class="text-xs font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Marges nettes projetées (Production - Dépenses)</span>
            </div>
        </div>

        <!-- Second KPIs Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Core active contracts count -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Contrats Actifs</span>
                <div class="text-2xl font-black text-slate-800 font-mono">
                    {{ $activeContractsCount }}
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Polices d'assurance en cours</span>
            </div>

            <!-- Solde à Recouvrer -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Primes Restantes à Recouvrer</span>
                <div class="text-2xl font-black text-rose-600 font-mono">
                    {{ number_format($totalImpayes, 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Primes restant à encaisser des clients</span>
            </div>

            <!-- Commissions Internes -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Commissions Internes (Collaborateurs)</span>
                <div class="text-2xl font-black text-indigo-600 font-mono">
                    {{ number_format($totalCommissions, 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Commissions versées aux agents commerciaux</span>
            </div>
        </div>

        <!-- Main workspace: Left (Ledger & performance) | Right (Detailed Expenses breakdown) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left workspace (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Latest Contracts -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h2 class="font-bold text-slate-800 text-sm">Derniers Contrats Émis</h2>
                        <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" class="text-[10px] text-teal-600 hover:text-teal-950 font-bold uppercase tracking-wider">Voir registre</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($latestContracts as $contrat)
                            <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                <div>
                                    <div class="font-bold text-slate-850 font-mono text-sm">#{{ $contrat->numero_contrat }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Client: {{ $contrat->client->nom_complet }} | Succursale: {{ $contrat->succursale->nom ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono font-bold text-teal-650">{{ number_format($contrat->prime_totale, 2) }} DH</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 font-mono">{{ $contrat->date_effet->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">
                                Aucun contrat enregistré.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top rankings row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Branch Rankings (Only if no specific branch is selected) -->
                    @if(!$selectedBranch)
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h2 class="font-bold text-slate-800 text-sm">Classement Succursales</h2>
                            </div>
                            <div class="divide-y divide-slate-100">
                                @forelse($branchRankings as $index => $rank)
                                    <div class="p-4 flex justify-between items-center hover:bg-slate-50">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-teal-50 text-teal-700 text-xs font-bold mr-3 font-mono">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="font-semibold text-slate-700 text-xs">{{ $rank->succursale->nom }}</span>
                                        </div>
                                        <span class="font-mono font-bold text-teal-650 text-xs">
                                            {{ number_format($rank->total_prod, 2) }} DH
                                        </span>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-slate-400 text-xs">Aucune donnée.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- Agent Rankings -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden {{ $selectedBranch ? 'md:col-span-2' : '' }}">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h2 class="font-bold text-slate-800 text-sm">Classement Commerciaux</h2>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($agentRankings as $index => $rank)
                                <div class="p-4 flex justify-between items-center hover:bg-slate-50">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-teal-50 text-teal-700 text-xs font-bold mr-3 font-mono">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-semibold text-slate-700 text-xs">{{ $rank->employe->nom_complet }}</span>
                                    </div>
                                    <span class="font-mono font-bold text-teal-650 text-xs">
                                        {{ number_format($rank->total_prod, 2) }} DH
                                    </span>
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-400 text-xs">Aucune donnée.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right workspace: Detailed Expenses breakdown -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Detailed Expenses breakdown -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 text-sm">Répartition des Charges</h2>
                        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                            <a href="{{ Route::has('admin.charges') ? route('admin.charges') : '#' }}" class="text-[10px] text-teal-600 hover:text-teal-950 font-bold uppercase tracking-wider">Gérer</a>
                        @endif
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <!-- 1. Loyer -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-750 mb-1.5">
                                <span>🏢 Loyer / Locaux</span>
                                <span class="font-mono">{{ number_format($this->expenseLoyer, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseLoyer / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- 2. Electricite -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-750 mb-1.5">
                                <span>⚡ Électricité (Dow)</span>
                                <span class="font-mono">{{ number_format($this->expenseElectricite, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseElectricite / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- 3. Eau -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-750 mb-1.5">
                                <span>💧 Eau (Ma)</span>
                                <span class="font-mono">{{ number_format($this->expenseEau, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-sky-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseEau / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- 4. Salaires -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-750 mb-1.5">
                                <span>👥 Salaires & Commissions</span>
                                <span class="font-mono">{{ number_format($this->expenseSalaire + $this->totalCommissions, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? (($this->expenseSalaire + $this->totalCommissions) / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-1 font-medium">Inclut les commissions fixes & variables versées aux agents.</p>
                        </div>

                        <!-- 5. Autres -->
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-750 mb-1.5">
                                <span>📦 Dépenses Diverses</span>
                                <span class="font-mono">{{ number_format($this->expenseAutre, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-purple-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseAutre / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expirations alerts -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                        <svg class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="font-bold text-slate-800 text-sm">Alertes Échéances (30 jours)</h2>
                    </div>

                    <div class="p-4 space-y-4">
                        @forelse($expiringContracts as $contrat)
                            @php
                                $daysLeft = now()->diffInDays($contrat->date_echeance, false);
                            @endphp
                            <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl flex justify-between items-center transition-all hover:bg-slate-100/60">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-850 text-xs font-mono">#{{ $contrat->numero_contrat }}</div>
                                    <div class="text-[10px] text-slate-500 font-sans truncate max-w-[140px]">{{ $contrat->client->nom_complet }}</div>
                                    <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Agent: {{ $contrat->employe->nom_complet ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-[9px] font-extrabold uppercase tracking-wide {{ $daysLeft <= 7 ? 'bg-rose-50 text-rose-700 border border-rose-250/60' : 'bg-amber-50 text-amber-700 border border-amber-250/60' }}">
                                        -{{ $daysLeft }} J
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">
                                Aucun contrat arrivant à échéance.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
