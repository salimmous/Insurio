<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Entreprises</h1>
                <p class="text-sm text-gray-500">Gérez les comptes clients entreprises et professionnels de votre portefeuille.</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle Entreprise
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">{{ $entreprises->total() }}</span>
                    <span class="text-sm text-gray-500">Entreprises clientes</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ \App\Models\Client::where('type', 'entreprise')->where('solvabilite', 'solvable')->count() }}
                    </span>
                    <span class="text-sm text-gray-500">Entreprises solvables</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ \App\Models\Client::where('type', 'entreprise')->where('incident', true)->count() }}
                    </span>
                    <span class="text-sm text-gray-500">Entreprises avec incidents</span>
                </div>
            </div>
        </div>

        <!-- Filter / Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Rechercher par Raison Sociale, ICE, RC, Téléphone..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <!-- Entreprises List -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-55 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Raison Sociale</th>
                            <th class="px-6 py-3">ICE / RC</th>
                            <th class="px-6 py-3">Téléphone</th>
                            <th class="px-6 py-3">E-mail</th>
                            <th class="px-6 py-3">Solvabilité</th>
                            <th class="px-6 py-3">Incidents</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-900">
                        @forelse($entreprises as $ent)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $ent->nom }}</div>
                                    @if($ent->employes->isNotEmpty())
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @foreach($ent->employes as $emp)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-50 text-slate-600 border border-slate-200">
                                                    {{ $emp->nom }} {{ $emp->prenom }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">{{ $ent->adresse ?? 'Pas d\'adresse renseignée' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-gray-700 text-xs">
                                    {{ $ent->cin ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-600 text-xs">
                                    {{ $ent->telephone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $ent->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($ent->solvabilite === 'solvable')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Solvable</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">Insolvable</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($ent->incident)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Oui (Impayé/Sinistre)</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">Aucun</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <button wire:click="openModal({{ $ent->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Modifier</button>
                                    <button onclick="confirm('Supprimer cette entreprise ?') || event.stopImmediatePropagation()" wire:click="delete({{ $ent->id }})" class="text-rose-600 hover:text-rose-900 font-medium">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    Aucune entreprise cliente enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($entreprises as $ent)
                    <div class="p-4 flex flex-col gap-2 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-bold text-gray-800">{{ $ent->nom }}</span>
                                <span class="text-xs text-gray-400 font-mono block">ICE/RC: {{ $ent->cin ?? '-' }}</span>
                            </div>
                            <div class="flex gap-1">
                                @if($ent->solvabilite === 'solvable')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Solvable</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800">Insolvable</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-gray-600">
                            <div><strong>Téléphone:</strong> {{ $ent->telephone ?? '-' }}</div>
                            <div><strong>E-mail:</strong> {{ $ent->email ?? '-' }}</div>
                            <div><strong>Incidents:</strong> {{ $ent->incident ? 'Oui' : 'Aucun' }}</div>
                            @if($ent->employes->isNotEmpty())
                                <div class="mt-1.5 flex flex-wrap gap-1">
                                    @foreach($ent->employes as $emp)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                            {{ $emp->nom }} {{ $emp->prenom }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex justify-end gap-3 text-xs mt-2 border-t pt-2 border-gray-100">
                            <button wire:click="openModal({{ $ent->id }})" class="text-indigo-600 font-semibold">Modifier</button>
                            <button onclick="confirm('Supprimer cette entreprise ?') || event.stopImmediatePropagation()" wire:click="delete({{ $ent->id }})" class="text-rose-600 font-semibold">Supprimer</button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">
                        Aucune entreprise cliente enregistrée.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $entreprises->links() }}
            </div>
        </div>

        <!-- Form Modal -->
        @if($isModalOpen)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-6 py-6 border-b border-gray-150">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                {{ $clientId ? 'Modifier l\'entreprise' : 'Ajouter une entreprise' }}
                            </h3>
                        </div>
                        <form wire:submit.prevent="save" class="p-6 flex flex-col gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Raison Sociale / Nom</label>
                                <input type="text" wire:model="nom" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('nom') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">ICE / RC</label>
                                    <input type="text" wire:model="cin" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                                    @error('cin') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Téléphone</label>
                                    <input type="text" wire:model="telephone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('telephone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">E-mail</label>
                                <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Adresse complète</label>
                                <textarea wire:model="adresse" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                @error('adresse') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Solvabilité</label>
                                    <select wire:model="solvabilite" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="solvable">Solvable</option>
                                        <option value="non-solvable">Non solvable / Litige</option>
                                    </select>
                                    @error('solvabilite') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1 flex items-center pl-4 mt-6">
                                    <input type="checkbox" id="incident" wire:model="incident" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="incident" class="ml-2 block text-sm text-gray-700 font-semibold text-rose-600">Incident signalé</label>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 -mx-6 -mb-6 border-t border-gray-150">
                                <button type="button" wire:click="closeModal" class="inline-flex justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Annuler
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
