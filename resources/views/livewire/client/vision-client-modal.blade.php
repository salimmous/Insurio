<div>
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-all duration-300">
        <!-- Main Modal Container: Bottom sheet on mobile, centered modal on desktop -->
        <div class="w-full sm:max-w-3xl bg-white border border-gray-200 rounded-t-2xl sm:rounded-2xl shadow-2xl p-6 overflow-hidden max-h-[90vh] sm:max-h-[85vh] flex flex-col transition-all duration-300 transform translate-y-0">
            
            <!-- Drag indicator for mobile bottom sheet -->
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-4 block sm:hidden"></div>

            <!-- Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Recherche & Vision Client
                </h3>
                <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Search input -->
            <div class="my-4">
                <input wire:model.live="search" type="text" placeholder="Rechercher par nom, prénom, email ou CIN..." 
                       class="w-full bg-gray-50 border border-gray-300 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-400 outline-none transition-all focus:ring-1 focus:ring-indigo-500 shadow-sm text-sm">
            </div>

            <!-- Table / Cards List -->
            <div class="flex-1 overflow-y-auto min-h-[250px]">
                <!-- Desktop view -->
                <div class="hidden sm:block">
                    <table class="w-full text-left text-sm text-gray-700">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-4 py-3">Code Client</th>
                                <th class="px-4 py-3">Nom</th>
                                <th class="px-4 py-3">CIN</th>
                                <th class="px-4 py-3">Solvabilité</th>
                                <th class="px-4 py-3">Incident</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($clients as $client)
                            <tr class="hover:bg-gray-55 transition-colors">
                                <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-500">CL-{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $client->nom }} {{ $client->prenom }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $client->cin ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $client->solvabilite === 'solvable' ? 'bg-green-100 text-green-850' : 'bg-red-100 text-red-850' }}">
                                        {{ ucfirst($client->solvabilite) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($client->incident)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                        Oui
                                    </span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button wire:click="selectClient({{ $client->id }})" 
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition duration-150">
                                        Sélectionner
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                    Aucun client trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="block sm:hidden divide-y divide-gray-100">
                    @forelse($clients as $client)
                    <div class="py-3 flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-mono text-xs text-gray-400 mr-2">[CL-{{ str_pad($client->id, 5, '0', STR_PAD_LEFT) }}]</span>
                                <span class="font-bold text-gray-900">{{ $client->nom }} {{ $client->prenom }}</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $client->solvabilite === 'solvable' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $client->solvabilite }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 flex justify-between items-center">
                            <div>CIN: <span class="font-semibold">{{ $client->cin ?? '-' }}</span></div>
                            @if($client->incident)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Incident</span>
                            @endif
                        </div>
                        <div class="flex justify-end mt-1">
                            <button wire:click="selectClient({{ $client->id }})" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2 rounded-lg transition duration-150 text-center">
                                Sélectionner
                             </button>
                        </div>
                    </div>
                    @empty
                    <div class="py-8 text-center text-gray-400 text-xs">
                        Aucun client trouvé.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                <button wire:click="close" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
