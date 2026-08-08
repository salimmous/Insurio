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
                    <svg width="20" height="20" style="width:20px;height:20px;" class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Recherche & Création Client
                </h3>
                <button wire:click="close" class="text-gray-400 hover:text-gray-600 transition-colors cursor-pointer">
                    <svg width="24" height="24" style="width:24px;height:24px;" class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Search input + Actions -->
            <div class="my-4 flex flex-wrap gap-2 items-center">
                <input wire:model.live.debounce.200ms="search" type="text" placeholder="Rechercher par nom, prénom, email, téléphone ou code..." 
                       class="flex-1 min-w-[220px] bg-gray-50 border border-gray-300 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-400 outline-none transition-all focus:ring-1 focus:ring-indigo-500 shadow-sm text-sm">
                
                <button wire:click="toggleCreateForm" type="button" class="px-4 py-2.5 {{ $showCreateForm ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-emerald-600 text-white hover:bg-emerald-700' }} font-bold text-xs rounded-xl transition-all flex items-center gap-1.5 cursor-pointer shadow-sm">
                    @if($showCreateForm)
                        <span>✕ Annuler</span>
                    @else
                        <svg width="14" height="14" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Nouveau Client</span>
                    @endif
                </button>

                <button wire:click="clearApporteur" type="button" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-xl transition-all cursor-pointer">
                    Aucun Apporteur
                </button>
            </div>

            <!-- Inline Create Client Form -->
            @if($showCreateForm)
            <div class="mb-4 p-4 bg-emerald-50/70 border border-emerald-200/90 rounded-2xl space-y-3 shadow-inner">
                <div class="flex justify-between items-center border-b border-emerald-200/60 pb-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                        <svg width="16" height="16" class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Créer un nouveau client rapidement
                    </h4>
                    <span class="text-[11px] font-semibold text-emerald-700 bg-white px-2 py-0.5 rounded-full border border-emerald-200">Création Rapide</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Type Client</label>
                        <select wire:model="new_type" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                            <option value="particulier">Particulier</option>
                            <option value="entreprise">Société / Entreprise</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nom / Raison Sociale <span class="text-rose-500">*</span></label>
                        <input wire:model="new_nom" type="text" placeholder="Ex: Mouttaki" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                        @error('new_nom') <span class="text-rose-500 text-[10px] block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Prénom</label>
                        <input wire:model="new_prenom" type="text" placeholder="Ex: Youness" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Téléphone</label>
                        <input wire:model="new_telephone" type="text" placeholder="Ex: 0661599799" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">CIN / ICE</label>
                        <input wire:model="new_cin" type="text" placeholder="Ex: AB123456" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email</label>
                        <input wire:model="new_email" type="email" placeholder="client@gmail.com" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="saveNewClient" type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer flex items-center gap-1.5">
                        <svg width="14" height="14" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Enregistrer & Sélectionner</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Table List -->
            <div class="flex-1 overflow-y-auto min-h-[220px]">
                <table class="w-full text-left text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3">Code / Ref</th>
                            <th class="px-4 py-3">Nom & Prénom</th>
                            <th class="px-4 py-3">Téléphone</th>
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
                            <td class="px-4 py-3 text-right">
                                <button wire:click="selectApporteur({{ $app->id }}, '{{ addslashes($app->nom) }}', '{{ addslashes($app->prenom) }}', 0, {{ $app->client_id ?? 'null' }})" 
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg transition duration-150 shadow-sm cursor-pointer">
                                    Sélectionner
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">
                                Aucun client trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                <button wire:click="close" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition duration-150 cursor-pointer">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
