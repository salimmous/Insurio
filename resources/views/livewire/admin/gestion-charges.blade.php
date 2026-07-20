<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Header & Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Gestion des Charges Agence</h1>
                <p class="text-xs text-slate-500 mt-1">Suivez et contrôlez les dépenses de vos succursales (Loyer, Eau, Électricité, Salaires, etc.)</p>
            </div>
            <button wire:click="openCreateModal" class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-teal-900/10 flex items-center gap-2 hover:-translate-y-0.5">
                <span>➕</span> Nouvelle Charge
            </button>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border border-emerald-150 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total Expenses -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Dépenses</span>
                <div class="text-xl font-black text-slate-800 font-mono">
                    {{ number_format($expenses->sum('amount'), 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Somme cumulée des charges</span>
            </div>

            <!-- Loyers -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28 border-l-4 border-l-blue-500">
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-500">Loyers</span>
                <div class="text-xl font-black text-slate-800 font-mono">
                    {{ number_format($expenses->where('category', 'loyer')->sum('amount'), 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Bureaux et locaux</span>
            </div>

            <!-- Électricité & Eau -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28 border-l-4 border-l-amber-500">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-500">Électricité & Eau</span>
                <div class="text-xl font-black text-slate-800 font-mono">
                    {{ number_format($expenses->whereIn('category', ['electricite', 'eau'])->sum('amount'), 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Factures eau et électricité</span>
            </div>

            <!-- Salaires -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28 border-l-4 border-l-teal-500">
                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-500">Salaires</span>
                <div class="text-xl font-black text-slate-800 font-mono">
                    {{ number_format($expenses->where('category', 'salaire')->sum('amount'), 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Collaborateurs et agents</span>
            </div>

            <!-- Autres -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-28 border-l-4 border-l-purple-500">
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-500">Autres Charges</span>
                <div class="text-xl font-black text-slate-800 font-mono">
                    {{ number_format($expenses->where('category', 'autre')->sum('amount'), 2) }} DH
                </div>
                <span class="text-[9px] text-slate-400 font-medium">Dépenses diverses</span>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="flex flex-col md:flex-row gap-4 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
            <div class="flex-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Filtrer par catégorie</label>
                <select wire:model.live="filterCategory" class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3 py-2 text-xs outline-none transition-all">
                    <option value="">Toutes les catégories</option>
                    <option value="loyer">Loyer</option>
                    <option value="electricite">Électricité</option>
                    <option value="eau">Eau</option>
                    <option value="salaire">Salaire</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Filtrer par succursale</label>
                <select wire:model.live="filterSuccursale" class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3 py-2 text-xs outline-none transition-all">
                    <option value="">Toutes les succursales</option>
                    @foreach($succursales as $suc)
                        <option value="{{ $suc->id }}">{{ $suc->nom }} ({{ $suc->ville }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Main Ledger Table Card -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 min-w-[800px]" style="min-width: 800px;">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-4">Libellé de la Charge</th>
                            <th class="px-5 py-4">Catégorie</th>
                            <th class="px-5 py-4">Succursale / Bureau</th>
                            <th class="px-5 py-4">Montant (DH)</th>
                            <th class="px-5 py-4">Date de Paiement</th>
                            <th class="px-5 py-4">Description</th>
                            <th class="px-5 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs">
                        @forelse($expenses as $exp)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 font-bold text-slate-800 text-sm">{{ $exp->title }}</td>
                                <td class="px-5 py-4">
                                    @if($exp->category == 'loyer')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Loyer</span>
                                    @elseif($exp->category == 'electricite')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Électricité</span>
                                    @elseif($exp->category == 'eau')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 uppercase">Eau</span>
                                    @elseif($exp->category == 'salaire')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200 uppercase">Salaire</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-50 text-slate-700 border border-slate-200 uppercase">Autre</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if($exp->succursale)
                                        <span class="text-slate-700 font-semibold">{{ $exp->succursale->nom }}</span>
                                        <span class="block text-[10px] text-slate-400 font-mono mt-0.5">{{ $exp->succursale->ville }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Siège Global</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-slate-800">{{ number_format($exp->amount, 2) }} DH</td>
                                <td class="px-5 py-4 font-mono text-slate-500">{{ $exp->date_charge->format('d/m/Y') }}</td>
                                <td class="px-5 py-4 text-slate-450 max-w-[200px] truncate" title="{{ $exp->description }}">{{ $exp->description ?: 'Aucune description' }}</td>
                                <td class="px-5 py-4 text-right space-x-2">
                                    <button wire:click="edit({{ $exp->id }})" class="text-indigo-650 hover:text-indigo-900 font-semibold">Modifier</button>
                                    <button onclick="confirm('Voulez-vous supprimer cette charge ?') || event.stopImmediatePropagation()" wire:click="delete({{ $exp->id }})" class="text-rose-600 hover:text-rose-900 font-semibold">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 text-sm">
                                    Aucune charge enregistrée pour cette période.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal (Sleek design matching central dashboard) -->
        @if($showModal)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 animate-fade-in">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <!-- Modal body -->
                    <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-6 py-5 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-800" id="modal-title">
                                {{ $isEditing ? 'Modifier la charge' : 'Enregistrer une nouvelle charge' }}
                            </h3>
                        </div>
                        <form wire:submit.prevent="save" class="p-6 flex flex-col gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-450 mb-1.5">Intitulé / Libellé de la charge</label>
                                <input type="text" wire:model="title" placeholder="Ex: Loyer Local Principal Juillet" class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all placeholder-slate-400">
                                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-450 mb-1.5">Catégorie</label>
                                    <select wire:model="category" class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all text-slate-700">
                                        <option value="loyer">Loyer</option>
                                        <option value="electricite">Électricité</option>
                                        <option value="eau">Eau</option>
                                        <option value="salaire">Salaire</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                    @error('category') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Montant (DH)</label>
                                    <input type="number" step="0.01" min="0" wire:model="amount" placeholder="Ex: 1200" class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all placeholder-slate-400 font-mono">
                                    @error('amount') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Date de Paiement</label>
                                    <input type="date" wire:model="date_charge" class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all text-slate-700">
                                    @error('date_charge') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Succursale (Optionnel)</label>
                                    <select wire:model="succursale_id" class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all text-slate-700">
                                        <option value="">Siège Global / Non Spécifié</option>
                                        @foreach($succursales as $suc)
                                            <option value="{{ $suc->id }}">{{ $suc->nom }} ({{ $suc->ville }})</option>
                                        @endforeach
                                    </select>
                                    @error('succursale_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Description / Notes</label>
                                <textarea wire:model="description" rows="2" placeholder="Description facultative..." class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-2.5 outline-none text-sm transition-all placeholder-slate-400"></textarea>
                                @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 -mx-6 -mb-6 border-t border-slate-100">
                                <button type="button" wire:click="$set('showModal', false)" class="bg-white hover:bg-slate-50 text-slate-650 font-bold px-4 py-2 rounded-xl text-xs transition-all border border-slate-200 shadow-sm">
                                    Annuler
                                </button>
                                <button type="submit" class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-bold px-6 py-2 rounded-xl text-xs transition-all shadow-md shadow-teal-900/10">
                                    Valider
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
