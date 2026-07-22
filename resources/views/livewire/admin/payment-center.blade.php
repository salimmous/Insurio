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
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">💵</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Solde Caisses Agence</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($cashBalance, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Caisse Principale Ouverte</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">🏦</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Comptes Bancaires</span>
                <span class="text-2xl font-black text-blue-600">{{ number_format($bankBalance, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">Attijariwafa, BCP, BMCE</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">🏛️</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Chèques en Portefeuille</span>
                <span class="text-2xl font-black text-amber-600">{{ number_format($pendingChequesSum, 2) }} DH</span>
                <span class="text-[10px] text-slate-400 block">{{ $pendingChequesCount }} chèques à déposer</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">📜</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 text-xs font-bold gap-6 overflow-x-auto pb-2">
        <button wire:click="$set('activeTab', 'ledger')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'ledger' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>📖 Grand Livre Comptable</span>
        </button>
        <button wire:click="$set('activeTab', 'cheques')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'cheques' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>📜 Chèques Marocains</span>
            @if($pendingChequesCount > 0)
                <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded-full font-mono">{{ $pendingChequesCount }}</span>
            @endif
        </button>
        <button wire:click="$set('activeTab', 'caisses')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'caisses' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>💰 Caisses & Coffres</span>
        </button>
        <button wire:click="$set('activeTab', 'banks')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'banks' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>🏛️ Comptes Bancaires & RIB</span>
        </button>
        <button wire:click="$set('activeTab', 'approvals')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'approvals' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>🛡️ Double Validation</span>
            @if($pendingApprovalsCount > 0)
                <span class="bg-rose-100 text-rose-800 text-[10px] px-2 py-0.5 rounded-full font-mono animate-pulse">{{ $pendingApprovalsCount }}</span>
            @endif
        </button>
        <button wire:click="$set('activeTab', 'audit')" class="pb-2 transition flex items-center gap-2 {{ $activeTab === 'audit' ? 'border-b-2 border-indigo-600 text-indigo-600 font-extrabold' : 'text-slate-500 hover:text-slate-900' }}">
            <span>🕵️ Logs d'Audit Inaltérables</span>
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
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
            <h3 class="font-black text-lg text-slate-900 border-b pb-4">Caisses d'Agence & Comptage Physique</h3>
            @foreach($cashRegisters as $reg)
                <div class="p-6 border border-slate-200 rounded-2xl bg-slate-50 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                    <div>
                        <span class="font-black text-slate-900 text-base block">{{ $reg->name }}</span>
                        <span class="text-xs text-emerald-600 font-bold">● Caisse Ouverte</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block">Solde Théorique</span>
                        <span class="text-xl font-black font-mono text-slate-900">{{ number_format($reg->current_balance, 2) }} DH</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block">Dernier Comptage Physique</span>
                        <span class="text-xl font-black font-mono text-indigo-600">{{ number_format($reg->physical_balance, 2) }} DH</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 block">Écart de Caisse</span>
                        <span class="text-xl font-black font-mono {{ $reg->variance_amount < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ number_format($reg->variance_amount, 2) }} DH
                        </span>
                    </div>
                </div>
            @endforeach
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
</div>
