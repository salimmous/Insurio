<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Controls Row -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                    <!-- Branch Filter Dropdown -->
                    <div class="min-w-[200px]">
                        <label class="block text-[9px] font-bold text-slate-450 tracking-wider mb-1 uppercase">Filtrer par Succursale</label>
                        <select wire:model.live="selectedBranch" class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3 py-1.5 text-xs font-semibold outline-none transition-all text-slate-700">
                            <option value="">Toutes les succursales</option>
                            @foreach($branchList as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nom }} ({{ $branch->ville }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <span class="text-[9px] font-bold text-slate-450 tracking-wider block uppercase">Succursale Active</span>
                        <span class="text-xs font-bold text-slate-700">
                            {{ $branchList->firstWhere('id', $selectedBranch)->nom ?? 'Siège Central' }}
                        </span>
                    </div>
                @endif

                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <div>
                    <span class="text-[9px] font-bold text-slate-450 tracking-wider block uppercase">Période Active</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-extrabold rounded-md border border-indigo-150 uppercase">Tous les contrats</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button wire:click="refreshDashboard" class="bg-slate-50 hover:bg-slate-100 text-slate-750 border border-slate-200 font-bold px-3.5 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5">
                    <span>🔄</span> Actualiser
                </button>
                <div class="text-[10px] text-slate-450 font-mono font-bold">
                    Rafraîchi à: {{ now()->format('H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Connection Banner & Status -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20">
                    <span class="text-xl">📊</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-base tracking-tight text-white">Dashboard CEO d'Agence</span>
                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-widest">
                            {{ App\Models\Setting::get('commission_trigger', 'vente') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-350 mt-1">Supervision de la production, de l'état de recouvrement des succursales et des encaissements.</p>
                </div>
            </div>

            <!-- Mini stats row -->
            <div class="flex gap-8 text-left">
                <div>
                    <span class="block font-black text-white text-lg font-mono">{{ App\Models\Succursale::count() }}</span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">Succursales</span>
                </div>
                <div>
                    <span class="block font-black text-white text-lg font-mono">{{ App\Models\Employe::count() }}</span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">Agents Actifs</span>
                </div>
                <div>
                    <span class="block font-black text-white text-lg font-mono">{{ $clientsCount }}</span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase tracking-widest">Clients CRM</span>
                </div>
            </div>
        </div>

        <!-- Stripe-like Executive Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Revenue & Cash Flow -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">Chiffre d'Affaires Encaissé</span>
                <div>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($monthlyRevenue, 2) }} DH</div>
                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Encaissé aujourd'hui: {{ number_format($todayRevenue, 2) }} DH</div>
                </div>
                <span class="text-[9px] text-emerald-600 font-bold">🟢 Flux de trésorerie entrant</span>
            </div>

            <!-- Production Volume -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">Production Nette (Primes)</span>
                <div>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($totalProduction, 2) }} DH</div>
                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Moyenne prime / contrat: {{ number_format($averagePremium, 2) }} DH</div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Contrats actifs hors taxes</span>
            </div>

            <!-- Commissions & Net Margin -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">Bénéfice Net Estimé</span>
                <div>
                    <div class="text-2xl font-black font-mono tracking-tight {{ $netProfit >= 0 ? 'text-emerald-700' : 'text-rose-650' }}">
                        {{ number_format($netProfit, 2) }} DH
                    </div>
                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Commissions: {{ number_format($totalCommissions, 2) }} DH</div>
                </div>
                <span class="text-[9px] text-indigo-600 font-bold">Marge brute après charges</span>
            </div>

            <!-- Retention & Renewals -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">Taux de Renouvellement</span>
                <div>
                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $renewalRate }}%</div>
                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">Rétention Clientèle: {{ $customerRetention }}%</div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Performances de fidélisation</span>
            </div>

        </div>

        <!-- Secondary telemetry row (Impayés, Tickets, Sinistres, Expirations) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <div class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-450">Primes Non Recouvrées</span>
                    <span class="block text-xl font-bold text-rose-650 mt-1 font-mono">{{ number_format($totalImpayes, 2) }} DH</span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-xs">🔴</div>
            </div>

            <div class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-450">Sinistres en Attente</span>
                    <span class="block text-xl font-bold text-amber-600 mt-1 font-mono">{{ $claimsWaitingCount }}</span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xs">⚠️</div>
            </div>

            <div class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-450">Contrats à Renouveler (30j)</span>
                    <span class="block text-xl font-bold text-indigo-650 mt-1 font-mono">{{ $expiring30Count }}</span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs">🔄</div>
            </div>

            <div class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-450">Contrats Expirés</span>
                    <span class="block text-xl font-bold text-slate-800 mt-1 font-mono">{{ $expiredContractsCount }}</span>
                </div>
                <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-650 flex items-center justify-center font-bold text-xs">⚫</div>
            </div>

        </div>

        <!-- Main workspace grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Side: Chart and Recent list (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Performance Chart -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                    <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4">Production & Commissions Mensuelles ({{ now()->year }})</h2>
                    <div class="flex items-end gap-2 h-48 pt-6 relative border-b border-l border-slate-200 px-4">
                        @php
                            $maxVal = max(array_merge($chartProductionData, $chartCommissionsData, [1000]));
                        @endphp
                        @foreach($chartLabels as $index => $label)
                            @php
                                $prodHeight = $maxVal > 0 ? ($chartProductionData[$index] / $maxVal) * 100 : 0;
                                $commHeight = $maxVal > 0 ? ($chartCommissionsData[$index] / $maxVal) * 100 : 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center justify-end h-full group relative">
                                <!-- Tooltip -->
                                <div class="absolute bottom-full mb-2 bg-slate-800 text-white text-[10px] rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none text-center">
                                    Production: {{ number_format($chartProductionData[$index], 0) }} DH<br>
                                    Commission: {{ number_format($chartCommissionsData[$index], 0) }} DH
                                </div>
                                
                                <div class="flex gap-1.5 w-full items-end h-full">
                                    <!-- Production Bar -->
                                    <div class="bg-teal-500/80 hover:bg-teal-600 rounded-t-sm w-1/2 transition-all" style="height: {{ $prodHeight }}%"></div>
                                    <!-- Commission Bar -->
                                    <div class="bg-indigo-500/80 hover:bg-indigo-600 rounded-t-sm w-1/2 transition-all" style="height: {{ $commHeight }}%"></div>
                                </div>
                                
                                <span class="text-[9px] font-semibold text-slate-400 mt-2">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Latest Contracts -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h2 class="font-bold text-slate-850 text-xs uppercase tracking-wider">Derniers Contrats Émis</h2>
                        <a href="{{ route('automobile.index') }}" class="text-[10px] text-teal-600 hover:text-teal-950 font-bold uppercase tracking-wider">Voir registre</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($latestContracts as $contrat)
                            <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                <div>
                                    <div class="font-bold text-slate-850 font-mono text-sm">#{{ $contrat->contract_number }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Client: {{ $contrat->client->first_name ?? '' }} {{ $contrat->client->last_name ?? '' }} | Succursale: {{ $contrat->succursale->nom ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-mono font-bold text-teal-650">{{ number_format($contrat->premium_amount, 2) }} DH</div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 font-mono">{{ $contrat->start_date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">
                                Aucun contrat enregistré.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Side: Leaderboard & Charges (1 Col) -->
            <div class="space-y-6">

                <!-- Top Agents & Products Leaderboard -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Classement & Produits Top</h2>
                    
                    <div class="space-y-4 text-xs font-semibold">
                        <!-- Top Agents -->
                        <div>
                            <span class="text-slate-400 text-[9px] uppercase tracking-wider font-bold block mb-2">Meilleurs Agents (Commissions)</span>
                            <div class="space-y-2">
                                @forelse($topAgents as $agent)
                                    <div class="flex justify-between items-center bg-slate-50 p-2 rounded-xl border border-slate-200/40">
                                        <span class="text-slate-700">{{ $agent['name'] }}</span>
                                        <span class="font-mono font-bold text-teal-650">{{ number_format($agent['total'], 2) }} DH</span>
                                    </div>
                                @empty
                                    <div class="text-slate-400 text-xs py-1">Aucune commission versée.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Top Products -->
                        <div>
                            <span class="text-slate-400 text-[9px] uppercase tracking-wider font-bold block mb-2">Produits les plus vendus</span>
                            <div class="space-y-2">
                                @forelse($bestProducts as $product)
                                    <div class="flex justify-between items-center bg-slate-50 p-2 rounded-xl border border-slate-200/40">
                                        <span class="text-slate-700">{{ $product['name'] }}</span>
                                        <span class="font-mono font-bold text-slate-800">{{ number_format($product['total'], 2) }} DH</span>
                                    </div>
                                @empty
                                    <div class="text-slate-400 text-xs py-1">Aucun contrat vendu.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses breakdown -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-850 text-xs uppercase tracking-wider">Répartition des Charges ({{ number_format($totalExpenses, 2) }} DH)</h2>
                        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                            <a href="{{ route('admin.charges') }}" class="text-[10px] text-teal-600 hover:text-teal-950 font-bold uppercase tracking-wider">Gérer</a>
                        @endif
                    </div>
                    
                    <div class="p-5 space-y-4">
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                <span>🏢 Loyer / Locaux</span>
                                <span class="font-mono">{{ number_format($this->expenseLoyer, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseLoyer / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                <span>⚡ Électricité</span>
                                <span class="font-mono">{{ number_format($this->expenseElectricite, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseElectricite / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                <span>💧 Eau / Fluides</span>
                                <span class="font-mono">{{ number_format($this->expenseEau, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-sky-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseEau / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                <span>👥 Salaires & Commissions</span>
                                <span class="font-mono">{{ number_format($this->expenseSalaire + $this->totalCommissions, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-teal-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? (($this->expenseSalaire + $this->totalCommissions) / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiring Contracts Notifications -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                        <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="font-bold text-slate-850 text-xs uppercase tracking-wider">Alertes Échéances (30 jours)</h2>
                    </div>

                    <div class="p-4 space-y-3">
                        @forelse($expiringContracts as $contrat)
                            @php
                                $daysLeft = now()->diffInDays($contrat->end_date, false);
                            @endphp
                            <div class="bg-slate-50 border border-slate-200/60 p-3.5 rounded-xl flex justify-between items-center transition-all hover:bg-slate-100/60">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-850 text-xs font-mono">#{{ $contrat->contract_number }}</div>
                                    <div class="text-[10px] text-slate-500 font-sans truncate max-w-[140px]">{{ $contrat->client->first_name ?? '' }} {{ $contrat->client->last_name ?? '' }}</div>
                                    <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Agent: {{ $contrat->employe->nom_complet ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide {{ $daysLeft <= 7 ? 'bg-rose-50 text-rose-700 border border-rose-250/60' : 'bg-amber-50 text-amber-700 border border-amber-250/60' }}">
                                        -{{ $daysLeft }} J
                                    </span>
                                    <button wire:click="$dispatch('triggerWhatsAppReminder', { contractId: {{ $contrat->id }} })" class="inline-flex items-center px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9px] font-bold transition-all">
                                        WhatsApp
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-slate-400 text-xs">
                                Aucun contrat arrivant à échéance.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
