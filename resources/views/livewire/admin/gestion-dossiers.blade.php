<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Centre de Gestion des Dossiers</h1>
                <p class="text-sm text-gray-500">Workspace centralisé pour tous les sinistres, réclamations clients, impayés, modifications de contrat et suivis opérationnels.</p>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Créer un Dossier / Incident
            </button>
        </div>

        <!-- Session Message -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
                <span>🎉</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Case Dashboard Widgets -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Dossiers Ouverts</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-slate-800">{{ $stats['open'] }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 rounded">Actifs</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Cas Urgents</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-rose-600">{{ $stats['urgent'] }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-50 text-rose-700 rounded animate-pulse">Critique</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Attente Client</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-amber-600">{{ $stats['waiting_client'] }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded">Pause</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Attente Compagnie</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-blue-600">{{ $stats['waiting_company'] }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-blue-50 text-blue-700 rounded">Validation</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Attente Expert</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-purple-600">{{ $stats['waiting_expert'] }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 rounded">Rapport</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Résolution Moyenne</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-extrabold text-emerald-600">{{ $stats['avg_resolution'] }}j</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded">SLA</span>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="relative w-full lg:max-w-md">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher par client, dossier DS-..., police..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <div class="w-full flex flex-wrap gap-2 justify-end">
                <select wire:model.live="filterType" class="bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none">
                    <option value="">Tous les Types</option>
                    <option value="claim">Claims / Sinistres</option>
                    <option value="complaint">Réclamation Client</option>
                    <option value="payment_issue">Problème de Paiement</option>
                    <option value="returned_cheque">Chèque Impayé</option>
                    <option value="missing_docs">Documents Manquants</option>
                    <option value="renewal">Renouvellement</option>
                    <option value="modification">Modification de Contrat</option>
                    <option value="cancellation">Résiliation</option>
                </select>

                <select wire:model.live="filterStatus" class="bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none">
                    <option value="">Tous les Statuts</option>
                    <option value="open">Ouvert</option>
                    <option value="assigned">Assigné</option>
                    <option value="waiting_client">Attente Client</option>
                    <option value="waiting_company">Attente Compagnie</option>
                    <option value="waiting_expert">Attente Expert</option>
                    <option value="waiting_garage">Attente Garage</option>
                    <option value="in_progress">En Cours</option>
                    <option value="resolved">Résolu</option>
                    <option value="closed">Fermé</option>
                </select>

                <select wire:model.live="filterPriority" class="bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none">
                    <option value="">Toutes les Priorités</option>
                    <option value="low">Faible</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                    <option value="critical">Critique</option>
                </select>

                <select wire:model.live="filterSuccursale" class="bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none">
                    <option value="">Toutes les Branches</option>
                    @foreach($succursalesList as $s)
                        <option value="{{ $s->id }}">{{ $s->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Dossiers Table Workspace -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Numéro & Type</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contrat / Compagnie</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut & Priorité</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigné à</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date & Progression</th>
                            <th class="px-6 py-3.5 class text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-150">
                        @forelse($dossiers as $d)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-extrabold text-indigo-650 hover:underline">
                                        <a href="{{ route('admin.dossiers.workspace', $d->id) }}">{{ $d->dossier_number }}</a>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded mt-1 inline-block uppercase
                                        @if($d->type === 'claim') bg-red-50 text-red-700
                                        @elseif($d->type === 'payment_issue' || $d->type === 'returned_cheque') bg-amber-50 text-amber-700
                                        @elseif($d->type === 'renewal') bg-emerald-50 text-emerald-700
                                        @else bg-blue-50 text-blue-700 @endif">
                                        @if($d->type === 'claim') Sinistre (Auto)
                                        @elseif($d->type === 'payment_issue') Impayé
                                        @elseif($d->type === 'returned_cheque') Chèque Impayé
                                        @elseif($d->type === 'complaint') Réclamation
                                        @elseif($d->type === 'renewal') Renouvellement
                                        @else {{ $d->type }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $d->client->nom_complet }}</div>
                                    <div class="text-xs text-gray-400">{{ $d->client->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($d->contract)
                                        <div class="text-sm text-gray-900 font-semibold">{{ $d->contract->policy_number }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">Aucun contrat lié</div>
                                    @endif
                                    <div class="text-xs text-gray-500 font-medium">{{ $d->compagnie ? $d->compagnie->nom : 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                                        @if($d->status === 'resolved' || $d->status === 'closed') bg-emerald-100 text-emerald-800
                                        @elseif($d->status === 'open' || $d->status === 'assigned') bg-blue-100 text-blue-800
                                        @elseif(str_starts_with($d->status, 'waiting_')) bg-amber-100 text-amber-800
                                        @else bg-slate-100 text-slate-800 @endif">
                                        {{ $d->status }}
                                    </span>
                                    <div class="mt-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider
                                            @if($d->priority === 'critical' || $d->priority === 'high') text-rose-600
                                            @elseif($d->priority === 'medium') text-slate-500
                                            @else text-slate-400 @endif">
                                            Priorité: {{ $d->priority }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($d->assignedEmployee)
                                        <div class="text-sm text-gray-800 font-semibold">{{ $d->assignedEmployee->nom_complet }}</div>
                                        <div class="text-xs text-gray-400">{{ $d->succursale->nom }}</div>
                                    @else
                                        <span class="text-xs italic text-gray-400">Non assigné</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-600">Créé le {{ $d->creation_date->format('d/m/Y') }}</div>
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5 mt-2 overflow-hidden">
                                        <div class="bg-indigo-650 h-1.5 rounded-full" style="width: {{ $d->progress }}%"></div>
                                    </div>
                                    <div class="text-[9px] text-gray-450 font-bold mt-0.5">{{ $d->progress }}% Complété</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                    <a href="{{ route('admin.dossiers.workspace', $d->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-all">Consulter</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-400 text-3xl">📂</div>
                                    <div class="mt-2 text-sm text-gray-500 font-semibold">Aucun dossier trouvé</div>
                                    <p class="text-xs text-gray-400 mt-1">Essayez d'ajuster vos critères de recherche ou de créer un nouveau dossier.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($dossiers->hasPages())
                <div class="p-4 border-t border-gray-150 bg-slate-50">
                    {{ $dossiers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Dossier Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-slate-900">Créer un Nouveau Dossier / Incident</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createDossier" class="p-6 space-y-4">
                    <!-- Type of Dossier -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Type de Dossier</label>
                        <select wire:model.live="type" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                            <option value="claim" class="text-slate-900 bg-white font-semibold py-1">Claim / Sinistre (Automobile)</option>
                            <option value="complaint" class="text-slate-900 bg-white font-semibold py-1">Complaint / Réclamation Client</option>
                            <option value="payment_issue" class="text-slate-900 bg-white font-semibold py-1">Payment Issue / Impayé</option>
                            <option value="returned_cheque" class="text-slate-900 bg-white font-semibold py-1">Returned Cheque / Chèque Impayé</option>
                            <option value="missing_docs" class="text-slate-900 bg-white font-semibold py-1">Missing Documents / Pièces Manquantes</option>
                            <option value="renewal" class="text-slate-900 bg-white font-semibold py-1">Renewal / Renouvellement</option>
                            <option value="modification" class="text-slate-900 bg-white font-semibold py-1">Modification de Contrat</option>
                            <option value="cancellation" class="text-slate-900 bg-white font-semibold py-1">Cancellation / Résiliation</option>
                        </select>
                    </div>

                    <!-- Client Select -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client</label>
                        <select wire:model.live="client_id" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                            <option value="" class="text-slate-900 bg-white font-semibold py-1">Sélectionner un Client</option>
                            @foreach($clientsList as $c)
                                <option value="{{ $c->id }}" class="text-slate-900 bg-white font-semibold py-1">{{ $c->nom_complet }} ({{ $c->national_id ?? $c->cin }})</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-xs text-rose-600 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract Select (Linked to Client) -->
                    @if($client_id)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contrat d'assurance (Optionnel)</label>
                            <select wire:model.live="contract_id" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="" class="text-slate-900 bg-white font-semibold py-1">Sélectionner un Contrat</option>
                                @foreach($contractsList as $co)
                                    <option value="{{ $co->id }}" class="text-slate-900 bg-white font-semibold py-1">{{ $co->policy_number }} ({{ $co->start_date?->format('d/m/Y') }} - {{ $co->end_date?->format('d/m/Y') }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Compagnie -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Compagnie d'Assurance</label>
                            <select wire:model.live="compagnie_id" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="" class="text-slate-900 bg-white font-semibold py-1">Sélectionner la Compagnie</option>
                                @foreach($compagniesList as $comp)
                                    <option value="{{ $comp->id }}" class="text-slate-900 bg-white font-semibold py-1">{{ $comp->nom }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Succursale -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Succursale / Agence</label>
                            <select wire:model.live="succursale_id" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="" class="text-slate-900 bg-white font-semibold py-1">Sélectionner la Succursale</option>
                                @foreach($succursalesList as $s)
                                    <option value="{{ $s->id }}" class="text-slate-900 bg-white font-semibold py-1">{{ $s->nom }}</option>
                                @endforeach
                            </select>
                            @error('succursale_id') <span class="text-xs text-rose-600 mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Priority -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Priorité</label>
                            <select wire:model.live="priority" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="low" class="text-slate-900 bg-white font-semibold py-1">Low / Basse</option>
                                <option value="medium" class="text-slate-900 bg-white font-semibold py-1">Medium / Moyenne</option>
                                <option value="high" class="text-slate-900 bg-white font-semibold py-1">High / Haute</option>
                                <option value="critical" class="text-slate-900 bg-white font-semibold py-1">Critical / Critique</option>
                            </select>
                        </div>

                        <!-- Urgency -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urgence</label>
                            <select wire:model.live="urgency" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="low" class="text-slate-900 bg-white font-semibold py-1">Low / Basse</option>
                                <option value="medium" class="text-slate-900 bg-white font-semibold py-1">Medium / Moyenne</option>
                                <option value="high" class="text-slate-900 bg-white font-semibold py-1">High / Haute</option>
                                <option value="critical" class="text-slate-900 bg-white font-semibold py-1">Critical / Critique</option>
                            </select>
                        </div>
                    </div>

                    <!-- Assignee -->
                    @if($succursale_id)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Assigner à (Conseiller)</label>
                            <select wire:model.live="assigned_employee_id" class="w-full bg-white border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 shadow-xs cursor-pointer">
                                <option value="" class="text-slate-900 bg-white font-semibold py-1">Auto-assignation / Non assigné</option>
                                @foreach($employeesList as $emp)
                                    <option value="{{ $emp->id }}" class="text-slate-900 bg-white font-semibold py-1">{{ $emp->nom_complet }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-100 font-bold text-sm transition-all shadow-xs">Annuler</button>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-indigo-600/20">Créer le Dossier</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
