<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">Suivi des Règlements</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Suivez les primes encaissées et gérez les reçus de paiement.</p>
        </div>
        <button wire:click="create" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Enregistrer un Règlement
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
        <input type="text" wire:model.live="search" placeholder="Rechercher par client ou référence..." 
               class="w-full max-w-md px-4 py-2.5 border border-slate-200 dark:border-slate-650 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-550 dark:text-slate-350 border-b border-slate-100 dark:border-slate-700">
                    <th class="px-6 py-4 font-semibold">Client</th>
                    <th class="px-6 py-4 font-semibold">N° Contrat</th>
                    <th class="px-6 py-4 font-semibold">Montant</th>
                    <th class="px-6 py-4 font-semibold">Méthode</th>
                    <th class="px-6 py-4 font-semibold">Statut</th>
                    <th class="px-6 py-4 font-semibold">Référence</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $item)
                    <tr class="border-b border-slate-100 dark:border-slate-750 text-slate-650 dark:text-slate-300 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">
                            {{ $item->client->first_name ?? '' }} {{ $item->client->last_name ?? '' }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                            #{{ $item->contract->contract_number ?? $item->contract_id }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-emerald-650">
                            {{ number_format($item->amount, 2) }} DH
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold capitalize">
                            {{ $item->payment_method === 'bank_transfer' ? 'Virement' : ($item->payment_method === 'card' ? 'Carte' : 'Espèces') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                {{ $item->status === 'paid' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30' : ($item->status === 'partial' ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/30' : 'bg-rose-50 text-rose-700 dark:bg-rose-900/30') }}">
                                {{ $item->status === 'paid' ? 'Payé' : ($item->status === 'partial' ? 'Partiel' : 'En Attente') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            {{ $item->reference ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400">
                            {{ $item->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">Aucun règlement enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-6">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Modal Dialog -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 w-full max-w-lg overflow-hidden transition-all transform scale-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Enregistrer un Règlement</h3>
                    <button wire:click="$set('isOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Client</label>
                        <select wire:model.live="client_id" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="">Sélectionner un Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }} ({{ $client->company_name ?? 'Particulier' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Contrat</label>
                        <select wire:model="contract_id" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="">Sélectionner un Contrat</option>
                            @foreach($contracts as $c)
                                <option value="{{ $c->id }}">N° {{ $c->contract_number }} (Prime: {{ $c->premium_amount }} DH)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Montant (DH)</label>
                            <input type="number" step="0.01" wire:model="amount" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Méthode de paiement</label>
                            <select wire:model="payment_method" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="cash">Espèces</option>
                                <option value="bank_transfer">Virement Bancaire</option>
                                <option value="card">Carte Bancaire</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Statut</label>
                            <select wire:model="status" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                                <option value="paid">Payé</option>
                                <option value="partial">Partiel</option>
                                <option value="pending">En Attente</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Référence / Numéro chèque</label>
                            <input type="text" wire:model="reference" placeholder="ex: CHQ-879812" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('isOpen', false)" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-700">
                        Annuler
                    </button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
