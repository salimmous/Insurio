<div class="p-6 space-y-6 font-sans">
    <div>

        <!-- HEADER & AI ASSISTANT -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <span class="text-indigo-600">💸</span> Centre de Règlements & Opérations
                </h1>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mt-1">Tenant Financial Operations Workspace</p>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                <button wire:click="$toggle('showAiAssistant')" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition-all border border-indigo-200/40">
                    ⚡ AI Co-pilot
                </button>
                <button wire:click="openCreatePayment" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm shadow-indigo-900/10">
                    + Enregistrer Règlement
                </button>
            </div>
        </div>

        <!-- AI Assistant Panel -->
        @if($showAiAssistant)
            <div class="bg-indigo-950 text-indigo-100 p-6 rounded-2xl border border-indigo-900 shadow-xl space-y-4 animate-fade-in">
                <div class="flex justify-between items-start border-b border-indigo-900/50 pb-3">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-teal-400 flex items-center gap-2">
                        🧠 Insurio Financial Intelligence
                    </h3>
                    <button wire:click="$set('showAiAssistant', false)" class="text-indigo-400 hover:text-white text-xs">Fermer</button>
                </div>
                <div class="text-xs font-medium leading-relaxed font-mono whitespace-pre-line">
                    {!! $aiSummary !!}
                </div>
            </div>
        @endif

        <!-- TOP METRICS / TELEMETRY (Stripe Dashboard Style) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1: Today's Revenue -->
            <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 h-24 w-24 bg-indigo-650 opacity-10 rounded-full blur-xl"></div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-300">Revenue du Jour</span>
                <div class="text-2xl font-black mt-2">{{ number_format($kpis['today_revenue'], 2) }} DH</div>
                <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-3 font-mono">
                    <span class="bg-slate-800 px-1.5 py-0.5 rounded text-teal-400 font-bold">Espèces: {{ number_format($kpis['cash_today'], 2) }}</span>
                    <span class="bg-slate-800 px-1.5 py-0.5 rounded text-indigo-300 font-bold">Bancaire: {{ number_format($kpis['bank_today'] + $kpis['cheque_today'], 2) }}</span>
                </div>
            </div>

            <!-- Metric 2: Monthly Telemetry -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Ce Mois / Cette Année</span>
                <div class="text-2xl font-black mt-2 text-slate-800">{{ number_format($kpis['monthly_revenue'], 2) }} DH</div>
                <div class="text-[10px] font-bold text-slate-450 mt-3 flex justify-between">
                    <span>Année (YTD):</span>
                    <span class="font-extrabold text-slate-700">{{ number_format($kpis['yearly_revenue'], 2) }} DH</span>
                </div>
            </div>

            <!-- Metric 3: Outstanding / Unpaid Balance -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Encours Total Agence</span>
                <div class="text-2xl font-black mt-2 text-rose-600">{{ number_format($kpis['outstanding_balance'], 2) }} DH</div>
                <div class="text-[10px] font-bold text-slate-450 mt-3 flex justify-between">
                    <span>Règlements en attente:</span>
                    <span class="font-extrabold text-slate-700">{{ $kpis['pending_count'] }} fiches</span>
                </div>
            </div>

            <!-- Metric 4: Cheques Status -->
            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative overflow-hidden">
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400">Incidents de Chèques</span>
                <div class="text-2xl font-black mt-2 text-amber-600">{{ $kpis['returned_count'] }} Chèques</div>
                <div class="text-[10px] font-bold text-slate-450 mt-3 flex justify-between">
                    <span>Statut :</span>
                    <span class="font-extrabold text-amber-700 uppercase">retours impayés</span>
                </div>
            </div>
        </div>

        <!-- MAIN WORKSPACE NAVIGATION TABS -->
        <div class="bg-white p-1 rounded-xl border border-gray-200 shadow-sm flex overflow-x-auto scrollbar-none gap-1">
            <button wire:click="$set('activeTab', 'payments')" class="flex-shrink-0 px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'payments' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                💳 Règlements ({{ $payments->total() }})
            </button>
            <button wire:click="$set('activeTab', 'installments')" class="flex-shrink-0 px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'installments' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                🗓️ Échéanciers Center
            </button>
            <button wire:click="$set('activeTab', 'cheques')" class="flex-shrink-0 px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'cheques' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                🏦 Cheque Center
            </button>
            <button wire:click="$set('activeTab', 'reconciliation')" class="flex-shrink-0 px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'reconciliation' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                🤝 Rapprochements Bancaires
            </button>
            <button wire:click="$set('activeTab', 'accounting')" class="flex-shrink-0 px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'accounting' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                📊 Comptabilité & Performance
            </button>
        </div>

        <!-- TAB CONTENTS -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm min-h-[500px]">

            <!-- TAB 1: PAYMENTS LIST -->
            @if($activeTab === 'payments')
                <div class="space-y-4">
                    <!-- Filters Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                        <div class="lg:col-span-2">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher réglement, client, contrat..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-1.5 text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <select wire:model.live="filterStatus" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-600 focus:outline-none">
                                <option value="">Tous les statuts</option>
                                <option value="draft">Brouillon</option>
                                <option value="pending">En attente</option>
                                <option value="paid">Payé</option>
                                <option value="overdue">En retard</option>
                                <option value="returned">Chèque retourné</option>
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="filterMethod" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-600 focus:outline-none">
                                <option value="">Tous les modes</option>
                                <option value="cash">Espèces</option>
                                <option value="cheque">Chèque</option>
                                <option value="bank_transfer">Virement</option>
                                <option value="credit_card">Carte bancaire</option>
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="filterBranch" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-600 focus:outline-none">
                                <option value="">Toutes les succursales</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select wire:model.live="filterCompany" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-600 focus:outline-none">
                                <option value="">Toutes les compagnies</option>
                                @foreach($companies as $c)
                                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Payments Table -->
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs font-medium text-slate-600">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">Règlement</th>
                                    <th class="px-6 py-4">Client / Contrat</th>
                                    <th class="px-6 py-4">Succursale</th>
                                    <th class="px-6 py-4">Montant</th>
                                    <th class="px-6 py-4">Mode</th>
                                    <th class="px-6 py-4">Statut</th>
                                    <th class="px-6 py-4">Échéance</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($payments as $p)
                                    <tr class="hover:bg-slate-50/50 transition-all">
                                        <td class="px-6 py-4 font-mono font-bold text-slate-800">
                                            <a href="{{ route('admin.payments.workspace', $p->id) }}" class="text-indigo-650 hover:underline">
                                                {{ $p->payment_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="block font-bold text-slate-700">{{ $p->client->nom_complet ?? '-' }}</span>
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $p->contract->contract_number ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $p->branch->nom ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block font-extrabold text-slate-800">{{ number_format($p->amount, 2) }} {{ $p->currency }}</span>
                                            @if($p->paid_amount > 0)
                                                <span class="block text-[10px] text-emerald-600">Payé: {{ number_format($p->paid_amount, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 uppercase font-bold text-slate-500">{{ $p->payment_method }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase 
                                                {{ $p->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                {{ $p->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $p->payment_status === 'overdue' ? 'bg-rose-100 text-rose-800' : '' }}
                                                {{ $p->payment_status === 'returned' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $p->payment_status === 'deposited' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                            ">
                                                {{ $p->payment_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono">{{ $p->due_date ? $p->due_date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.payments.workspace', $p->id) }}" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-[10px] font-extrabold transition-all">Consulter</a>
                                                @if(in_array($p->payment_status, ['pending', 'draft']))
                                                    <button wire:click="openReconcile({{ $p->id }})" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded text-[10px] font-extrabold transition-all">Rapprocher</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-8 text-slate-400">Aucun règlement enregistré.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $payments->links() }}
                </div>
            @endif

            <!-- TAB 2: INSTALLMENTS CENTER -->
            @if($activeTab === 'installments')
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b pb-2 mb-4">Échéanciers & Plans de Financement</h2>
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs font-medium text-slate-600">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">ID Échéance</th>
                                    <th class="px-6 py-4">Client / Contrat</th>
                                    <th class="px-6 py-4">Montant Échéance</th>
                                    <th class="px-6 py-4">Date Limite</th>
                                    <th class="px-6 py-4">Statut</th>
                                    <th class="px-6 py-4">Reçu Paiement</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($installments as $inst)
                                    <tr class="hover:bg-slate-50/50 transition-all">
                                        <td class="px-6 py-4 font-mono font-bold text-slate-700">#{{ $inst->id }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block font-bold text-slate-700">{{ $inst->payment->client->nom_complet ?? '-' }}</span>
                                            <span class="block text-[10px] text-slate-400 font-mono">{{ $inst->payment->contract->contract_number ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-extrabold text-slate-800">{{ number_format($inst->amount, 2) }} DH</td>
                                        <td class="px-6 py-4 font-mono">{{ $inst->due_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase
                                                {{ $inst->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}
                                            ">
                                                {{ $inst->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-slate-450">{{ $inst->receipt_number ?: '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @if($inst->status !== 'paid')
                                                <button wire:click="recordInstallmentPayment({{ $inst->id }})" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[10px] font-extrabold transition-all">Acquitter</button>
                                            @else
                                                <span class="text-slate-400 font-bold text-[10px] uppercase">Régularisé</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-slate-400">Aucun échéancier trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $installments->links() }}
                </div>
            @endif

            <!-- TAB 3: CHEQUE CENTER -->
            @if($activeTab === 'cheques')
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b pb-4">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Cheque Management Desk</h2>
                        <div class="flex gap-2">
                            @foreach(['waiting' => 'En Attente', 'deposited' => 'Déposés', 'paid' => 'Encaissés', 'returned' => 'Rejetés'] as $k => $lbl)
                                <button wire:click="$set('chequeStatusFilter', '{{ $k }}')" class="px-3 py-1.5 text-xs font-extrabold rounded-lg transition-all {{ $chequeStatusFilter === $k ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $lbl }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cheques Table -->
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs font-medium text-slate-600">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">N° Chèque</th>
                                    <th class="px-6 py-4">Banque / Émetteur</th>
                                    <th class="px-6 py-4">Montant</th>
                                    <th class="px-6 py-4">Échéance Chèque</th>
                                    <th class="px-6 py-4">Date Dépôt</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($cheques as $c)
                                    <tr class="hover:bg-slate-50/50 transition-all">
                                        <td class="px-6 py-4 font-mono font-bold text-slate-800">{{ $c->cheque_number }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block font-bold text-slate-700">{{ $c->bank_name }}</span>
                                            <span class="block text-[10px] text-slate-400">{{ $c->client->nom_complet }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-extrabold text-slate-800">{{ number_format($c->amount, 2) }} DH</td>
                                        <td class="px-6 py-4 font-mono">{{ $c->cheque_issue_date ? $c->cheque_issue_date->format('d/m/Y') : '-' }}</td>
                                        <td class="px-6 py-4 font-mono text-slate-500">{{ $c->cheque_deposit_date ? $c->cheque_deposit_date->format('d/m/Y') : 'Non déposé' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-1.5">
                                                @if($c->payment_status === 'pending' || $c->payment_status === 'waiting')
                                                    <button wire:click="updateChequeStatus({{ $c->id }}, 'deposited')" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded text-[10px] font-extrabold transition-all">Déposer</button>
                                                @endif
                                                @if($c->payment_status === 'deposited')
                                                    <button wire:click="updateChequeStatus({{ $c->id }}, 'paid')" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded text-[10px] font-extrabold transition-all">Encaisser</button>
                                                    <button wire:click="updateChequeStatus({{ $c->id }}, 'returned')" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded text-[10px] font-extrabold transition-all">Rejeter / Impayé</button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-slate-400">Aucun chèque dans cette catégorie.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $cheques->links() }}
                </div>
            @endif

            <!-- TAB 4: BANK RECONCILIATION -->
            @if($activeTab === 'reconciliation')
                <div class="space-y-6">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b pb-2 mb-4">Rapprochement Bancaire (Reconciliation Desk)</h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: List of payments to match -->
                        <div class="lg:col-span-2 space-y-4">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Transactions non rapprochées</h3>
                            <div class="overflow-x-auto border border-gray-200 rounded-xl bg-slate-50/50 p-2">
                                <table class="min-w-full divide-y divide-gray-200 text-left text-xs text-slate-600">
                                    <thead class="text-slate-500 font-extrabold text-[9px] uppercase">
                                        <tr>
                                            <th class="px-4 py-3">Règlement</th>
                                            <th class="px-4 py-3">Client</th>
                                            <th class="px-4 py-3">Montant</th>
                                            <th class="px-4 py-3">Mode</th>
                                            <th class="px-4 py-3 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse($unreconciled as $up)
                                            <tr>
                                                <td class="px-4 py-3 font-mono font-bold text-indigo-700">{{ $up->payment_number }}</td>
                                                <td class="px-4 py-3">{{ $up->client->nom_complet }}</td>
                                                <td class="px-4 py-3 font-extrabold text-slate-800">{{ number_format($up->amount, 2) }} DH</td>
                                                <td class="px-4 py-3 uppercase font-bold text-[10px] text-slate-500">{{ $up->payment_method }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <button wire:click="openReconcile({{ $up->id }})" class="px-2 py-1 bg-slate-900 text-white rounded text-[10px] font-bold hover:bg-slate-800 transition-all">Match</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-slate-400">Toutes les transactions ont été rapprochées !</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Right: Reconciled History -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Derniers Rapprochements</h3>
                            <div class="space-y-2 max-h-[400px] overflow-y-auto pr-1">
                                @forelse($reconciled as $rec)
                                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm text-xs space-y-2">
                                        <div class="flex justify-between font-bold">
                                            <span class="text-slate-800 font-mono">{{ $rec->payment->payment_number }}</span>
                                            <span class="text-emerald-600">Matched</span>
                                        </div>
                                        <p class="text-[10px] text-slate-450 font-semibold uppercase">Réf Banque: {{ $rec->reference }}</p>
                                        <div class="text-[10px] text-slate-500 flex justify-between">
                                            <span>Date: {{ $rec->deposit_date->format('d/m/Y') }}</span>
                                            @if($rec->difference > 0)
                                                <span class="text-rose-600 font-bold">Écart: {{ number_format($rec->difference, 2) }} DH</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-slate-450 italic py-6">Aucun rapprochement enregistré.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 5: ACCOUNTING AND TELEMETRY -->
            @if($activeTab === 'accounting')
                <div class="space-y-6">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b pb-2 mb-4">Comptabilité & Ventilation des Revenus</h2>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Revenue by Payment Method -->
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Mode de Règlement (Répartition)</h3>
                            <div class="space-y-3">
                                @foreach($methodChart as $mc)
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs font-bold text-slate-700">
                                            <span class="uppercase">{{ $mc->payment_method }}</span>
                                            <span>{{ number_format($mc->total, 2) }} DH</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 60%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Revenue by Insurer -->
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Compagnies d'Assurances (Vol. Affaires)</h3>
                            <div class="space-y-3">
                                @foreach($companyChart as $cc)
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs font-bold text-slate-700">
                                            <span>{{ $cc->company->nom ?? 'Compagnie Inconnue' }}</span>
                                            <span>{{ number_format($cc->total, 2) }} DH</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-teal-500 h-1.5 rounded-full" style="width: 45%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

    <!-- CREATE PAYMENT MODAL -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xl w-full max-w-lg p-6 space-y-4 animate-scale-up">
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-base font-extrabold text-slate-800">Enregistrer un Règlement</h2>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-700 text-sm">✕</button>
                </div>
                
                <form wire:submit.prevent="createPayment" class="space-y-4 text-xs font-semibold text-slate-700">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-500 mb-1">Client</label>
                            <select wire:model.live="client_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                                <option value="">Séléctionner le client</option>
                                @foreach($clients as $cl)
                                    <option value="{{ $cl->id }}">{{ $cl->nom_complet }}</option>
                                @endforeach
                            </select>
                            @error('client_id') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-slate-500 mb-1">Contrat</label>
                            <select wire:model.live="contract_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                                <option value="">Séléctionner le contrat</option>
                                @foreach($contracts as $co)
                                    @if(empty($client_id) || $co->client_id == $client_id)
                                        <option value="{{ $co->id }}">{{ $co->contract_number }} ({{ $co->compagnie->nom ?? '' }})</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('contract_id') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-slate-500 mb-1">Montant Prime (DH)</label>
                            <input type="number" step="0.01" wire:model.live="amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                            @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-slate-500 mb-1">Acompte Payé (DH)</label>
                            <input type="number" step="0.01" wire:model.live="paid_amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                        </div>
                        <div>
                            <label class="block text-slate-500 mb-1">Remise (DH)</label>
                            <input type="number" step="0.01" wire:model.live="discount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-500 mb-1">Mode de Règlement</label>
                            <select wire:model.live="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                                <option value="cash">Espèces</option>
                                <option value="cheque">Chèque</option>
                                <option value="bank_transfer">Virement bancaire</option>
                                <option value="credit_card">Carte Bancaire</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-slate-500 mb-1">Date d'Opération</label>
                            <input type="date" wire:model="payment_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                        </div>
                    </div>

                    <!-- CONDITIONAL CHEQUE DETAILS -->
                    @if($payment_method === 'cheque')
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <h3 class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Détails du Chèque</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-slate-500 mb-0.5">N° Chèque</label>
                                    <input type="text" wire:model="cheque_number" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 font-mono">
                                </div>
                                <div>
                                    <label class="block text-slate-500 mb-0.5">Banque Émettrice</label>
                                    <input type="text" wire:model="bank_name" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white rounded-xl">Confirmer & Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- RECONCILE MODAL -->
    @if($showReconcileModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xl w-full max-w-sm p-6 space-y-4 animate-scale-up">
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-base font-extrabold text-slate-800">Rapprochement Bancaire</h2>
                    <button wire:click="$set('showReconcileModal', false)" class="text-slate-400 hover:text-slate-700 text-sm">✕</button>
                </div>
                
                <form wire:submit.prevent="createReconciliation" class="space-y-4 text-xs font-semibold text-slate-700">
                    <div>
                        <label class="block text-slate-500 mb-1">Référence du Dépôt / Transfert</label>
                        <input type="text" wire:model="reconcile_ref" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                        @error('reconcile_ref') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Montant Reçu par la Banque (DH)</label>
                        <input type="number" step="0.01" wire:model="reconcile_amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                        @error('reconcile_amount') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-slate-500 mb-1">Date de Constat de Dépôt</label>
                        <input type="date" wire:model="reconcile_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" wire:click="$set('showReconcileModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl">Confirmer & Matcher</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
