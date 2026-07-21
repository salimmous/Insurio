<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Succursales</h1>
                <p class="text-sm text-gray-500">Gérez les succursales de votre cabinet d'assurance, suivez leur production et affectez les responsables.</p>
            </div>
            <button wire:click="openCreateModal" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle Succursale
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
                    <span class="block text-2xl font-bold text-gray-900">{{ count($succursales) }}</span>
                    <span class="text-sm text-gray-500">Succursales au total</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-green-50 text-green-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ collect($succursales)->where('is_siege', true)->first()['nom'] ?? 'Aucun Siège' }}
                    </span>
                    <span class="text-sm text-gray-500">Siège Social Principal</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ collect($succursales)->sum('contrats_count') }}
                    </span>
                    <span class="text-sm text-gray-500">Contrats enregistrés</span>
                </div>
            </div>
        </div>

        <!-- Table / Cards List -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <!-- Desktop Table view -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-55 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Nom / Ville</th>
                            <th class="px-6 py-3">Responsable</th>
                            <th class="px-6 py-3">Employés</th>
                            <th class="px-6 py-3">Production</th>
                            <th class="px-6 py-3">Rôle / Statut</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-900">
                        @forelse($succursales as $suc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-600">
                                    {{ $suc->code_succursale }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ $suc->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $suc->ville ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $suc->responsable ? $suc->responsable->nom_complet : 'Aucun' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $suc->employes_count }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $suc->contrats_count }} contrats
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if($suc->is_siege)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Siège</span>
                                        @endif
                                        @if($suc->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <button wire:click="edit({{ $suc->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Modifier</button>
                                    <button wire:click="toggleStatus({{ $suc->id }})" class="text-gray-500 hover:text-gray-800 font-medium">
                                        {{ $suc->is_active ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    Aucune succursale enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View (Cards list) -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($succursales as $suc)
                    <div class="p-4 flex flex-col gap-2 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono text-xs font-bold text-gray-400 mr-2">[{{ $suc->code_succursale }}]</span>
                                <span class="font-bold text-gray-800">{{ $suc->nom }}</span>
                            </div>
                            <div class="flex gap-1">
                                @if($suc->is_siege)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-green-100 text-green-800">Siège</span>
                                @endif
                                @if($suc->is_active)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-800">Active</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 flex flex-wrap gap-x-4 gap-y-1">
                            <div><strong>Responsable:</strong> {{ $suc->responsable ? $suc->responsable->nom_complet : 'Aucun' }}</div>
                            <div><strong>Ville:</strong> {{ $suc->ville ?? 'N/A' }}</div>
                            <div><strong>Stats:</strong> {{ $suc->employes_count }} emp. / {{ $suc->contrats_count }} contr.</div>
                        </div>
                        <div class="flex justify-end gap-3 text-xs mt-2 border-t pt-2 border-gray-100">
                            <button wire:click="edit({{ $suc->id }})" class="text-indigo-600 hover:text-indigo-900 font-semibold">Modifier</button>
                            <button wire:click="toggleStatus({{ $suc->id }})" class="text-gray-500 hover:text-gray-800 font-semibold">
                                {{ $suc->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">
                        Aucune succursale enregistrée.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Form Modal -->
        @if($showModal)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-6 py-6 border-b border-gray-150">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                {{ $isEditing ? 'Modifier la succursale' : 'Ajouter une succursale' }}
                            </h3>
                        </div>
                        <form wire:submit.prevent="save" class="p-6 flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Code Succursale</label>
                                    <input type="text" wire:model="code_succursale" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 font-mono" readonly>
                                    @error('code_succursale') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nom du bureau</label>
                                    <input type="text" wire:model="nom" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('nom') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Ville</label>
                                    <input type="text" wire:model="ville" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('ville') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Téléphone</label>
                                    <input type="text" wire:model="telephone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('telephone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Adresse complète</label>
                                <textarea wire:model="adresse" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                @error('adresse') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Responsable affecté</label>
                                <select wire:model="responsable_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Aucun --</option>
                                    @foreach($employes as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nom_complet }}</option>
                                    @endforeach
                                </select>
                                @error('responsable_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-6 py-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_siege" wire:model="is_siege" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="is_siege" class="ml-2 block text-sm text-gray-700">Désigner comme Siège Principal</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_active" wire:model="is_active" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Actif</label>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 -mx-6 -mb-6 border-t border-gray-150">
                                <button type="button" wire:click="$set('showModal', false)" class="inline-flex justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
