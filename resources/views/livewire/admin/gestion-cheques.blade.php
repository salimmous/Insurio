<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 text-white p-6 rounded-3xl shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="p-2 bg-indigo-500/20 text-indigo-400 rounded-xl border border-indigo-500/30">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                        <path d="M12 6v12" />
                    </svg>
                </span>
                <h1 class="text-2xl font-black tracking-tight">Portefeuille des Chèques Marocains</h1>
            </div>
            <p class="text-slate-400 text-xs pl-11">
                Suivi du dépôt, dates d'échéance / versement et encaissement bancaire (Attijariwafa, BCP, BMCE, CIH, SGMB, CDM...)
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="$refresh" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualiser
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- En Attente -->
        <div wire:click="$set('filterStatus', 'pending')" class="cursor-pointer bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:border-amber-400 transition-all group">
            <div class="flex justify-between items-start">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">En Attente de Versement</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform">🟡</span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900 font-mono block">{{ number_format($pendingAmount, 2) }} <span class="text-xs font-bold text-slate-400">DH</span></span>
                <span class="text-xs font-bold text-amber-700 mt-1 inline-block bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200">
                    {{ $pendingCount }} chèque(s) à verser
                </span>
            </div>
        </div>

        <!-- Versés en Banque -->
        <div wire:click="$set('filterStatus', 'deposited')" class="cursor-pointer bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:border-blue-400 transition-all group">
            <div class="flex justify-between items-start">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Versés / En Banque</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">🏛️</span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900 font-mono block">{{ number_format($depositedAmount, 2) }} <span class="text-xs font-bold text-slate-400">DH</span></span>
                <span class="text-xs font-bold text-blue-700 mt-1 inline-block bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-200">
                    {{ $depositedCount }} chèque(s) en cours de compensation
                </span>
            </div>
        </div>

        <!-- Encaissés -->
        <div wire:click="$set('filterStatus', 'collected')" class="cursor-pointer bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:border-emerald-400 transition-all group">
            <div class="flex justify-between items-start">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Encaissés / Crédités</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">✅</span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-emerald-600 font-mono block">{{ number_format($collectedAmount, 2) }} <span class="text-xs font-bold text-slate-400">DH</span></span>
                <span class="text-xs font-bold text-emerald-700 mt-1 inline-block bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200">
                    {{ $collectedCount }} chèque(s) validé(s)
                </span>
            </div>
        </div>

        <!-- Impayés / Rejetés -->
        <div wire:click="$set('filterStatus', 'returned')" class="cursor-pointer bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:border-rose-400 transition-all group">
            <div class="flex justify-between items-start">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Impayés / Rejetés</span>
                <span class="p-2 bg-rose-50 text-rose-600 rounded-xl group-hover:scale-110 transition-transform">❌</span>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-rose-600 font-mono block">{{ number_format($returnedAmount, 2) }} <span class="text-xs font-bold text-slate-400">DH</span></span>
                <span class="text-xs font-bold text-rose-700 mt-1 inline-block bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-200">
                    {{ $returnedCount }} chèque(s) en rejet
                </span>
            </div>
        </div>
    </div>

    <!-- Controls Bar (Search & Status Filter) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative w-full md:w-96">
            <svg class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par N° Chèque, Client, Contrat, Banque..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all outline-none">
        </div>

        <!-- Filter Status Tabs -->
        <div class="flex items-center gap-1 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <button wire:click="$set('filterStatus', '')" class="px-3 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ empty($filterStatus) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Tous ({{ $totalCount }})
            </button>
            <button wire:click="$set('filterStatus', 'pending')" class="px-3 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filterStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
                En Attente
            </button>
            <button wire:click="$set('filterStatus', 'deposited')" class="px-3 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filterStatus === 'deposited' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200' }}">
                Versés / Déposés
            </button>
            <button wire:click="$set('filterStatus', 'collected')" class="px-3 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filterStatus === 'collected' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
                Encaissés
            </button>
            <button wire:click="$set('filterStatus', 'returned')" class="px-3 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filterStatus === 'returned' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">
                Impayés
            </button>
        </div>
    </div>

    <!-- Table of Cheques -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                        <th class="py-3.5 px-4">N° Chèque & Banque</th>
                        <th class="py-3.5 px-4">Client / Émetteur</th>
                        <th class="py-3.5 px-4">Contrat Assuré</th>
                        <th class="py-3.5 px-4 text-right">Montant (DH)</th>
                        <th class="py-3.5 px-4">Échéance / Versement Prévu</th>
                        <th class="py-3.5 px-4">Date Versement Effectif</th>
                        <th class="py-3.5 px-4 text-center">Statut du Chèque</th>
                        <th class="py-3.5 px-4 text-right">Actions / Versement</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($cheques as $chq)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- N° Chèque & Banque -->
                            <td class="py-3.5 px-4">
                                <div class="font-mono font-bold text-indigo-600 text-sm">N° {{ $chq->cheque_number }}</div>
                                <div class="text-[11px] font-semibold text-slate-500">{{ $chq->bank_name ?? 'Banque Maroc' }}</div>
                            </td>

                            <!-- Client -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $chq->client?->nom_complet ?? $chq->issuer }}</div>
                                @if($chq->client?->cin)
                                    <div class="text-[10px] text-slate-400 font-mono">CIN: {{ $chq->client->cin }}</div>
                                @endif
                            </td>

                            <!-- Contrat -->
                            <td class="py-3.5 px-4">
                                @if($chq->contract)
                                    <span class="font-mono font-bold bg-slate-100 text-slate-800 px-2 py-0.5 rounded border border-slate-200 text-[11px]">
                                        {{ $chq->contract->numero_contrat }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Non associé</span>
                                @endif
                            </td>

                            <!-- Montant -->
                            <td class="py-3.5 px-4 text-right">
                                <span class="font-mono font-extrabold text-slate-900 text-sm">
                                    {{ number_format($chq->amount, 2) }}
                                </span>
                            </td>

                            <!-- Échéance / Versement Prévu -->
                            <td class="py-3.5 px-4">
                                @if($chq->due_date)
                                    <div class="font-mono font-bold flex items-center gap-1.5 {{ $chq->due_date->isPast() && in_array($chq->status, ['received', 'pending']) ? 'text-amber-700' : 'text-slate-700' }}">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $chq->due_date->format('d/m/Y') }}
                                    </div>
                                    @if($chq->due_date->isPast() && in_array($chq->status, ['received', 'pending']))
                                        <span class="text-[9px] font-extrabold text-amber-700 uppercase bg-amber-50 px-1.5 py-0.5 rounded">Échéance atteinte</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Date Versement Effectif -->
                            <td class="py-3.5 px-4">
                                @if($chq->deposit_date)
                                    <div class="font-mono text-slate-700 font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $chq->deposit_date->format('d/m/Y') }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Pas encore versé</span>
                                @endif
                            </td>

                            <!-- Statut Badge -->
                            <td class="py-3.5 px-4 text-center">
                                @if(in_array($chq->status, ['received', 'pending', 'created']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                        🟡 En Attente
                                    </span>
                                @elseif($chq->status === 'deposited')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                        🏛️ Versé / Déposé
                                    </span>
                                @elseif(in_array($chq->status, ['collected', 'validated']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        ✅ Encaissé (Versé)
                                    </span>
                                @elseif(in_array($chq->status, ['returned', 'rejected']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                        ❌ Impayé / Rejeté
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                        {{ strtoupper($chq->status) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(in_array($chq->status, ['received', 'pending', 'created']))
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'deposited')" class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1">
                                            🏛️ Déposer / Verser
                                        </button>
                                    @elseif($chq->status === 'deposited')
                                        <button wire:click="quickSetStatus({{ $chq->id }}, 'collected')" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-2xs transition flex items-center gap-1">
                                            ✅ Marquer Encaissé
                                        </button>
                                        <button wire:click="quickSetStatus({{ $chq->id }}, 'returned')" class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[11px] font-bold transition">
                                            ❌ Rejeté
                                        </button>
                                    @elseif(in_array($chq->status, ['collected', 'validated']))
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'collected')" class="px-2 py-1 text-slate-500 hover:text-slate-800 text-[10px] font-bold transition">
                                            ✏️ Modifier
                                        </button>
                                    @else
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'deposited')" class="px-2 py-1 text-slate-500 hover:text-slate-800 text-[10px] font-bold transition">
                                            🔄 Changer Statut
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 text-xs">
                                Aucun chèque trouvé dans ce portefeuille.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cheques->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $cheques->links() }}
            </div>
        @endif
    </div>

    <!-- Status Change Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-slate-100 animate-in fade-in zoom-in duration-150">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-black text-slate-900 text-base">Mettre à jour le chèque N° {{ $selectedChequeNumber }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1 text-lg">✕</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Statut du Chèque</label>
                        <select wire:model.live="newStatus" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                            <option value="received">🟡 En Attente de Versement (Portefeuille)</option>
                            <option value="deposited">🏛️ Versé / Déposé en Banque</option>
                            <option value="collected">✅ Encaissé / Crédité en Compte (Dakhlat)</option>
                            <option value="returned">❌ Impayé / Rejeté par la Banque</option>
                        </select>
                    </div>

                    @if($newStatus === 'deposited' || $newStatus === 'collected')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Date du Versement / Dépôt Effectif</label>
                            <input wire:model="depositDate" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Notes / Remarques (Optionnel)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Ex: Bordereau de remise n° 889221..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button wire:click="closeModal" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                        Annuler
                    </button>
                    <button wire:click="saveStatusUpdate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md">
                        Enregistrer la mise à jour
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
