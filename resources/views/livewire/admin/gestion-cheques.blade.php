<div class="space-y-5">

    {{-- ═══ HEADER ═══════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 text-white p-6 rounded-3xl shadow-xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="p-2.5 bg-indigo-500/20 text-indigo-400 rounded-xl border border-indigo-500/30">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" /><path d="M12 6v12" />
                    </svg>
                </span>
                <h1 class="text-2xl font-black tracking-tight">Portefeuille des Chèques</h1>
            </div>
            <p class="text-slate-400 text-xs pl-12">Suivi complet · Versement · Encaissement bancaire · Sélection en masse</p>
        </div>
        <div class="flex items-center gap-2">
            @if(count($selectedIds) > 0)
                <button wire:click="openBulkModal"
                    class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black transition flex items-center gap-2 shadow-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Action groupée ({{ count($selectedIds) }})
                </button>
            @endif
            <button wire:click="clearFilters" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-slate-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Réinitialiser
            </button>
            <button wire:click="$refresh" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl text-xs font-bold transition flex items-center gap-2 border border-slate-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualiser
            </button>
        </div>
    </div>

    {{-- ═══ KPI CARDS ══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
        {{-- Tous --}}
        <div wire:click="$set('filterStatus', '')" class="cursor-pointer bg-white p-4 rounded-2xl border border-slate-200 shadow-xs hover:border-slate-400 transition-all group {{ empty($filterStatus) ? 'ring-2 ring-slate-400' : '' }}">
            <div class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-2">Total Portefeuille</div>
            <div class="text-xl font-black text-slate-900 font-mono">{{ number_format($totalAmount, 0) }} <span class="text-xs text-slate-400">DH</span></div>
            <div class="text-xs font-bold text-slate-500 mt-1">{{ $totalCount }} chèque(s)</div>
        </div>
        {{-- En Attente --}}
        <div wire:click="$set('filterStatus', 'pending')" class="cursor-pointer bg-white p-4 rounded-2xl border border-amber-200 shadow-xs hover:border-amber-400 transition-all group {{ $filterStatus === 'pending' ? 'ring-2 ring-amber-400 bg-amber-50' : '' }}">
            <div class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider mb-2">🟡 En Attente</div>
            <div class="text-xl font-black text-amber-700 font-mono">{{ number_format($pendingAmount, 0) }} <span class="text-xs text-amber-400">DH</span></div>
            <div class="text-xs font-bold text-amber-600 mt-1">{{ $pendingCount }} à verser</div>
        </div>
        {{-- Versés --}}
        <div wire:click="$set('filterStatus', 'deposited')" class="cursor-pointer bg-white p-4 rounded-2xl border border-blue-200 shadow-xs hover:border-blue-400 transition-all group {{ $filterStatus === 'deposited' ? 'ring-2 ring-blue-400 bg-blue-50' : '' }}">
            <div class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider mb-2">🏛️ Versés / Déposés</div>
            <div class="text-xl font-black text-blue-700 font-mono">{{ number_format($depositedAmount, 0) }} <span class="text-xs text-blue-400">DH</span></div>
            <div class="text-xs font-bold text-blue-600 mt-1">{{ $depositedCount }} en banque</div>
        </div>
        {{-- Encaissés (Dakhlat) --}}
        <div wire:click="$set('filterPaymentReceived', $filterPaymentReceived === 'yes' ? '' : 'yes')" class="cursor-pointer bg-white p-4 rounded-2xl border border-emerald-200 shadow-xs hover:border-emerald-400 transition-all {{ $filterPaymentReceived === 'yes' ? 'ring-2 ring-emerald-500 bg-emerald-50' : '' }}">
            <div class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider mb-2">✅ Encaissés (Dakhlat)</div>
            <div class="text-xl font-black text-emerald-700 font-mono">{{ number_format($collectedAmount, 0) }} <span class="text-xs text-emerald-400">DH</span></div>
            <div class="text-xs font-bold text-emerald-600 mt-1">{{ $collectedCount }} crédités</div>
        </div>
        {{-- Rejetés --}}
        <div wire:click="$set('filterStatus', 'returned')" class="cursor-pointer bg-white p-4 rounded-2xl border border-rose-200 shadow-xs hover:border-rose-400 transition-all {{ $filterStatus === 'returned' ? 'ring-2 ring-rose-400 bg-rose-50' : '' }}">
            <div class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider mb-2">❌ Impayés / Rejetés</div>
            <div class="text-xl font-black text-rose-700 font-mono">{{ number_format($returnedAmount, 0) }} <span class="text-xs text-rose-400">DH</span></div>
            <div class="text-xs font-bold text-rose-600 mt-1">{{ $returnedCount }} rejetés</div>
        </div>
    </div>

    {{-- ═══ FILTERS BAR ════════════════════════════════════════════════════════════ --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs space-y-3">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Search --}}
            <div class="relative flex-1 min-w-52">
                <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="N° Chèque, Client, Contrat, Banque..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all outline-none">
            </div>

            {{-- Bank Filter --}}
            <div class="min-w-36">
                <select wire:model.live="filterBank"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                    <option value="">🏦 Toutes les banques</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank }}">{{ $bank }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Received Filter --}}
            <div class="min-w-44">
                <select wire:model.live="filterPaymentReceived"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                    <option value="">💰 Tous (Dakhlat + Non)</option>
                    <option value="yes">✅ Flous Dakhlat (Encaissés)</option>
                    <option value="no">⏳ Flous Madakhlouch (En attente)</option>
                </select>
            </div>

            {{-- Date From --}}
            <div class="min-w-36">
                <input wire:model.live="filterDateFrom" type="date"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"
                    title="Date d'échéance - Depuis">
            </div>

            {{-- Date To --}}
            <div class="min-w-36">
                <input wire:model.live="filterDateTo" type="date"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"
                    title="Date d'échéance - Jusqu'au">
            </div>
        </div>

        {{-- Status Quick Tabs --}}
        <div class="flex items-center gap-1 overflow-x-auto pb-0.5">
            <span class="text-[10px] font-extrabold text-slate-400 uppercase mr-1 shrink-0">Statut :</span>
            <button wire:click="$set('filterStatus', '')" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition whitespace-nowrap {{ empty($filterStatus) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Tous</button>
            <button wire:click="$set('filterStatus', 'pending')" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition whitespace-nowrap {{ $filterStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">🟡 En Attente</button>
            <button wire:click="$set('filterStatus', 'deposited')" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition whitespace-nowrap {{ $filterStatus === 'deposited' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200' }}">🏛️ Versés / Déposés</button>
            <button wire:click="$set('filterStatus', 'collected')" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition whitespace-nowrap {{ $filterStatus === 'collected' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">✅ Encaissés</button>
            <button wire:click="$set('filterStatus', 'returned')" class="px-3 py-1.5 rounded-xl text-[11px] font-bold transition whitespace-nowrap {{ $filterStatus === 'returned' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">❌ Impayés</button>
        </div>

        {{-- Active filters summary + bulk selection banner --}}
        @if(count($selectedIds) > 0)
            <div class="flex items-center justify-between bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-2.5">
                <span class="text-xs font-bold text-indigo-700">
                    <span class="text-indigo-900 font-black">{{ count($selectedIds) }}</span> chèque(s) sélectionné(s)
                    &nbsp;·&nbsp;
                    Total sélection: <span class="font-mono font-black text-indigo-900">{{ number_format(Cheque::whereIn('id', $selectedIds)->sum('amount'), 2) }} DH</span>
                </span>
                <div class="flex items-center gap-2">
                    <button wire:click="openBulkModal" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-[11px] font-black transition">
                        🗂 Action groupée
                    </button>
                    <button wire:click="$set('selectedIds', [])" class="px-2.5 py-1.5 text-indigo-600 hover:text-indigo-900 text-[11px] font-bold transition">
                        Désélectionner tout
                    </button>
                </div>
            </div>
        @elseif(!empty($filterStatus) || !empty($filterBank) || !empty($filterPaymentReceived) || !empty($filterDateFrom) || !empty($filterDateTo) || !empty($search))
            <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-2">
                <span class="text-xs font-bold text-slate-600">
                    Résultat filtré: <span class="font-black text-slate-900">{{ $filteredTotal }} chèque(s)</span>
                    pour un montant de <span class="font-mono font-black text-indigo-700">{{ number_format($filteredAmount, 2) }} DH</span>
                </span>
                <button wire:click="clearFilters" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 transition">✕ Effacer les filtres</button>
            </div>
        @endif
    </div>

    {{-- ═══ TABLE ═══════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" wire:model="selectAll"
                                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="py-3 px-4">N° Chèque &amp; Banque</th>
                        <th class="py-3 px-4">Client / Émetteur</th>
                        <th class="py-3 px-4">Contrat</th>
                        <th class="py-3 px-4 text-right">Montant (DH)</th>
                        <th class="py-3 px-4">Échéance / Versement Prévu</th>
                        <th class="py-3 px-4">Date Versement Effectif</th>
                        <th class="py-3 px-4 text-center">Statut</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                    @forelse($cheques as $chq)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ in_array((string)$chq->id, $selectedIds) ? 'bg-indigo-50/60' : '' }}">

                            {{-- Checkbox --}}
                            <td class="py-3 px-4">
                                <input type="checkbox" value="{{ $chq->id }}" wire:model="selectedIds"
                                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </td>

                            {{-- N° Chèque & Banque --}}
                            <td class="py-3 px-4">
                                <div class="font-mono font-black text-indigo-600 text-sm">N° {{ $chq->cheque_number }}</div>
                                <div class="text-[11px] font-semibold text-slate-500 flex items-center gap-1">
                                    🏦 {{ $chq->bank_name ?? '—' }}
                                    @if($chq->agency)
                                        <span class="text-slate-400">· {{ $chq->agency }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Client --}}
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $chq->client?->nom_complet ?? $chq->issuer ?? '—' }}</div>
                                @if($chq->client?->cin)
                                    <div class="text-[10px] text-slate-400 font-mono">CIN: {{ $chq->client->cin }}</div>
                                @endif
                            </td>

                            {{-- Contrat --}}
                            <td class="py-3 px-4">
                                @if($chq->contract)
                                    <span class="font-mono font-bold bg-slate-100 text-slate-800 px-2 py-0.5 rounded border border-slate-200 text-[11px]">
                                        {{ $chq->contract->numero_contrat }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Non lié</span>
                                @endif
                            </td>

                            {{-- Montant --}}
                            <td class="py-3 px-4 text-right">
                                <span class="font-mono font-extrabold text-slate-900 text-sm">
                                    {{ number_format($chq->amount, 2) }}
                                </span>
                            </td>

                            {{-- Échéance --}}
                            <td class="py-3 px-4">
                                @if($chq->due_date)
                                    <div class="font-mono font-bold flex items-center gap-1 {{ $chq->due_date->isPast() && in_array($chq->status, ['received', 'pending', 'created']) ? 'text-amber-700' : 'text-slate-700' }}">
                                        📅 {{ $chq->due_date->format('d/m/Y') }}
                                    </div>
                                    @if($chq->due_date->isPast() && in_array($chq->status, ['received', 'pending', 'created']))
                                        <span class="text-[9px] font-extrabold text-amber-700 uppercase bg-amber-50 px-1.5 py-0.5 rounded">⚠ Échéance atteinte</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- Date Versement Effectif --}}
                            <td class="py-3 px-4">
                                @if($chq->deposit_date)
                                    <div class="font-mono text-blue-700 font-bold">✓ {{ $chq->deposit_date->format('d/m/Y') }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Pas encore versé</span>
                                @endif
                                @if($chq->collection_date)
                                    <div class="text-[10px] text-emerald-600 font-bold">Encaissé: {{ $chq->collection_date->format('d/m/Y') }}</div>
                                @endif
                            </td>

                            {{-- Statut Badge --}}
                            <td class="py-3 px-4 text-center">
                                @if(in_array($chq->status, ['received', 'pending', 'created']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 whitespace-nowrap">
                                        🟡 En Attente
                                    </span>
                                @elseif($chq->status === 'deposited')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200 whitespace-nowrap">
                                        🏛️ Versé / Déposé
                                    </span>
                                @elseif(in_array($chq->status, ['collected', 'validated']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 whitespace-nowrap">
                                        ✅ Encaissé (Dakhlat)
                                    </span>
                                @elseif(in_array($chq->status, ['returned', 'rejected']))
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200 whitespace-nowrap">
                                        ❌ Impayé / Rejeté
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 whitespace-nowrap">
                                        {{ strtoupper($chq->status) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(in_array($chq->status, ['received', 'pending', 'created']))
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'deposited')"
                                            class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold shadow-xs transition whitespace-nowrap">
                                            🏛️ Déposer
                                        </button>
                                    @elseif($chq->status === 'deposited')
                                        <button wire:click="quickSetStatus({{ $chq->id }}, 'collected')"
                                            class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-xs transition whitespace-nowrap">
                                            ✅ Encaisser
                                        </button>
                                        <button wire:click="quickSetStatus({{ $chq->id }}, 'returned')"
                                            class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[11px] font-bold transition whitespace-nowrap">
                                            ❌ Rejeté
                                        </button>
                                    @elseif(in_array($chq->status, ['collected', 'validated']))
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'collected')"
                                            class="px-2 py-1 text-slate-500 hover:text-slate-800 text-[10px] font-bold transition">
                                            ✏️ Modifier
                                        </button>
                                    @else
                                        <button wire:click="openStatusModal({{ $chq->id }}, 'deposited')"
                                            class="px-2 py-1 text-slate-500 hover:text-slate-800 text-[10px] font-bold transition">
                                            🔄 Changer
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <div class="text-4xl mb-3">📂</div>
                                <div class="text-slate-500 font-bold text-sm">Aucun chèque trouvé</div>
                                <div class="text-slate-400 text-xs mt-1">Modifiez vos filtres ou ajoutez un règlement par chèque</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cheques->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $cheques->links() }}
            </div>
        @endif
    </div>

    {{-- ═══ SINGLE STATUS MODAL ════════════════════════════════════════════════════ --}}
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-black text-slate-900 text-base">Mise à jour · Chèque N° {{ $selectedChequeNumber }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">✕</button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nouveau Statut</label>
                        <select wire:model.live="newStatus" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                            <option value="received">🟡 En Attente de Versement (Portefeuille)</option>
                            <option value="deposited">🏛️ Versé / Déposé en Banque</option>
                            <option value="collected">✅ Encaissé / Crédité (Dakhlat)</option>
                            <option value="returned">❌ Impayé / Rejeté</option>
                        </select>
                    </div>
                    @if($newStatus === 'deposited' || $newStatus === 'collected')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Date du Versement / Dépôt</label>
                            <input wire:model="depositDate" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Notes (Optionnel)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Ex: Bordereau de remise n° 889221..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"></textarea>
                    </div>
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button wire:click="closeModal" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">Annuler</button>
                    <button wire:click="saveStatusUpdate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md">Enregistrer</button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ BULK ACTION MODAL ═══════════════════════════════════════════════════════ --}}
    @if($showBulkModal)
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-5 shadow-2xl border border-slate-100">
                <div class="flex justify-between items-center border-b pb-3">
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Action groupée</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ count($selectedIds) }} chèque(s) sélectionné(s)</p>
                    </div>
                    <button wire:click="closeBulkModal" class="text-slate-400 hover:text-slate-600 p-1 text-lg leading-none">✕</button>
                </div>

                {{-- Summary of selected --}}
                <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100">
                    <div class="text-xs font-bold text-indigo-700">
                        Montant total concerné:
                        <span class="font-mono font-black text-indigo-900 text-sm ml-1">
                            {{ number_format(Cheque::whereIn('id', $selectedIds)->sum('amount'), 2) }} DH
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Appliquer le statut à tous</label>
                        <select wire:model.live="bulkNewStatus" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                            <option value="received">🟡 En Attente de Versement (Portefeuille)</option>
                            <option value="deposited">🏛️ Versé / Déposé en Banque</option>
                            <option value="collected">✅ Encaissé / Crédité (Dakhlat)</option>
                            <option value="returned">❌ Impayé / Rejeté</option>
                        </select>
                    </div>
                    @if($bulkNewStatus === 'deposited' || $bulkNewStatus === 'collected')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Date du Versement / Dépôt (pour tous)</label>
                            <input wire:model="bulkDepositDate" type="date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none">
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Notes (Optionnel — appliqué à tous)</label>
                        <textarea wire:model="bulkNotes" rows="2" placeholder="Ex: Remise groupée de chèques..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-800 focus:bg-white focus:border-indigo-500 outline-none"></textarea>
                    </div>
                </div>

                @if($bulkNewStatus === 'collected')
                    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-800 font-semibold">
                        ⚠️ <strong>Attention:</strong> Le solde du compte bancaire sera incrémenté du montant total des chèques sélectionnés.
                    </div>
                @endif

                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                    <button wire:click="closeBulkModal" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">Annuler</button>
                    <button wire:click="saveBulkUpdate" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition shadow-md">
                        Appliquer à {{ count($selectedIds) }} chèque(s)
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
