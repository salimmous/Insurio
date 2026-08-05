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
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-md transition">
                ⚡ Nouvelles Entrée au Grand Livre
            </button>
        </div>
    </div>

    <!-- High-Density Financial KPI Banner -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Recettes du Jour</span>
                <span class="text-2xl font-black text-emerald-600">+{{ number_format($todayRevenue, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Dépenses: {{ number_format($todayExpenses, 2) }} DH</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Solde Caisses Agence</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($cashBalance, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Caisse Principale Ouverte</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Comptes Bancaires</span>
                <span class="text-2xl font-black text-blue-600">{{ number_format($bankBalance, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Attijariwafa, BCP, BMCE</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8l-2-2m0 0l-2 2m2-2v4m4-6l-2-2m0 0l-2 2m2-2v4" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Chèques en Portefeuille</span>
                <span class="text-2xl font-black text-amber-600">{{ number_format($pendingChequesSum, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">{{ $pendingChequesCount }} chèques à déposer</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 text-xs font-bold gap-6 overflow-x-auto pb-2">
        <button wire:click="$set('activeTab', 'ledger')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'ledger' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'ledger' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Grand Livre Comptable</span>
        </button>

        <button wire:click="$set('activeTab', 'cheques')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'cheques' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'cheques' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Chèques Marocains</span>
            @if($pendingChequesCount > 0)
                <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded-full font-mono">{{ $pendingChequesCount }}</span>
            @endif
        </button>

        <button wire:click="$set('activeTab', 'caisses')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'caisses' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'caisses' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Caisses & Coffres</span>
        </button>

        <button wire:click="$set('activeTab', 'banks')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'banks' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'banks' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-4-8l-2-2m0 0l-2 2m2-2v4m4-6l-2-2m0 0l-2 2m2-2v4" />
            </svg>
            <span>Comptes Bancaires & RIB</span>
        </button>

        <button wire:click="$set('activeTab', 'approvals')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'approvals' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'approvals' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>Double Validation</span>
            @if($pendingApprovalsCount > 0)
                <span class="bg-rose-100 text-rose-800 text-[10px] px-2 py-0.5 rounded-full font-mono animate-pulse">{{ $pendingApprovalsCount }}</span>
            @endif
        </button>

        <button wire:click="$set('activeTab', 'audit')" class="pb-2 transition flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'audit' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <svg class="w-4 h-4 shrink-0 {{ $activeTab === 'audit' ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Logs d'Audit Inaltérables</span>
        </button>
    </div>

    <!-- TAB 1: GRAND LIVRE COMPTABLE -->
    @if($activeTab === 'ledger')
        <div class="space-y-4">
            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" wire:model.live="search" placeholder="Rechercher par Transaction ID (TRX-...), N° Reçu, Client, CIN..." class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div class="w-48">
                    <select wire:model.live="filterEntryType" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold">
                        <option value="">Tous les types (Crédit / Débit)</option>
                        <option value="credit">Crédit (Recettes +)</option>
                        <option value="debit">Débit (Dépenses -)</option>
                    </select>
                </div>
                <div class="w-48">
                    <select wire:model.live="filterMethod" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold">
                        <option value="">Tous les modes de paiement</option>
                        <option value="cash">Espèces</option>
                        <option value="cheque">Chèque Marocain</option>
                        <option value="transfer">Virement Bancaire</option>
                        <option value="card">Carte Bancaire / TPE</option>
                    </select>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-left">
                        <tr>
                            <th class="px-6 py-3.5">Transaction ID & Date</th>
                            <th class="px-6 py-3.5">Client / Contrat</th>
                            <th class="px-6 py-3.5">Catégorie</th>
                            <th class="px-6 py-3.5">Mode</th>
                            <th class="px-6 py-3.5">Montant</th>
                            <th class="px-6 py-3.5">Statut</th>
                            <th class="px-6 py-3.5 text-right">Reçu & Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800 font-medium">
                        @forelse($ledgers as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-indigo-600 block">{{ $item->transaction_id }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $item->entry_date->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->client)
                                        <div class="font-bold text-slate-900">{{ $item->client->first_name }} {{ $item->client->last_name }}</div>
                                        <span class="text-[10px] text-slate-400 font-mono">CIN: {{ $item->client->cin ?? '-' }}</span>
                                    @else
                                        <span class="text-slate-400">Opération Générale Agence</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="capitalize font-semibold text-slate-700">{{ str_replace('_', ' ', $item->category) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="uppercase font-mono text-[11px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                        {{ $item->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-sm">
                                    @if($item->entry_type === 'credit')
                                        <span class="text-emerald-600">+{{ number_format($item->amount, 2) }} DH</span>
                                    @else
                                        <span class="text-rose-600">-{{ number_format($item->amount, 2) }} DH</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Validé</span>
                                    @elseif($item->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">En Attente Approval</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Rejeté</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-mono">
                                    <span class="text-[10px] font-bold text-slate-500 block">{{ $item->receipt_number }}</span>
                                    <button class="text-indigo-600 hover:underline font-bold text-[11px]">Imprimer Reçu 🖨️</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    Aucune opération enregistrée dans le Grand Livre.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-200">
                    {{ $ledgers->links() }}
                </div>
            </div>
        </div>

    <!-- TAB 2: CENTRE DE CHÈQUES MAROCAINS -->
    @elseif($activeTab === 'cheques')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="font-black text-lg text-slate-900">Gestion du Portefeuille de Chèques Marocains</h3>
                <span class="text-xs text-slate-500">Suivi Attijariwafa, BCP, BMCE, CIH, SGMB, CDM</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($cheques as $chq)
                    <div class="p-5 border border-slate-200 rounded-2xl bg-slate-50 space-y-4 shadow-2xs">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono font-bold text-indigo-600 block text-sm">N° {{ $chq->cheque_number }}</span>
                                <span class="text-xs font-bold text-slate-800 block">{{ $chq->bank_name }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $chq->status === 'collected' ? 'bg-emerald-100 text-emerald-800' : ($chq->status === 'returned' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $chq->status }}
                            </span>
                        </div>

                        <div class="text-xs space-y-1 text-slate-600">
                            <div><span class="font-bold">Émetteur:</span> {{ $chq->issuer }}</div>
                            <div><span class="font-bold">Échéance:</span> {{ $chq->due_date ? $chq->due_date->format('d/m/Y') : '-' }}</div>
                            <div class="font-mono text-base font-black text-slate-900 pt-1">{{ number_format($chq->amount, 2) }} DH</div>
                        </div>

                        <div class="pt-2 border-t border-slate-200 flex justify-between gap-2">
                            @if($chq->status === 'received' || $chq->status === 'pending')
                                <button wire:click="updateChequeStatus({{ $chq->id }}, 'deposited')" class="w-full bg-blue-600 text-white py-1.5 rounded-lg text-[10px] font-bold">Déposer en Banque 🏛️</button>
                            @elseif($chq->status === 'deposited')
                                <button wire:click="updateChequeStatus({{ $chq->id }}, 'collected')" class="w-full bg-emerald-600 text-white py-1.5 rounded-lg text-[10px] font-bold">Marquer Encaissé ✅</button>
                                <button wire:click="updateChequeStatus({{ $chq->id }}, 'returned')" class="w-full bg-rose-600 text-white py-1.5 rounded-lg text-[10px] font-bold">Marquer Impayé ❌</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-400 text-xs">
                        Aucun chèque enregisté en portefeuille.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- TAB 3: CAISSES & COFFRES -->
    @elseif($activeTab === 'caisses')
        <div class="space-y-6">
            <!-- Header Caisse & Action Buttons -->
            <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-xl space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-800 pb-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">💰</span>
                            <h3 class="font-black text-xl text-white tracking-tight">Caisse Principale Agence</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                SUIVI EN TEMPS RÉEL
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Traçabilité complète de chaque entrée et sortie d'espèces avec solde progressif cumulé.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button wire:click="openCashMovementModal('credit')" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs shadow-lg transition">
                            🟢 + Entrée Espèces
                        </button>
                        <button wire:click="openCashMovementModal('debit')" class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl font-bold text-xs shadow-lg transition">
                            🔴 - Sortie / Retrait Espèces
                        </button>
                    </div>
                </div>

                <!-- Caisse KPI Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($cashRegisters as $reg)
                        <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700 space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Solde Actuel en Caisse</span>
                            <span class="text-2xl font-black font-mono text-emerald-400">{{ number_format($reg->current_balance, 2) }} DH</span>
                            <span class="text-[10px] text-slate-400 block font-mono">Théorique: {{ number_format($reg->expected_balance, 2) }} DH</span>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700 space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dernier Comptage Physique</span>
                            <span class="text-2xl font-black font-mono text-indigo-300">{{ number_format($reg->physical_balance, 2) }} DH</span>
                            <span class="text-[10px] text-slate-400 block font-mono">Physique vérifié</span>
                        </div>
                        <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700 space-y-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Écart de Caisse</span>
                            <span class="text-2xl font-black font-mono {{ $reg->variance_amount < 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ number_format($reg->variance_amount, 2) }} DH
                            </span>
                            <span class="text-[10px] text-slate-400 block">Différence théorique / physique</span>
                        </div>
                    @endforeach

                    <div class="bg-slate-800/80 p-4 rounded-xl border border-slate-700 flex flex-col justify-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Pointage Physique</span>
                        <div class="flex gap-2">
                            <input type="number" step="0.01" wire:model="physical_count_amount" placeholder="Montant physique..." class="w-full bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1.5 text-xs text-white font-mono">
                            <button wire:click="recordPhysicalCashCount" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-bold shrink-0">Valider</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JOURNAL DE CAISSE QUOTIDIEN (FIL D'ACTUALITÉ DÉTAILLÉ PAR JOUR) -->
            <div class="space-y-6">
                <div class="flex justify-between items-center px-1">
                    <h4 class="font-black text-lg text-slate-900 flex items-center gap-2">
                        <span>📖 Journal Quotidien Détaillé de Caisse</span>
                        <span class="text-xs font-normal text-slate-500">(Calcul et suivi du solde après chaque mouvement)</span>
                    </h4>
                </div>

                @forelse($this->cashJournal as $day)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden space-y-0">
                        <!-- Day Banner Header -->
                        <div class="bg-slate-50 border-b border-slate-200 px-6 py-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <div class="flex items-center gap-3">
                                <span class="font-black text-sm text-slate-900 capitalize flex items-center gap-2">
                                    📅 {{ $day['formatted_date'] }}
                                </span>
                                @if($day['is_today'])
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aujourd'hui
                                    </span>
                                @elseif($day['is_yesterday'])
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                                        Hier
                                    </span>
                                @endif
                            </div>

                            <!-- Day Totals Summary Pills -->
                            <div class="flex flex-wrap items-center gap-3 text-xs font-mono font-bold">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl">
                                    Total Entrées (+): +{{ number_format($day['total_in'], 2) }} DH
                                </span>
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl">
                                    Total Sorties (-): -{{ number_format($day['total_out'], 2) }} DH
                                </span>
                                <span class="px-3 py-1 bg-indigo-900 text-white rounded-xl shadow-xs">
                                    Solde Fin de Journée: {{ number_format($day['end_balance'], 2) }} DH
                                </span>
                            </div>
                        </div>

                        <!-- Day Movements Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-xs">
                                <thead class="bg-slate-100/70 text-slate-500 font-bold uppercase tracking-wider text-left">
                                    <tr>
                                        <th class="px-6 py-3">Heure</th>
                                        <th class="px-6 py-3">Type</th>
                                        <th class="px-6 py-3">Motif & Description / Client</th>
                                        <th class="px-6 py-3">N° Reçu</th>
                                        <th class="px-6 py-3">Opérateur</th>
                                        <th class="px-6 py-3">Montant Mouvement</th>
                                        <th class="px-6 py-3 text-right bg-slate-200/50">Solde Caisse Après Opération</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 text-slate-800 font-medium">
                                    @foreach($day['transactions'] as $tx)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-6 py-3.5 font-mono text-slate-500">
                                                {{ $tx->entry_date ? $tx->entry_date->format('H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-3.5 whitespace-nowrap">
                                                @if($tx->entry_type === 'credit')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        <span>🟢</span> Entrée Espèces (+)
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                        <span>🔴</span> Sortie / Retrait (-)
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <span class="font-bold text-slate-900 block">{{ $tx->notes ?: 'Mouvement de caisse' }}</span>
                                                @if($tx->client)
                                                    <span class="text-[11px] text-indigo-600 font-semibold block">Client: {{ $tx->client->first_name }} {{ $tx->client->last_name }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3.5 font-mono text-[11px] text-slate-500">
                                                {{ $tx->receipt_number ?: $tx->transaction_id }}
                                            </td>
                                            <td class="px-6 py-3.5 text-slate-600">
                                                {{ $tx->user->name ?? 'Agent' }}
                                            </td>
                                            <td class="px-6 py-3.5 font-mono font-black text-sm whitespace-nowrap">
                                                @if($tx->entry_type === 'credit')
                                                    <span class="text-emerald-600">+{{ number_format($tx->amount, 2) }} DH</span>
                                                @else
                                                    <span class="text-rose-600">-{{ number_format($tx->amount, 2) }} DH</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3.5 text-right font-mono font-black text-sm text-slate-900 bg-slate-50/80 whitespace-nowrap border-l border-slate-200">
                                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-900 border border-indigo-200">
                                                    💼 {{ number_format($tx->running_balance, 2) }} DH
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-xs">
                        Aucun mouvement d'espèces enregistré dans le journal de caisse.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- TAB 4: COMPTES BANCAIRES & RIB -->
    @elseif($activeTab === 'banks')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
            <h3 class="font-black text-lg text-slate-900 border-b pb-4">Comptes Bancaires Marocains & Trésorerie</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($bankAccounts as $b)
                    <div class="p-5 border border-slate-200 rounded-2xl bg-slate-50 space-y-3">
                        <span class="font-black text-slate-900 text-sm block">{{ $b->bank_name }}</span>
                        <span class="text-xs text-slate-500 block">{{ $b->agency }}</span>
                        <div class="font-mono text-xs font-bold text-slate-700 bg-white p-2 rounded-lg border border-slate-200 break-all">
                            RIB: {{ $b->rib }}
                        </div>
                        <div class="text-xl font-black font-mono text-blue-600 pt-2 border-t">
                            {{ number_format($b->current_balance, 2) }} DH
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- TAB 5: DOUBLE VALIDATION WORKFLOW -->
    @elseif($activeTab === 'approvals')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
            <h3 class="font-black text-lg text-slate-900 border-b pb-4">File d'Attente de Double Validation (> 5,000 DH)</h3>
            <div class="space-y-4">
                @forelse($approvals as $app)
                    <div class="p-5 border border-slate-200 rounded-2xl bg-amber-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <span class="font-mono font-bold text-indigo-600 text-sm block">Demandé par: {{ $app->requester->name ?? 'Employé' }}</span>
                            <span class="text-xs text-slate-600 block">{{ $app->manager_notes }}</span>
                        </div>
                        <div class="font-mono font-black text-xl text-slate-900">
                            {{ number_format($app->amount, 2) }} DH
                        </div>
                        <button wire:click="approveTransaction({{ $app->id }})" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-md transition">
                            Approuver & Valider ➔
                        </button>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">
                        Aucune transaction en attente de validation.
                    </div>
                @endforelse
            </div>
        </div>

    <!-- TAB 6: LOGS D'AUDIT INALTÉRABLES -->
    @elseif($activeTab === 'audit')
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4">
            <h3 class="font-black text-lg text-slate-900 border-b pb-4">Traçabilité et Registre d'Audit Inaltérable</h3>
            <div class="space-y-3 font-mono text-xs">
                @foreach($auditLogs as $log)
                    <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-indigo-600 block">[{{ $log->created_at->format('d/m/Y H:i:s') }}] {{ $log->action }}</span>
                            <span class="text-slate-500 text-[10px] block">Utilisateur: {{ $log->user->name ?? 'Système' }} • IP: {{ $log->ip_address }}</span>
                        </div>
                        <span class="text-[10px] text-slate-400 max-w-md truncate">{{ $log->reason }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modal Form: New General Ledger Entry -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-slate-900/60 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-black text-slate-900">Nouvelle Opération au Grand Livre</h3>
                    <button wire:click="closeCreateModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="createLedgerEntry" class="space-y-4 text-xs font-medium">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Sens de l'Opération *</label>
                            <select wire:model.live="entry_type" class="w-full border border-slate-300 rounded-xl p-2.5 font-bold">
                                <option value="credit">Crédit (+ Recette Agence)</option>
                                <option value="debit">Débit (- Dépense / Remboursement)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Montant (DH) *</label>
                            <input type="number" step="0.01" wire:model.live="amount" class="w-full border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-sm">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Mode de Paiement *</label>
                            <select wire:model.live="payment_method" class="w-full border border-slate-300 rounded-xl p-2.5 font-bold">
                                <option value="cash">Espèces (Caisse Agence)</option>
                                <option value="cheque">Chèque Marocain</option>
                                <option value="transfer">Virement Bancaire</option>
                                <option value="card">Carte Bancaire / TPE</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Catégorie Comptable *</label>
                            <select wire:model="category" class="w-full border border-slate-300 rounded-xl p-2.5">
                                <option value="encaissement_prime">Encaissement Prime Assurance</option>
                                <option value="reglement_sinistre">Règlement Sinistre Client</option>
                                <option value="commission">Commission Compagnie</option>
                                <option value="charge">Charge & Dépense d'Exploitation</option>
                                <option value="virement">Virement Bancaire Interne</option>
                            </select>
                        </div>

                        @if($payment_method === 'cheque')
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">N° de Chèque *</label>
                                <input type="text" wire:model="cheque_number" placeholder="ex: 8849201" class="w-full border border-slate-300 rounded-xl p-2.5 font-mono">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-700 mb-1">Banque Émettrice *</label>
                                <select wire:model="bank_name" class="w-full border border-slate-300 rounded-xl p-2.5">
                                    <option value="Attijariwafa Bank">Attijariwafa Bank</option>
                                    <option value="Banque Populaire (BCP)">Banque Populaire (BCP)</option>
                                    <option value="BMCE Bank of Africa">BMCE Bank of Africa</option>
                                    <option value="CIH Bank">CIH Bank</option>
                                    <option value="Société Générale (SGMB)">Société Générale (SGMB)</option>
                                    <option value="Crédit du Maroc (CDM)">Crédit du Maroc (CDM)</option>
                                </select>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Rattacher au Client (Optionnel)</label>
                            <select wire:model="client_id" class="w-full border border-slate-300 rounded-xl p-2.5">
                                <option value="">-- Aucun Client (Opération Générale) --</option>
                                @foreach($clients as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->formatted_reference }} - {{ $cl->first_name }} {{ $cl->last_name }} (CIN: {{ $cl->cin ?? '-' }})</option>
                                @endforeach
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
                            <span>💸</span>
                            <span>Enregistrer un Mouvement de Caisse</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Entrée ou retrait direct d'espèces avec mise à jour du solde de caisse.</p>
                    </div>
                    <button wire:click="closeCashMovementModal" class="text-slate-400 hover:text-slate-600 font-bold text-lg">✕</button>
                </div>

                <form wire:submit.prevent="recordCashMovement" class="space-y-4 text-xs font-semibold">
                    <!-- Movement Type Selector -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-2">Type d'Opération de Caisse *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('cash_movement_type', 'debit')" 
                                    class="py-3 px-4 rounded-xl border font-bold flex items-center justify-center gap-2 transition {{ $cash_movement_type === 'debit' ? 'bg-rose-50 border-rose-500 text-rose-700 shadow-xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                <span>🔴</span>
                                <span>Sortie / Retrait (-)</span>
                            </button>
                            <button type="button" wire:click="$set('cash_movement_type', 'credit')" 
                                    class="py-3 px-4 rounded-xl border font-bold flex items-center justify-center gap-2 transition {{ $cash_movement_type === 'credit' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 shadow-xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                                <span>🟢</span>
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
</div>
