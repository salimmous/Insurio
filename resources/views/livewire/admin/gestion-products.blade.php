<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Produits d'Assurance</h1>
                <p class="text-sm text-gray-500">Configurez et gérez les types et branches d'assurances de votre cabinet.</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Produit
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">{{ $products->total() }}</span>
                    <span class="text-sm text-gray-500">Produits enregistrés</span>
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
                        {{ \App\Models\Product::where('statut', 'actif')->count() }}
                    </span>
                    <span class="text-sm text-gray-500">Produits actifs</span>
                </div>
            </div>
        </div>

        <!-- Filter / Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Rechercher par Code, Nom, Description..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-55 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Code Produit</th>
                            <th class="px-6 py-3">Nom</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">Marge (%)</th>
                            <th class="px-6 py-3">Statut</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-900">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600 text-xs">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $product->nom }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 max-w-xs truncate">
                                    {{ $product->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-teal-600 text-xs">
                                    {{ $product->marge_pourcentage !== null && $product->marge_pourcentage > 0 ? number_format($product->marge_pourcentage, 2) . '%' : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($product->statut === 'actif')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Actif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <button wire:click="openModal({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Modifier</button>
                                    <button onclick="confirm('Supprimer ce produit ?') || event.stopImmediatePropagation()" wire:click="delete({{ $product->id }})" class="text-rose-600 hover:text-rose-900 font-medium">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    Aucun produit enregistré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($products as $product)
                    <div class="p-4 flex flex-col gap-2 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono text-xs font-bold text-indigo-600 mr-2">[{{ $product->code }}]</span>
                                <span class="font-bold text-gray-800">{{ $product->nom }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($product->marge_pourcentage !== null && $product->marge_pourcentage > 0)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-teal-50 text-teal-700 border border-teal-200">{{ number_format($product->marge_pourcentage, 2) }}%</span>
                                @endif
                                @if($product->statut === 'actif')
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Actif</span>
                                @else
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800">Inactif</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-xs text-gray-600">
                            {{ $product->description ?? '-' }}
                        </div>
                        <div class="flex justify-end gap-3 text-xs mt-2 border-t pt-2 border-gray-100">
                            <button wire:click="openModal({{ $product->id }})" class="text-indigo-600 font-semibold">Modifier</button>
                            <button onclick="confirm('Supprimer ce produit ?') || event.stopImmediatePropagation()" wire:click="delete({{ $product->id }})" class="text-rose-600 font-semibold">Supprimer</button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">
                        Aucun produit enregistré.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $products->links() }}
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
                                {{ $productId ? 'Modifier le produit' : 'Ajouter un produit' }}
                            </h3>
                        </div>
                        <form wire:submit.prevent="save" class="p-6 flex flex-col gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Code Produit</label>
                                    <input type="text" wire:model="code" placeholder="ex: HAB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 font-mono uppercase">
                                    @error('code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nom / Libellé</label>
                                    <input type="text" wire:model="nom" placeholder="ex: Habitation" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('nom') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Description</label>
                                <textarea wire:model="description" rows="3" placeholder="Description du produit d'assurance..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Statut</label>
                                    <select wire:model="statut" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="actif">Actif</option>
                                        <option value="inactif">Inactif</option>
                                    </select>
                                    @error('statut') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Marge (%)</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" max="100" wire:model="marge_pourcentage" placeholder="ex: 15.00" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 pr-8 font-mono">
                                        <span class="absolute right-3 top-2 text-xs font-bold text-gray-400 pointer-events-none">%</span>
                                    </div>
                                    @error('marge_pourcentage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
