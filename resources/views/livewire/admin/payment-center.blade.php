<div class="p-6 space-y-6 font-sans">
    <!-- Header Title -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Grand Livre & Gestion de la Trésorerie</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    BANKING ERP LEDGER v3.0
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Suivi inaltérable de chaque dirham, traçabilité des opérations, caisses et chèques marocains.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-md transition gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                <span>- Enregistrer une Dépense / Sortie</span>
            </button>
        </div>
    </div>

    <!-- High-Density Financial KPI Banner -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    @if(!empty($filterDate))
                        Recettes du {{ \Carbon\Carbon::parse($filterDate)->format('d/m/Y') }}
                    @elseif($filterDatePreset === 'today')
                        Recettes du Jour
                    @elseif($filterDatePreset === 'yesterday')
                        Recettes d'Hier
                    @elseif($filterDatePreset === 'this_week')
                        Recettes Semaine
                    @elseif($filterDatePreset === 'this_month')
                        Recettes du Mois
                    @else
                        Total Recettes (Cumul)
                    @endif
                </span>
                <span class="text-xl font-black text-emerald-600">+{{ number_format($totalRecettes, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Dépenses: {{ number_format($totalDepenses, 2) }} DH</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Solde Caisses Agence</span>
                <span class="text-xl font-black text-slate-900">{{ number_format($cashBalance, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Espèces physiques en mains</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Chèques en Attente</span>
                <span class="text-xl font-black text-amber-600">{{ number_format($chequesEnAttenteSum, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">{{ $chequesEnAttenteCount }} chèque(s) à verser</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Bar (Filter by Date/Day, Operation Type & Method) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h3 class="font-black text-sm text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filtres Avancés du Grand Livre</span>
                </h3>
                <p class="text-[11px] text-slate-500">Filtrage dynamique par jour, type d'opération et mode de paiement.</p>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="$set('filterDatePreset', 'today')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filterDatePreset === 'today' && empty($filterDate) ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Aujourd'hui (Vue du Jour)
                </button>
                <button wire:click="$set('filterDatePreset', 'all')" class="px-3 py-1.5 rounded-xl text-xs font-bold transition {{ $filterDatePreset === 'all' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Tous les filtres (Historique)
                </button>
                <button wire:click="resetFilters" class="text-xs font-bold text-slate-500 hover:text-indigo-600 transition flex items-center gap-1 ml-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Réinitialiser</span>
                </button>
                <button wire:click="wipeAllFinancialData" wire:confirm="Voulez-vous vraiment effacer TOUTES les données financières pour effectuer un test à blanc ?" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition flex items-center gap-1 ml-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Vider Données (Test)</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 text-xs">
            <!-- Recherche Globale -->
            <div class="md:col-span-1">
                <label class="block font-bold text-slate-700 mb-1">Recherche Globale</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="TRX-..., N° Reçu, Client, CIN..." class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-medium pl-8 focus:ring-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <!-- Calendrier Direct (Choisir un Jour) -->
            <div>
                <label class="block font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Calendrier (Choisir un Jour)</span>
                    </span>
                    @if(!empty($this->filterDate))
                        <button wire:click="$set('filterDate', '')" class="text-[10px] text-indigo-600 font-bold hover:underline">Effacer date</button>
                    @endif
                </label>
                <div class="relative">
                    <input type="date" wire:model.live="filterDate" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 bg-white shadow-xs focus:ring-2 focus:ring-indigo-500 cursor-pointer">
                </div>
            </div>

            <!-- Filtre par Période Pré-définie -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Ou Période Pré-définie</label>
                <select wire:model.live="filterDatePreset" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold bg-white shadow-xs">
                    <option value="today">Aujourd'hui (Par Défaut)</option>
                    <option value="all">Toutes les dates (Historique complet)</option>
                    <option value="yesterday">Hier</option>
                    <option value="this_week">Cette semaine</option>
                    <option value="this_month">Ce mois-ci</option>
                    <option value="custom">Période personnalisée (Intervalle)...</option>
                </select>
            </div>

            <!-- Type d'opération -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Type d'Opération</label>
                <select wire:model.live="filterEntryType" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold bg-white">
                    <option value="">Toutes les opérations (+ / -)</option>
                    <option value="credit">Recettes / Encaissements (+)</option>
                    <option value="debit">Dépenses / Décaissements (-)</option>
                </select>
            </div>

            <!-- Mode de Paiement -->
            <div>
                <label class="block font-bold text-slate-700 mb-1">Mode de Paiement</label>
                <select wire:model.live="filterMethod" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold bg-white">
                    <option value="">Tous les modes de paiement</option>
                    <option value="cash">Espèces (Caisse)</option>
                    <option value="cheque">Chèque Marocain</option>
                    <option value="transfer">Virement Bancaire</option>
                    <option value="card">Carte / TPE</option>
                </select>
            </div>
        </div>

        <!-- Custom Date Range Selector -->
        @if($filterDatePreset === 'custom')
            <div class="pt-3 border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs animate-in fade-in duration-150">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Date Début</label>
                    <input type="date" wire:model.live="filterDateStart" class="w-full border border-slate-300 rounded-xl p-2 font-mono text-xs">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Date Fin</label>
                    <input type="date" wire:model.live="filterDateEnd" class="w-full border border-slate-300 rounded-xl p-2 font-mono text-xs">
                </div>
            </div>
        @endif
    </div>

    <!-- Period Totals Summary Pill -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 bg-slate-900 text-white p-4 rounded-2xl shadow-lg">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Synthèse des transactions (Filtres Actifs) :</span>
        </div>
        <div class="flex flex-wrap items-center gap-3 text-xs font-mono font-bold">
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-xl">Recettes: +{{ number_format($totalRecettes, 2) }} DH</span>
            <span class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 rounded-xl">Dépenses: -{{ number_format($totalDepenses, 2) }} DH</span>
            <span class="px-3.5 py-1 bg-indigo-600 text-white rounded-xl shadow-xs">Solde Net: {{ number_format($soldeNet, 2) }} DH</span>
        </div>
    </div>

    <!-- JOURNAL COMPTABLE GROUPÉ PAR JOUR -->
    <div class="space-y-6">
        @forelse($groupedJournal as $day)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden space-y-0">
                <!-- Day Banner Header -->
                <div class="bg-slate-50 border-b border-slate-200 px-6 py-3.5 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <span class="font-black text-sm text-slate-900 capitalize flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $day['formatted_date'] }}</span>
                        </span>
                        @if($day['is_today'])
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Aujourd'hui</span>
                        @elseif($day['is_yesterday'])
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">Hier</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs font-mono font-bold">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl">Entrées (+): +{{ number_format($day['total_in'], 2) }} DH</span>
                        <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl">Sorties (-): -{{ number_format($day['total_out'], 2) }} DH</span>
                        <span class="px-3 py-1 bg-slate-900 text-white rounded-xl shadow-xs">Solde Jour: {{ number_format($day['net_day'], 2) }} DH</span>
                    </div>
                </div>

                <!-- Day Transactions Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs">
                        <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-left">
                            <tr>
                                <th class="px-6 py-3">Transaction ID & Heure</th>
                                <th class="px-6 py-3">Client / Contrat</th>
                                <th class="px-6 py-3">Catégorie</th>
                                <th class="px-6 py-3">Mode</th>
                                <th class="px-6 py-3">Montant</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3 text-right">Reçu & Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-800 font-medium">
                            @foreach($day['transactions'] as $item)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <button type="button" wire:click="viewTransactionDetails({{ $item->id }})" class="font-mono font-bold text-indigo-600 hover:text-indigo-800 hover:underline block text-sm inline-flex items-center gap-1">
                                            <span>{{ $item->transaction_id }}</span>
                                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->entry_date ? $item->entry_date->format('H:i') : '-' }}</span>
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @if($item->client)
                                            <div class="font-bold text-slate-900">{{ $item->client->first_name }} {{ $item->client->last_name }}</div>
                                            <span class="text-[10px] text-slate-400 font-mono">CIN: {{ $item->client->cin ?? '-' }}</span>
                                        @else
                                            <span class="text-slate-400 font-medium">Opération Générale Agence</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <span class="font-bold text-slate-800 block capitalize">{{ str_replace('_', ' ', $item->category ?: 'Encaissement Prime') }}</span>
                                        @if($item->notes)
                                            <span class="text-[11px] text-slate-500 font-normal block">{{ $item->notes }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <span class="uppercase font-mono text-[11px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                            {{ $item->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono font-black text-sm">
                                        @if($item->entry_type === 'credit')
                                            <span class="text-emerald-600 block">+{{ number_format($item->amount, 2) }} DH</span>
                                        @else
                                            <span class="text-rose-600 block">-{{ number_format($item->amount, 2) }} DH</span>
                                        @endif

                                        @if(isset($ledgerBalances[$item->id]))
                                            <div class="text-[10px] font-medium text-slate-500 mt-1 flex items-center gap-1 font-sans">
                                                <span class="text-slate-400">Solde:</span>
                                                <span class="text-slate-700 font-bold font-mono" title="Solde Avant Opération">{{ number_format($ledgerBalances[$item->id]['before'], 2) }}</span>
                                                <span class="text-slate-400 font-bold">➔</span>
                                                <span class="font-bold font-mono {{ $ledgerBalances[$item->id]['after'] < 0 ? 'text-rose-600' : 'text-emerald-600' }}" title="Solde Après Opération">{{ number_format($ledgerBalances[$item->id]['after'], 2) }} DH</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        @if($item->status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Validé</span>
                                            </span>
                                        @elseif($item->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">En Attente</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Rejeté</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono">
                                        <span class="text-[10px] font-bold text-slate-500 block">{{ $item->receipt_number }}</span>
                                        <a href="{{ url('/admin/payments-center/' . $item->id . '/receipt') }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 hover:underline font-bold text-[11px] inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            <span>Imprimer Reçu</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-500 text-xs font-medium space-y-2">
                <svg class="w-8 h-8 text-slate-300 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div class="font-bold text-slate-800 text-sm">Aucune opération enregistrée pour la période sélectionnée</div>
                <p class="text-slate-400">Pour consulter l'historique des jours précédents, sélectionnez <button wire:click="$set('filterDatePreset', 'all')" class="text-indigo-600 font-bold hover:underline">Toutes les dates</button> dans les filtres ci-dessus.</p>
            </div>
        @endforelse
    <!-- Modal Form: New General Ledger Entry -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-slate-900/60 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span>Enregistrer une Dépense / Sortie (-)</span>
                    </h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="createLedgerEntry" class="space-y-4 text-xs font-medium">
                    <!-- Live Real-Time Balance Impact Preview (Solde Avant -> Déduction -> Solde Après) -->
                    @php 
                        $deductionAmount = (float)($amount ?? 0);
                        $afterBalance = $cashBalance - $deductionAmount;
                    @endphp
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2 shadow-xs">
                        <div class="flex justify-between items-center text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>Aperçu de l'Impact sur Solde Caisse</span>
                            </span>
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100">Calcul en temps réel</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <!-- Solde Avant -->
                            <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-xs">
                                <span class="text-[10px] font-extrabold text-slate-400 block uppercase">Solde Avant</span>
                                <span class="text-xs font-black text-slate-800 font-mono">{{ number_format($cashBalance, 2) }} DH</span>
                            </div>
                            <!-- Montant Déduit -->
                            <div class="bg-rose-50 p-2.5 rounded-xl border border-rose-200 shadow-xs">
                                <span class="text-[10px] font-extrabold text-rose-500 block uppercase">Montant Déduit (-)</span>
                                <span class="text-xs font-black text-rose-600 font-mono">- {{ number_format($deductionAmount, 2) }} DH</span>
                            </div>
                            <!-- Solde Après -->
                            <div class="p-2.5 rounded-xl border shadow-xs {{ $afterBalance < 0 ? 'bg-rose-100/80 border-rose-400' : 'bg-emerald-50 border-emerald-300' }}">
                                <span class="text-[10px] font-extrabold {{ $afterBalance < 0 ? 'text-rose-700' : 'text-emerald-700' }} block uppercase">Solde Après</span>
                                <span class="text-xs font-black {{ $afterBalance < 0 ? 'text-rose-800' : 'text-emerald-800' }} font-mono">{{ number_format($afterBalance, 2) }} DH</span>
                            </div>
                        </div>
                        @if($afterBalance < 0)
                            <div class="text-[10px] font-bold text-rose-600 flex items-center justify-center gap-1 pt-0.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Attention: Le montant saisi dépasse le solde d'espèces disponible en caisse!</span>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Sens de l'Opération</label>
                            <input type="hidden" wire:model="entry_type" value="debit">
                            <div class="px-3 py-2.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 font-bold text-xs flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                <span>Débit (- Dépense / Sortie / Remboursement)</span>
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Montant (DH) *</label>
                            <input type="number" step="0.01" wire:model.live="amount" placeholder="0.00" class="w-full border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-base text-slate-900 bg-white shadow-xs focus:ring-2 focus:ring-indigo-500 placeholder-slate-400">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Mode de Paiement *</label>
                            <select wire:model.live="payment_method" class="w-full border border-slate-300 rounded-xl p-2.5 font-bold text-slate-900 bg-white shadow-xs">
                                <option value="cash">Espèces (Caisse Agence)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Catégorie Comptable *</label>
                            <select wire:model="category" class="w-full border border-slate-300 rounded-xl p-2.5 font-medium text-slate-900 bg-white shadow-xs">
                                <option value="charge">Charge & Dépense d'Exploitation</option>
                                <option value="encaissement_prime">Encaissement Prime Assurance</option>
                                <option value="reglement_sinistre">Règlement Sinistre Client</option>
                                <option value="commission">Commission Compagnie</option>
                                <option value="virement">Virement Bancaire Interne</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Notes & Motif de la Transaction</label>
                        <textarea wire:model="notes" rows="2" class="w-full border border-slate-300 rounded-xl p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeCreateModal" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 font-bold">Annuler</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-md">Enregistrer au Grand Livre</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- CASH MOVEMENT MODAL (ENTRÉES / SORTIES ESPÈCES) -->
    @if($showCashMovementModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-6 shadow-2xl border border-slate-200 animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span>Enregistrer un Mouvement de Caisse</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Entrée ou retrait direct d'espèces avec mise à jour du solde de caisse.</p>
                    </div>
                    <button wire:click="closeCashMovementModal" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="recordCashMovement" class="space-y-4 text-xs font-semibold">
                    <!-- Movement Type Selector -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-2">Type d'Opération de Caisse *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('cash_movement_type', 'debit')" 
                                    class="py-3 px-4 rounded-xl border font-bold flex items-center justify-center gap-2 transition {{ $cash_movement_type === 'debit' ? 'bg-rose-50 border-rose-500 text-rose-700 shadow-xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                <span>Sortie / Retrait (-)</span>
                            </button>
                            <button type="button" wire:click="$set('cash_movement_type', 'credit')" 
                                    class="py-3 px-4 rounded-xl border font-bold flex items-center justify-center gap-2 transition {{ $cash_movement_type === 'credit' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 shadow-xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Entrée Espèces (+)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Montant -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Montant en DH *</label>
                        <div class="relative">
                            <input type="number" step="0.01" wire:model="cash_movement_amount" placeholder="ex: 300.00" class="w-full border border-slate-300 rounded-xl px-3 py-2.5 font-mono text-base font-bold pr-12 text-slate-900">
                            <span class="absolute right-3 top-3 font-mono font-bold text-slate-400">DH</span>
                        </div>
                        @error('cash_movement_amount') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date & Heure -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date & Heure du Mouvement</label>
                        <input type="datetime-local" wire:model="cash_movement_date" class="w-full border border-slate-300 rounded-xl p-2.5 font-mono text-xs">
                    </div>

                    <!-- Motif & Description -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Motif / Description Détaillée *</label>
                        <textarea wire:model="cash_movement_notes" rows="2" placeholder="ex: Retrait 300 DH de la caisse pour achat fournitures d'agence..." class="w-full border border-slate-300 rounded-xl p-2.5 text-xs"></textarea>
                        @error('cash_movement_notes') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Client Optionnel -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Client Associé (Optionnel)</label>
                        <select wire:model="client_id" class="w-full border border-slate-300 rounded-xl p-2.5">
                            <option value="">-- Aucun Client (Opération d'Agence) --</option>
                            @foreach($clients as $cl)
                                <option value="{{ $cl->id }}">{{ $cl->formatted_reference }} - {{ $cl->first_name }} {{ $cl->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeCashMovementModal" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 font-bold">Annuler</button>
                        <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 shadow-md">
                            Enregistrer le Mouvement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Transaction Full Details Modal (Popup) -->
    @if($showDetailModal && $selectedLedger)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-200 animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase text-indigo-600 tracking-wider block">Fiche Audit Transaction</span>
                        <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span>{{ $selectedLedger->transaction_id }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $selectedLedger->entry_type === 'credit' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $selectedLedger->entry_type === 'credit' ? 'Encaissement (+)' : 'Dépense / Sortie (-)' }}
                            </span>
                        </h3>
                    </div>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">✕</button>
                </div>

                <!-- Financial Impact Summary Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                    <span class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider block">
                        Historique du Solde Caisse (Impact Financier)
                    </span>
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-xs">
                            <span class="text-[10px] font-extrabold text-slate-400 block uppercase">Solde Avant</span>
                            <span class="text-xs font-black text-slate-800 font-mono">
                                {{ number_format($ledgerBalances[$selectedLedger->id]['before'] ?? 0, 2) }} DH
                            </span>
                        </div>
                        <div class="p-2.5 rounded-xl border shadow-xs {{ $selectedLedger->entry_type === 'credit' ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200' }}">
                            <span class="text-[10px] font-extrabold {{ $selectedLedger->entry_type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }} block uppercase">Montant</span>
                            <span class="text-xs font-black {{ $selectedLedger->entry_type === 'credit' ? 'text-emerald-700' : 'text-rose-700' }} font-mono">
                                {{ $selectedLedger->entry_type === 'credit' ? '+' : '-' }}{{ number_format($selectedLedger->amount, 2) }} DH
                            </span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-xs">
                            <span class="text-[10px] font-extrabold text-slate-400 block uppercase">Solde Après</span>
                            <span class="text-xs font-black {{ ($ledgerBalances[$selectedLedger->id]['after'] ?? 0) < 0 ? 'text-rose-600' : 'text-emerald-700' }} font-mono">
                                {{ number_format($ledgerBalances[$selectedLedger->id]['after'] ?? 0, 2) }} DH
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Transaction Details Grid -->
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Date & Heure</span>
                        <span class="font-mono font-bold text-slate-800 block">{{ $selectedLedger->entry_date ? $selectedLedger->entry_date->format('d/m/Y H:i') : '-' }}</span>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Mode de Paiement</span>
                        <span class="font-mono font-bold text-slate-800 uppercase block">{{ $selectedLedger->payment_method }}</span>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Catégorie Comptable</span>
                        <span class="font-bold text-slate-800 capitalize block">{{ str_replace('_', ' ', $selectedLedger->category ?: 'Opération') }}</span>
                    </div>

                    <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">N° Reçu Officiel</span>
                        <span class="font-mono font-bold text-indigo-600 block">{{ $selectedLedger->receipt_number ?: 'N/A' }}</span>
                    </div>

                    <div class="col-span-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Bénéficiaire / Client</span>
                        @if($selectedLedger->client)
                            <div class="font-bold text-slate-900">{{ $selectedLedger->client->first_name }} {{ $selectedLedger->client->last_name }}</div>
                            <span class="text-[10px] text-slate-500 font-mono block">CIN: {{ $selectedLedger->client->cin ?? '-' }}</span>
                        @else
                            <span class="text-slate-600 font-medium block">Opération Générale Agence / Caisse</span>
                        @endif
                    </div>

                    @if($selectedLedger->notes)
                        <div class="col-span-2 bg-slate-50 p-2.5 rounded-xl border border-slate-200">
                            <span class="text-[10px] font-bold text-slate-400 block uppercase">Motif / Remarques</span>
                            <p class="text-slate-700 text-xs font-normal mt-0.5">{{ $selectedLedger->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between items-center pt-3 border-t">
                    <a href="{{ url('/admin/payments-center/' . $selectedLedger->id . '/receipt') }}" target="_blank" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-xl font-bold text-xs inline-flex items-center gap-1.5 transition">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Imprimer Reçu PDF</span>
                    </a>
                    <button type="button" wire:click="closeDetailModal" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs shadow-xs">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
