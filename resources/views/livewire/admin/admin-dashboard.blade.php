<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Filter & Branch Selection Row -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
            <div class="flex flex-wrap items-center gap-4 w-full md:w-auto">
                @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                    <!-- Branch Filter Dropdown -->
                    <div class="min-w-[200px]">
                        <label class="block text-[9px] font-bold text-slate-400 tracking-wider mb-1">Filtrer par Succursale</label>
                        <select wire:model.live="selectedBranch" class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3 py-1.5 text-xs font-semibold outline-none transition-all text-slate-700">
                            <option value="">Toutes les succursales</option>
                            @foreach($branchList as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nom }} ({{ $branch->ville }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 tracking-wider block">Succursale Active</span>
                        <span class="text-xs font-bold text-slate-700">
                            {{ $branchList->firstWhere('id', $selectedBranch)->nom ?? 'Siège Central' }}
                        </span>
                    </div>
                @endif

                <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                <div>
                    <span class="text-[9px] font-bold text-slate-400 tracking-wider block">Période Active</span>
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- 1. Total Production (Premiums) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 border-t-4 border-t-emerald-500">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">Production (Primes)</span>
                    <div class="text-2xl font-black text-slate-800 font-mono mt-2">
                        {{ number_format($totalProduction, 2) }} <span class="text-[10px] font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Total des primes nettes des contrats</span>
            </div>

            <!-- 2. Commissions Inflow -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 border-t-4 border-t-indigo-500">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Commissions Cabinet</span>
                    <div class="text-2xl font-black text-slate-800 font-mono mt-2">
                        {{ number_format($totalCommissions, 2) }} <span class="text-[10px] font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Revenus perçus des compagnies d'assurance</span>
            </div>

            <!-- 3. Expenses / Outflow -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 border-t-4 border-t-rose-500">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600">Charges Globales</span>
                    <div class="text-2xl font-black text-slate-800 font-mono mt-2">
                        {{ number_format($totalExpenses, 2) }} <span class="text-[10px] font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Charges succursales et commissions versées</span>
            </div>

            <!-- 4. Net Profit -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 border-t-4 {{ $netProfit >= 0 ? 'border-t-teal-500' : 'border-t-red-500' }}">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $netProfit >= 0 ? 'text-teal-650' : 'text-rose-650' }}">Bénéfice Net Estimé</span>
                    <div class="text-2xl font-black font-mono mt-2 {{ $netProfit >= 0 ? 'text-teal-650' : 'text-rose-600' }}">
                        {{ number_format($netProfit, 2) }} <span class="text-[10px] font-bold text-slate-400">DH</span>
                    </div>
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Résultat réel (Commissions - Charges)</span>
            </div>
        </div>

        <!-- Renewal expiring countdown buckets -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-semibold text-slate-500">Échéances sous 7 jours</span>
                    <span class="block text-2xl font-black text-rose-650 mt-1 font-mono">{{ $expiring7Count }}</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-700 font-bold">7J</div>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-semibold text-slate-500">Échéances sous 15 jours</span>
                    <span class="block text-2xl font-black text-amber-600 mt-1 font-mono">{{ $expiring15Count }}</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-700 font-bold">15J</div>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex justify-between items-center">
                <div>
                    <span class="text-xs font-semibold text-slate-500">Échéances sous 30 jours</span>
                    <span class="block text-2xl font-black text-indigo-650 mt-1 font-mono">{{ $expiring30Count }}</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-700 font-bold">30J</div>
            </div>
        </div>

        <!-- Main workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left workspace (2 Cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- SVG Performance Chart -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                    <h2 class="font-bold text-slate-800 text-sm mb-4">Production & Commissions Mensuelles ({{ now()->year }})</h2>
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
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h2 class="font-bold text-slate-800 text-sm">Derniers Contrats Émis</h2>
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

            <!-- Right workspace: Breakdown & Expiry list -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Detailed Expenses breakdown -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 text-sm">Répartition des Charges</h2>
                        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('comptable'))
                            <a href="{{ route('admin.charges') }}" class="text-[10px] text-teal-600 hover:text-teal-950 font-bold uppercase tracking-wider">Gérer</a>
                        @endif
                    </div>
                    
                    <div class="p-6 space-y-5">
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
                                <span>⚡ Électricité (Lydec/Redal)</span>
                                <span class="font-mono">{{ number_format($this->expenseElectricite, 2) }} DH</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-500 h-full rounded-full" style="width: {{ $totalExpenses > 0 ? ($this->expenseElectricite / $totalExpenses) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                <span>💧 Eau (Lydec/Redal)</span>
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

                <!-- Expiring contracts notifications -->
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
                                $daysLeft = now()->diffInDays($contrat->end_date, false);
                            @endphp
                            <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl flex justify-between items-center transition-all hover:bg-slate-100/60">
                                <div class="space-y-1">
                                    <div class="font-bold text-slate-850 text-xs font-mono">#{{ $contrat->contract_number }}</div>
                                    <div class="text-[10px] text-slate-500 font-sans truncate max-w-[140px]">{{ $contrat->client->first_name ?? '' }} {{ $contrat->client->last_name ?? '' }}</div>
                                    <div class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Agent: {{ $contrat->employe->nom_complet ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right flex flex-col items-end gap-1.5">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-[9px] font-extrabold uppercase tracking-wide {{ $daysLeft <= 7 ? 'bg-rose-50 text-rose-700 border border-rose-250/60' : 'bg-amber-50 text-amber-700 border border-amber-250/60' }}">
                                        -{{ $daysLeft }} J
                                    </span>
                                    <button wire:click="$dispatch('triggerWhatsAppReminder', { contractId: {{ $contrat->id }} })" class="inline-flex items-center px-2 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[9px] font-bold transition-all">
                                        WhatsApp
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-400 text-sm">
                                Aucun contrat arrivant à échéance.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Contracts by Insurer -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 text-sm">Contrats par Compagnie</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($contractsByCompany as $item)
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                    <span>{{ $item['label'] }}</span>
                                    <span class="font-mono">{{ $item['value'] }} contrats</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $activeContractsCount > 0 ? ($item['value'] / $activeContractsCount) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">Aucune donnée disponible.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Contracts by Type -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800 text-sm">Contrats par Produit</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($contractsByType as $item)
                            <div>
                                <div class="flex justify-between items-center text-xs font-bold text-slate-755 mb-1.5">
                                    <span>{{ $item['label'] }}</span>
                                    <span class="font-mono">{{ $item['value'] }} contrats</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $activeContractsCount > 0 ? ($item['value'] / $activeContractsCount) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4">Aucune donnée disponible.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- WhatsApp Reminder Modal -->
    <livewire:admin.send-whatsapp-reminder />
</div>
