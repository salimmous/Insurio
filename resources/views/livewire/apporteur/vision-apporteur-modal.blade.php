<div>
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-all duration-300">
        <!-- Main Modal Container -->
        <div class="w-full sm:max-w-3xl bg-white border border-gray-200 rounded-t-2xl sm:rounded-2xl shadow-2xl p-6 overflow-hidden max-h-[90vh] sm:max-h-[85vh] flex flex-col transition-all duration-300 transform translate-y-0">
            
            <!-- Mobile indicator -->
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-4 block sm:hidden"></div>

            <!-- Header -->
            <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Recherche Apporteur d'Affaires
                </h3>
                <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Search input + Clear option -->
            <div class="my-4 flex gap-3">
                <input wire:model.live.debounce.200ms="search" type="text" placeholder="Rechercher par nom, prénom, email, téléphone ou code..." 
                       class="flex-1 bg-gray-50 border border-gray-300 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-400 outline-none transition-all focus:ring-1 focus:ring-indigo-500 shadow-sm text-sm">
                <button wire:click="clearApporteur" type="button" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl transition-all">
                    Aucun Apporteur
                </button>
            </div>

            <!-- Table List -->
            <div class="flex-1 overflow-y-auto min-h-[250px]">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3">Code / Ref</th>
                            <th class="px-4 py-3">Nom & Prénom</th>
                            <th class="px-4 py-3">Téléphone</th>
                            <th class="px-4 py-3">Taux Commission</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($apporteurs as $app)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs font-semibold text-indigo-600">
                                {{ $app->code }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-900 flex items-center gap-2">
                                <span>{{ $app->nom }} {{ $app->prenom }}</span>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $app->source === 'Client' ? 'bg-sky-100 text-sky-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $app->source }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 font-mono">{{ $app->telephone ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-emerald-600 font-mono">
                                {{ number_format($app->taux_commission ?? 10, 2) }}%
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="selectApporteur({{ $app->id }}, '{{ addslashes($app->nom) }}', '{{ addslashes($app->prenom) }}', {{ $app->taux_commission }})" 
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg transition duration-150 shadow-sm">
                                    Sélectionner
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                Aucun apporteur ou client trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
