<div class="p-6 space-y-6 font-sans">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Clôture de Caisse Journalière</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    DAILY CASH CLOSING ENGINE
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Calcul automatique des flux financiers du jour, rapprochement physique et rapport d'arrêté de caisse.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="openClosingModal" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-black text-xs shadow-md transition">
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Clôturer la Caisse Journalière</span>
            </button>
        </div>
    </div>

    <!-- Period Filters & Fast Selector -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2 overflow-x-auto">
            <button wire:click="setPeriod('today')" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $filterPeriod === 'today' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Aujourd'hui</button>
            <button wire:click="setPeriod('yesterday')" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $filterPeriod === 'yesterday' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Hier</button>
            <button wire:click="setPeriod('week')" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $filterPeriod === 'week' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Cette Semaine</button>
            <button wire:click="setPeriod('month')" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $filterPeriod === 'month' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Ce Mois</button>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <input type="date" wire:model.live="filterDateStart" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs font-semibold">
            <span class="text-xs text-slate-400">à</span>
            <input type="date" wire:model.live="filterDateEnd" class="border border-slate-300 rounded-xl px-3 py-1.5 text-xs font-semibold">
        </div>
    </div>

    <!-- 13 Mandatory KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Solde Ouverture Caisse</span>
            <span class="text-xl font-black text-slate-900">{{ number_format($openingBalance, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Encaissement Espèces</span>
            <span class="text-xl font-black text-emerald-600">+{{ number_format($cashInToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Décaissement Espèces</span>
            <span class="text-xl font-black text-rose-600">-{{ number_format($cashOutToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Chèques Reçus</span>
            <span class="text-xl font-black text-amber-600">{{ number_format($chequesReceivedToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Chèques Déposés</span>
            <span class="text-xl font-black text-blue-600">{{ number_format($chequesDepositedToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Chèques en Portefeuille</span>
            <span class="text-xl font-black text-indigo-600">{{ number_format($pendingChequesSum, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Chèques Impayés</span>
            <span class="text-xl font-black text-rose-600">{{ number_format($returnedChequesSum, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Dépenses Exploitation</span>
            <span class="text-xl font-black text-rose-600">{{ number_format($expensesToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Commissions Payées</span>
            <span class="text-xl font-black text-slate-700">{{ number_format($commissionsToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Remboursements Sinistres</span>
            <span class="text-xl font-black text-slate-700">{{ number_format($refundsToday, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Solde Caisse Attendu</span>
            <span class="text-xl font-black text-indigo-600">{{ number_format($closingBalance, 2) }} DH</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Écart de Caisse</span>
            <span class="text-xl font-black {{ $cashDifference < 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ number_format($cashDifference, 2) }} DH</span>
        </div>
    </div>

    <!-- Daily Financial Summary Ledger Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Income Summary -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="font-black text-base text-slate-900 border-b pb-3 flex justify-between items-center">
                <span class="inline-flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Recettes totales du Jour</span>
                </span>
                <span class="text-emerald-600 font-mono">+{{ number_format($totalIncome, 2) }} DH</span>
            </h3>
            <div class="space-y-2 text-xs text-slate-700">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>Solde Ouverture Caisse</span>
                    <span class="font-mono font-bold">{{ number_format($openingBalance, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>+ Encaissements Espèces</span>
                    <span class="font-mono font-bold text-emerald-600">+{{ number_format($cashInToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>+ Virements Bancaires Reçus</span>
                    <span class="font-mono font-bold text-emerald-600">+{{ number_format($transfersInToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>+ Règlements Carte / TPE</span>
                    <span class="font-mono font-bold text-emerald-600">+{{ number_format($cardInToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>+ Chèques Encaissés</span>
                    <span class="font-mono font-bold text-emerald-600">+{{ number_format($chequesReceivedToday, 2) }} DH</span>
                </div>
            </div>
        </div>

        <!-- Expenses Summary -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="font-black text-base text-slate-900 border-b pb-3 flex justify-between items-center">
                <span class="inline-flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span>Dépenses totales du Jour</span>
                </span>
                <span class="text-rose-600 font-mono">-{{ number_format($totalExpenses, 2) }} DH</span>
            </h3>
            <div class="space-y-2 text-xs text-slate-700">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>- Dépenses d'Exploitation</span>
                    <span class="font-mono font-bold text-rose-600">-{{ number_format($expensesToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>- Commissions Payées</span>
                    <span class="font-mono font-bold text-rose-600">-{{ number_format($commissionsToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>- Remboursements Clients</span>
                    <span class="font-mono font-bold text-rose-600">-{{ number_format($refundsToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span>- Retraits de Caisse Espèces</span>
                    <span class="font-mono font-bold text-rose-600">-{{ number_format($cashOutToday, 2) }} DH</span>
                </div>
                <div class="flex justify-between py-1 pt-2 font-black text-sm text-slate-900 border-t">
                    <span>Bénéfice Net du Jour (Net Profit)</span>
                    <span class="font-mono text-indigo-600">{{ number_format($netProfit, 2) }} DH</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Timeline Stream -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs">
        <h3 class="font-black text-base text-slate-900 border-b pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 stroke-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Timeline des Opérations Financières du Jour</span>
        </h3>
        <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
            @forelse($timeline as $trx)
                <div class="ml-6 relative">
                    <div class="absolute -left-[31px] top-0 w-4 h-4 rounded-full border-2 border-white {{ $trx->entry_type === 'credit' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                    <div class="flex justify-between items-start bg-slate-50 p-3.5 rounded-xl border border-slate-200">
                        <div>
                            <span class="font-mono text-[10px] font-bold text-slate-400 block">{{ $trx->entry_date->format('H:i') }}</span>
                            <span class="font-bold text-slate-900 text-xs block">{{ str_replace('_', ' ', strtoupper($trx->category)) }}</span>
                            <span class="text-xs text-slate-500 block">
                                {{ $trx->client ? $trx->client->first_name . ' ' . $trx->client->last_name : 'Opération Agence' }} • Mode: {{ strtoupper($trx->payment_method) }}
                            </span>
                        </div>
                        <span class="font-mono font-black text-sm {{ $trx->entry_type === 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $trx->entry_type === 'credit' ? '+' : '-' }}{{ number_format($trx->amount, 2) }} DH
                        </span>
                    </div>
                </div>
            @empty
                <div class="ml-6 text-xs text-slate-400 py-4">
                    Aucune transaction effectuée sur la période sélectionnée.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal: Close Cash Register -->
    @if($showClosingModal)
        <div class="fixed inset-0 bg-slate-900/60 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-black text-slate-900">Procès-Verbal d'Arrêté & Clôture de Caisse</h3>
                    <button wire:click="$set('showClosingModal', false)" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>La clôture est définitive et fermera la caisse physique de la journée.</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Montant Physique Compté en Caisse (DH) *</label>
                        <input type="number" step="0.01" wire:model="physical_count" class="w-full border border-slate-300 rounded-xl p-2.5 font-mono font-bold text-base">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nom du Responsable / Manager *</label>
                        <input type="text" wire:model="manager_name" class="w-full border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nom du Caissier / Employé *</label>
                        <input type="text" wire:model="employee_name" class="w-full border border-slate-300 rounded-xl p-2.5">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Notes et Commentaires d'Arrêté</label>
                        <textarea wire:model="closing_notes" rows="2" class="w-full border border-slate-300 rounded-xl p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="$set('showClosingModal', false)" class="px-4 py-2 border border-slate-300 rounded-xl text-slate-700 font-bold">Annuler</button>
                        <button type="button" wire:click="executeCashClosing" class="inline-flex items-center gap-2 px-6 py-2 bg-rose-600 text-white rounded-xl font-black shadow-md hover:bg-rose-700">
                            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Valider & Signer la Clôture</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
