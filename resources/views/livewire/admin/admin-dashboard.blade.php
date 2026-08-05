<div class="max-w-[1600px] mx-auto p-6 space-y-6 font-sans">
    <div class="space-y-6">

        <!-- Header Controls Row -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                    <!-- Branch Filter Dropdown -->
                    <div class="min-w-[220px]">
                        <label class="flex items-center gap-1.5 text-[11px] font-extrabold tracking-wider mb-1 uppercase.select-none" style="color: #334155 !important;">
                            <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7m4 0v10"></path></svg>
                            Filtrer par Succursale
                        </label>
                        <select wire:model.live="selectedBranch" class="w-full bg-slate-50 border border-slate-300 focus:bg-white rounded-xl px-3 py-2 text-xs font-bold outline-none transition-all cursor-pointer" style="color: #0f172a !important; background-color: #f8fafc !important;">
                            <option value="">Toutes les succursales</option>
                            @foreach($branchList as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nom }} ({{ $branch->ville }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <span class="flex items-center gap-1.5 text-[11px] font-extrabold tracking-wider uppercase block" style="color: #334155 !important;">
                            <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7m4 0v10"></path></svg>
                            Succursale Active
                        </span>
                        <span class="text-xs font-bold block mt-0.5" style="color: #0f172a !important;">
                            {{ $branchList->firstWhere('id', $selectedBranch)->nom ?? 'Siège Central' }}
                        </span>
                    </div>
                @endif

                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <div>
                    <span class="flex items-center gap-1.5 text-[11px] font-extrabold tracking-wider uppercase block" style="color: #334155 !important;">
                        <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Période Active
                    </span>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-[11px] font-extrabold rounded-lg border border-indigo-200 uppercase" style="color: #4338ca !important; background-color: #eef2ff !important; border-color: #c7d2fe !important;">
                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tous les contrats
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button wire:click="refreshDashboard" class="font-extrabold px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-2 shadow-sm hover:bg-slate-200" style="color: #0f172a !important; background-color: #f1f5f9 !important; border: 1px solid #cbd5e1 !important; cursor: pointer;">
                    <svg wire:loading.class="animate-spin" class="w-4 h-4 text-slate-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span style="color: #0f172a !important; font-weight: 800;">Actualiser</span>
                </button>
                <div class="flex items-center gap-1.5 text-[11px] font-mono font-bold" style="color: #334155 !important;">
                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Rafraîchi à: {{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>

        <!-- Connection Banner & Status -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-2xl p-6 shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-teal-500/10 text-teal-400 rounded-xl flex items-center justify-center border border-teal-500/20 shadow-inner">
                    <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4m-4 0H7m4 0v10"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-base tracking-tight text-white">Insurio Agency Operating System</span>
                        <span class="px-2 py-0.5 rounded text-[8px] font-extrabold bg-teal-500/20 text-teal-300 border border-teal-500/30 uppercase tracking-widest">
                            Morocco ERP
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 mt-1">Supervision de la production, de l'état de recouvrement des succursales et des encaissements.</p>
                </div>
            </div>

            <!-- Mini stats row -->
            <div class="flex flex-wrap items-center gap-6 text-left bg-slate-800/80 p-3 px-5 rounded-xl border border-slate-700/60">
                <div>
                    <span class="block font-black text-white text-sm font-mono">{{ number_format($totalProduction, 2) }} DH</span>
                    <span class="text-slate-400 text-[8px] font-bold uppercase tracking-widest">Production</span>
                </div>
                <div class="h-6 w-px bg-slate-700"></div>
                <div>
                    <span class="block font-black text-white text-sm font-mono">{{ number_format($totalExpenses, 2) }} DH</span>
                    <span class="text-slate-400 text-[8px] font-bold uppercase tracking-widest">Charges</span>
                </div>
                <div class="h-6 w-px bg-slate-700"></div>
                <div class="px-3 py-1 rounded-lg bg-teal-500/10 border border-teal-500/20">
                    <span class="block font-black text-teal-300 text-sm font-mono">{{ number_format($netProfit, 2) }} DH</span>
                    <span class="text-teal-400 text-[8px] font-bold uppercase tracking-widest">Bénéfice</span>
                </div>
            </div>
        </div>

        <!-- Segmented Tab Switcher Pills -->
        <div class="flex items-center">
            <div class="inline-flex bg-slate-200/70 p-1.5 rounded-2xl gap-1.5 border border-slate-200/80 shadow-inner">
                <button wire:click="setTab('portfolio')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center gap-2 {{ $activeDashboardTab === 'portfolio' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <svg class="w-4 h-4 {{ $activeDashboardTab === 'portfolio' ? 'text-teal-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Portefeuille (Portfolio)
                </button>
                <button wire:click="setTab('executive')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center gap-2 {{ $activeDashboardTab === 'executive' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <svg class="w-4 h-4 {{ $activeDashboardTab === 'executive' ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    CEO & Finance
                </button>
                <button wire:click="setTab('operations')" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center gap-2 {{ $activeDashboardTab === 'operations' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}">
                    <svg class="w-4 h-4 {{ $activeDashboardTab === 'operations' ? 'text-amber-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Command Center
                </button>
            </div>
        </div>

        <!-- PORTFOLIO VIEW -->
        @if($activeDashboardTab === 'portfolio')
            <!-- Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Contrats Actifs</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $activeContractsCount }}</span>
                    <span class="text-[9px] text-teal-600 font-bold block mt-2">Taux Rétention: {{ $customerRetention }}%</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">À Renouveler (30j)</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $expiring30Count }}</span>
                    <span class="text-[9px] text-indigo-600 font-bold block mt-2">Taux Renouvellement: {{ $renewalRate }}%</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Contrats Expirés</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $expiredContractsCount }}</span>
                    <span class="text-[9px] text-slate-500 block mt-2">Fin de couverture</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Taux de Sinistralité</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $claimsWaitingCount }} en attente</span>
                    <span class="text-[9px] text-amber-600 font-bold block mt-2">Sinistres à suivre</span>
                </div>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Distribution lists -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Distribution by Insurer and Products -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- By Company -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 border-b pb-2">Contrats par Compagnie</h3>
                            <div class="space-y-3">
                                @forelse($contractsByCompany as $company)
                                    <div>
                                        <div class="flex justify-between items-center text-xs font-semibold text-slate-700 mb-1">
                                            <span>{{ $company['label'] }}</span>
                                            <span>{{ $company['value'] }} contrats</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full">
                                            <div class="bg-indigo-650 h-full rounded-full" style="width: {{ $activeContractsCount > 0 ? ($company['value'] / $activeContractsCount) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-slate-400 text-xs py-2 text-center">Aucune compagnie.</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- By Product -->
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 border-b pb-2">Contrats par Produit</h3>
                            <div class="space-y-3">
                                @forelse($contractsByType as $product)
                                    <div>
                                        <div class="flex justify-between items-center text-xs font-semibold text-slate-700 mb-1">
                                            <span>{{ $product['label'] }}</span>
                                            <span>{{ $product['value'] }} contrats</span>
                                        </div>
                                        <div class="w-full bg-slate-100 h-1.5 rounded-full">
                                            <div class="bg-teal-500 h-full rounded-full" style="width: {{ $activeContractsCount > 0 ? ($product['value'] / $activeContractsCount) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-slate-400 text-xs py-2 text-center">Aucun produit.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Latest Contracts List -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Derniers Contrats Émis</h2>
                            <a href="{{ route('automobile.index') }}" class="text-[10px] text-teal-650 hover:text-teal-950 font-bold uppercase tracking-wider">Voir registre</a>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($latestContracts as $contrat)
                                <div class="p-4 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <div>
                                        <div class="font-bold text-slate-800 font-mono text-sm">#{{ $contrat->contract_number }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">Client: {{ $contrat->client->first_name ?? '' }} {{ $contrat->client->last_name ?? '' }} | Succursale: {{ $contrat->succursale->nom ?? 'N/A' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-mono font-bold text-teal-650">{{ number_format($contrat->premium_amount, 2) }} DH</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5 font-mono">{{ $contrat->start_date ? $contrat->start_date->format('d/m/Y') : '' }}</div>
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

                <!-- Right Sidebar: Alerts & Rankings -->
                <div class="space-y-6">
                    <!-- Branch Performance -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 border-b pb-2">Production par Succursale</h3>
                        <div class="space-y-3">
                            @forelse($branchRankings as $branch)
                                <div class="flex justify-between items-center bg-slate-50 p-2.5 rounded-xl border border-slate-200/40">
                                    <span class="text-xs text-slate-700 font-semibold">{{ $branch->succursale->nom ?? 'Siège' }}</span>
                                    <span class="font-mono font-bold text-teal-650 text-xs">{{ number_format($branch->total_prod, 2) }} DH</span>
                                </div>
                            @empty
                                <div class="text-slate-400 text-xs text-center">Aucune succursale.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Expiring Contracts -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                            <span class="text-xs">🔄</span>
                            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Alertes Échéances (30 jours)</h2>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($expiringContracts as $contrat)
                                @php
                                    $daysLeft = now()->diffInDays($contrat->end_date, false);
                                @endphp
                                <div class="bg-slate-50 border border-slate-200/60 p-3.5 rounded-xl flex justify-between items-center">
                                    <div class="space-y-1">
                                        <div class="font-bold text-slate-800 text-xs font-mono">#{{ $contrat->contract_number }}</div>
                                        <div class="text-[10px] text-slate-500 truncate max-w-[140px]">{{ $contrat->client->first_name ?? '' }} {{ $contrat->client->last_name ?? '' }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wide {{ $daysLeft <= 7 ? 'bg-rose-50 text-rose-700 border border-rose-250/60' : 'bg-amber-50 text-amber-700 border border-amber-250/60' }}">
                                        -{{ $daysLeft }} J
                                    </span>
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

        <!-- EXECUTIVE VIEW -->
        @elseif($activeDashboardTab === 'executive')
            <!-- Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Recettes Mensuelles</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ number_format($monthlyRevenue, 2) }} DH</span>
                    <span class="text-[9px] text-teal-600 font-bold block mt-2">Encaissé aujourd'hui: {{ number_format($todayRevenue, 2) }} DH</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Production Totale</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ number_format($totalProduction, 2) }} DH</span>
                    <span class="text-[9px] text-slate-500 block mt-2">Moyenne prime: {{ number_format($averagePremium, 2) }} DH</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Bénéfice Net Estimé</span>
                    <span class="text-2xl font-black font-mono block mt-1 {{ $netProfit >= 0 ? 'text-emerald-700' : 'text-rose-650' }}">{{ number_format($netProfit, 2) }} DH</span>
                    <span class="text-[9px] text-slate-500 block mt-2">Commissions totales: {{ number_format($totalCommissions, 2) }} DH</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Total Charges & Dépenses</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ number_format($totalExpenses, 2) }} DH</span>
                    <span class="text-[9px] text-rose-650 font-bold block mt-2">Dépenses d'agence enregistrées</span>
                </div>
            </div>

            <!-- Main Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
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
                                <div class="absolute bottom-full mb-2 bg-slate-800 text-white text-[10px] rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity z-10 pointer-events-none text-center">
                                    Production: {{ number_format($chartProductionData[$index], 0) }} DH<br>
                                    Commission: {{ number_format($chartCommissionsData[$index], 0) }} DH
                                </div>
                                <div class="flex gap-1.5 w-full items-end h-full">
                                    <div class="bg-teal-500/80 hover:bg-teal-600 rounded-t-sm w-1/2 transition-all" style="height: {{ $prodHeight }}%"></div>
                                    <div class="bg-indigo-500/80 hover:bg-indigo-600 rounded-t-sm w-1/2 transition-all" style="height: {{ $commHeight }}%"></div>
                                </div>
                                <span class="text-[9px] font-semibold text-slate-400 mt-2">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Sidebar: Leaderboard & Expenses -->
                <div class="space-y-6">
                    <!-- Leaderboard -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                        <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Classement & Produits Top</h2>
                        <div class="space-y-4 text-xs font-semibold">
                            <!-- Top Agents -->
                            <div>
                                <span class="text-slate-400 text-[9px] uppercase tracking-wider font-bold block mb-2">Meilleurs Agents (Commissions)</span>
                                <div class="space-y-2">
                                    @forelse($topAgents as $agent)
                                        <div class="flex justify-between items-center bg-slate-50 p-2.5 rounded-xl border border-slate-200/40">
                                            <span class="text-slate-700">{{ $agent['name'] }}</span>
                                            <span class="font-mono font-bold text-teal-650">{{ number_format($agent['total'], 2) }} DH</span>
                                        </div>
                                    @empty
                                        <div class="text-slate-400 text-xs py-1">Aucune commission versée.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expenses Breakdown -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Répartition des Charges</h2>
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
                                    <span>👥 Salaires & Commissions</span>
                                    <span class="font-mono">{{ number_format($this->expenseSalaire + $this->totalCommissions, 2) }} DH</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-teal-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? (($this->expenseSalaire + $this->totalCommissions) / $totalExpenses) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- OPERATIONS COMMAND CENTER VIEW -->
        @elseif($activeDashboardTab === 'operations')
            <!-- Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Encaissements Aujourd'hui</span>
                    <span class="text-2xl font-black text-teal-650 font-mono block mt-1">{{ number_format($todayRevenue, 2) }} DH</span>
                    <span class="text-[9px] text-slate-400 block mt-2">Transactions guichet & banque</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Impays Recouvrables</span>
                    <span class="text-2xl font-black text-rose-650 font-mono block mt-1">{{ number_format($totalImpayes, 2) }} DH</span>
                    <span class="text-[9px] text-rose-700 font-bold block mt-2">Déficit de recouvrement</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Sinistres à Gérer</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $claimsWaitingCount }}</span>
                    <span class="text-[9px] text-amber-600 font-bold block mt-2">Prises en charge & constats</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 block">Collaborateurs Connectés</span>
                    <span class="text-2xl font-black text-slate-900 font-mono block mt-1">{{ $employeesOnlineCount }}</span>
                    <span class="text-[9px] text-emerald-600 font-bold block mt-2">🟢 Session active</span>
                </div>
            </div>

            <!-- Operations Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Live alerts & events -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
                        <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-4 border-b pb-2">Command Center: Actions Requises</h2>
                        <div class="divide-y divide-slate-100">
                            @if($totalImpayes > 0)
                                <div class="py-3 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">🚨</span>
                                        <div>
                                            <span class="font-bold text-xs text-slate-800 block">Défaut de Paiement Détecté</span>
                                            <span class="text-[10px] text-slate-500">Relance client nécessaire pour primes non recouvrées.</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.payments.center') }}" class="px-2.5 py-1 bg-rose-50 text-rose-700 rounded-lg text-[10px] font-bold border border-rose-200">Recouvrer</a>
                                </div>
                            @endif

                            @if($claimsWaitingCount > 0)
                                <div class="py-3 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">💼</span>
                                        <div>
                                            <span class="font-bold text-xs text-slate-800 block">Dossiers Sinistres Non Résolus</span>
                                            <span class="text-[10px] text-slate-500">Vérifier status de validation et visites d'experts.</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.dossiers') }}" class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-lg text-[10px] font-bold border border-amber-200">Ouvrir</a>
                                </div>
                            @endif

                            @if($expiring30Count > 0)
                                <div class="py-3 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">📅</span>
                                        <div>
                                            <span class="font-bold text-xs text-slate-800 block">Renouvellements Prévus (30 jours)</span>
                                            <span class="text-[10px] text-slate-500">Assigner des tâches de relance de renouvellement.</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.tasks') }}" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-bold border border-indigo-200">Gérer</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Online Employees list -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                    <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Journal d'Activité Live</h2>
                    <div class="text-[11px] text-slate-500 space-y-2">
                        <div class="p-2 bg-slate-50 rounded-xl">
                            <span class="font-bold text-slate-700 block">Mohamed El Mansouri</span>
                            <span class="text-[9px] text-slate-400 font-mono">Connecté aujourd'hui à 08:31</span>
                        </div>
                        <div class="p-2 bg-slate-50 rounded-xl">
                            <span class="font-bold text-slate-700 block">Fatima Ezzahra</span>
                            <span class="text-[9px] text-slate-400 font-mono">Modification contrat émise à 17:15</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
