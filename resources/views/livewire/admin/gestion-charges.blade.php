<div class="py-6 font-sans space-y-6">
    <!-- Top Header Banner & Quick Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="p-2 bg-rose-50 text-rose-600 rounded-xl border border-rose-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
                <h1 class="text-xl font-black text-slate-900 tracking-tight">Gestion des Charges Agence</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Contrôle analytique et suivi comptable des dépenses d'exploitation (Loyers, Électricité, Eau, Salaires...)</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Solde Caisse: <strong class="font-mono text-slate-900 font-black">{{ number_format(\App\Models\CashRegister::sum('current_balance'), 2) }} DH</strong></span>
            </div>
            <button wire:click="openCreateModal" class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md flex items-center gap-2 hover:-translate-y-0.5">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Nouvelle Charge</span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Quick Stats Cards (5 Analytical Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Expenses -->
        <div class="bg-slate-900 text-white p-4 rounded-2xl border border-slate-800 shadow-md flex flex-col justify-between h-28">
            <div class="flex justify-between items-center text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                <span>Total Dépenses</span>
                <span class="p-1 bg-slate-800 rounded-lg text-slate-300">📊</span>
            </div>
            <div class="text-xl font-black font-mono text-white">
                {{ number_format($expenses->sum('amount'), 2) }} <span class="text-xs font-normal text-slate-400">DH</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Somme cumulée des charges</span>
        </div>

        <!-- Loyers -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between h-28 border-l-4 border-l-blue-500">
            <div class="flex justify-between items-center text-[10px] font-extrabold uppercase tracking-wider text-blue-600">
                <span>Loyers & Locaux</span>
                <span class="p-1 bg-blue-50 text-blue-600 rounded-lg">🏠</span>
            </div>
            <div class="text-xl font-black text-slate-900 font-mono">
                {{ number_format($expenses->where('category', 'loyer')->sum('amount'), 2) }} <span class="text-xs font-normal text-slate-400">DH</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Bureaux & agences</span>
        </div>

        <!-- Électricité & Eau -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between h-28 border-l-4 border-l-amber-500">
            <div class="flex justify-between items-center text-[10px] font-extrabold uppercase tracking-wider text-amber-600">
                <span>Électricité & Eau</span>
                <span class="p-1 bg-amber-50 text-amber-600 rounded-lg">⚡</span>
            </div>
            <div class="text-xl font-black text-slate-900 font-mono">
                {{ number_format($expenses->whereIn('category', ['electricite', 'eau'])->sum('amount'), 2) }} <span class="text-xs font-normal text-slate-400">DH</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Factures d'eau et électricité</span>
        </div>

        <!-- Salaires -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between h-28 border-l-4 border-l-teal-500">
            <div class="flex justify-between items-center text-[10px] font-extrabold uppercase tracking-wider text-teal-600">
                <span>Salaires & Avances</span>
                <span class="p-1 bg-teal-50 text-teal-600 rounded-lg">👥</span>
            </div>
            <div class="text-xl font-black text-slate-900 font-mono">
                {{ number_format($expenses->where('category', 'salaire')->sum('amount'), 2) }} <span class="text-xs font-normal text-slate-400">DH</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Collaborateurs et équipe</span>
        </div>

        <!-- Autres Charges -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between h-28 border-l-4 border-l-purple-500">
            <div class="flex justify-between items-center text-[10px] font-extrabold uppercase tracking-wider text-purple-600">
                <span>Autres Charges</span>
                <span class="p-1 bg-purple-50 text-purple-600 rounded-lg">📦</span>
            </div>
            <div class="text-xl font-black text-slate-900 font-mono">
                {{ number_format($expenses->where('category', 'autre')->sum('amount'), 2) }} <span class="text-xs font-normal text-slate-400">DH</span>
            </div>
            <span class="text-[10px] text-slate-400 font-medium">Dépenses diverses</span>
        </div>
    </div>

    <!-- Filters & Live Search Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <!-- Live Search -->
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Recherche Rapide</label>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par libellé, note..." class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl pl-9 pr-3 py-2 text-xs font-bold text-slate-900 outline-none transition-all placeholder-slate-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Filter Category -->
        <div class="w-full md:w-56">
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Catégorie</label>
            <select wire:model.live="filterCategory" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 outline-none transition-all">
                <option value="">Toutes les catégories</option>
                <option value="loyer">Loyer</option>
                <option value="electricite">Électricité</option>
                <option value="eau">Eau</option>
                <option value="salaire">Salaire</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <!-- Filter Succursale -->
        <div class="w-full md:w-56">
            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-500 mb-1">Succursale / Bureau</label>
            <select wire:model.live="filterSuccursale" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 outline-none transition-all">
                <option value="">Toutes les succursales</option>
                @foreach($succursales as $suc)
                    <option value="{{ $suc->id }}">{{ $suc->nom }} ({{ $suc->ville }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Main Ledger Table Card -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 min-w-[850px]">
                <thead class="bg-slate-100/70 border-b border-slate-200 text-[10px] font-black uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Libellé de la Charge</th>
                        <th class="px-6 py-3.5">Catégorie</th>
                        <th class="px-6 py-3.5">Succursale / Bureau</th>
                        <th class="px-6 py-3.5">Montant (DH)</th>
                        <th class="px-6 py-3.5">Date de Paiement</th>
                        <th class="px-6 py-3.5">Description</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium text-xs">
                    @forelse($expenses as $exp)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-900 block text-sm">{{ $exp->title }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">CHG-{{ $exp->date_charge ? $exp->date_charge->format('Ymd') : '' }}-{{ sprintf('%04d', $exp->id) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($exp->category == 'loyer')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-900 border border-blue-300 uppercase">
                                        <span>🏠</span> Loyer
                                    </span>
                                @elseif($exp->category == 'electricite')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900 border border-amber-300 uppercase">
                                        <span>⚡</span> Électricité
                                    </span>
                                @elseif($exp->category == 'eau')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-sky-100 text-sky-900 border border-sky-300 uppercase">
                                        <span>💧</span> Eau
                                    </span>
                                @elseif($exp->category == 'salaire')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-teal-100 text-teal-900 border border-teal-300 uppercase">
                                        <span>👥</span> Salaire
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-slate-100 text-slate-900 border border-slate-300 uppercase">
                                        <span>📦</span> Autre
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($exp->succursale)
                                    <span class="text-slate-900 font-bold block">{{ $exp->succursale->nom }}</span>
                                    <span class="text-[10px] text-slate-500 font-mono">{{ $exp->succursale->ville }}</span>
                                @else
                                    <span class="text-slate-600 font-bold italic">Siège Global</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono font-black text-rose-600 text-sm block">-{{ number_format($exp->amount, 2) }} DH</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Caisse Agence</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-mono font-bold text-slate-800 border border-slate-200">
                                    {{ $exp->date_charge ? $exp->date_charge->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium max-w-[200px] truncate" title="{{ $exp->description }}">
                                {{ $exp->description ?: 'Aucune description' }}
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2 font-bold">
                                <button wire:click="edit({{ $exp->id }})" class="text-indigo-650 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-lg transition">Modifier</button>
                                <button onclick="confirm('Voulez-vous supprimer cette charge ?') || event.stopImmediatePropagation()" wire:click="delete({{ $exp->id }})" class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 px-2.5 py-1 rounded-lg transition">Supprimer</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400 font-bold text-sm">
                                Aucun enregistrement de charge trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Nouvelle Charge / Modifier) -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-200 animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-base font-black text-slate-900">
                        {{ $isEditing ? 'Modifier la Charge Agence' : 'Enregistrer une Nouvelle Charge Agence' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs font-medium">
                    <!-- Live Caisse Balance Impact Preview -->
                    @php 
                        $caisseBal = \App\Models\CashRegister::sum('current_balance');
                        $expenseAmt = (float)($amount ?? 0);
                        $afterCaisse = $caisseBal - $expenseAmt;
                    @endphp
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2 shadow-xs">
                        <div class="flex justify-between items-center text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">
                            <span>Aperçu Impact Solde Caisse Agence</span>
                            <span class="text-[9px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-200">Comptabilisation Automatique</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-white p-2 rounded-xl border border-slate-200 shadow-xs">
                                <span class="text-[9px] font-extrabold text-slate-400 block uppercase">Solde Caisse</span>
                                <span class="text-xs font-black text-slate-800 font-mono">{{ number_format($caisseBal, 2) }} DH</span>
                            </div>
                            <div class="bg-rose-50 p-2 rounded-xl border border-rose-200 shadow-xs">
                                <span class="text-[9px] font-extrabold text-rose-500 block uppercase">Charge (-)</span>
                                <span class="text-xs font-black text-rose-600 font-mono">- {{ number_format($expenseAmt, 2) }} DH</span>
                            </div>
                            <div class="p-2 rounded-xl border shadow-xs {{ $afterCaisse < 0 ? 'bg-rose-100/80 border-rose-400' : 'bg-emerald-50 border-emerald-300' }}">
                                <span class="text-[9px] font-extrabold {{ $afterCaisse < 0 ? 'text-rose-700' : 'text-emerald-700' }} block uppercase">Nouveau Solde</span>
                                <span class="text-xs font-black {{ $afterCaisse < 0 ? 'text-rose-800' : 'text-emerald-800' }} font-mono">{{ number_format($afterCaisse, 2) }} DH</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Intitulé / Libellé de la charge *</label>
                        <input type="text" wire:model="title" placeholder="Ex: Loyer Local Principal Juillet" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 outline-none transition-all placeholder-slate-400">
                        @error('title') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Catégorie *</label>
                            <select wire:model="category" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 outline-none transition-all">
                                <option value="loyer">Loyer</option>
                                <option value="electricite">Électricité</option>
                                <option value="eau">Eau</option>
                                <option value="salaire">Salaire</option>
                                <option value="autre">Autre</option>
                            </select>
                            @error('category') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Montant (DH) *</label>
                            <input type="number" step="0.01" min="0" wire:model.live="amount" placeholder="Ex: 1200" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 font-mono outline-none transition-all placeholder-slate-400">
                            @error('amount') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Date de Paiement *</label>
                            <input type="date" wire:model="date_charge" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 outline-none transition-all">
                            @error('date_charge') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Succursale (Optionnel)</label>
                            <select wire:model="succursale_id" class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-900 outline-none transition-all">
                                <option value="">Siège Global / Non Spécifié</option>
                                @foreach($succursales as $suc)
                                    <option value="{{ $suc->id }}">{{ $suc->nom }} ({{ $suc->ville }})</option>
                                @endforeach
                            </select>
                            @error('succursale_id') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-700 mb-1">Description / Notes</label>
                        <textarea wire:model="description" rows="2" placeholder="Description facultative..." class="w-full bg-slate-50 border border-slate-300 focus:bg-white focus:border-indigo-600 rounded-xl px-4 py-2.5 text-xs text-slate-900 outline-none transition-all placeholder-slate-400"></textarea>
                        @error('description') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 font-bold">Annuler</button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-teal-700 text-white rounded-xl font-bold shadow-md hover:from-emerald-700 hover:to-teal-800">Valider & Comptabiliser</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
