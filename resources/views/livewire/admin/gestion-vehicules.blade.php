<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-50 rounded-xl text-teal-600 flex items-center justify-center border border-teal-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"/>
                        <circle cx="7" cy="17" r="2"/>
                        <path d="M9 17h6"/>
                        <circle cx="17" cy="17" r="2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Catalogue Véhicules & Marques</h1>
                    <p class="text-xs text-slate-500">Gestion des marques, logos, modèles et années de production</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-4 px-4 py-2 bg-slate-50 rounded-xl border border-slate-200/60 text-xs font-medium text-slate-600">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <strong class="text-slate-900 font-mono">{{ $totalMarques }}</strong> Marques
                </span>
                <span class="text-slate-300">|</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <strong class="text-slate-900 font-mono">{{ $totalModeles }}</strong> Modèles
                </span>
            </div>

            <button wire:click="openBrandModal()" class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une Marque
            </button>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search -->
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher une marque ou modèle..." 
                   class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl pl-10 pr-4 py-2 text-xs text-slate-800 outline-none transition">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl w-full md:w-auto">
            <button wire:click="$set('filterType', 'all')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $filterType === 'all' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                Tous ({{ $totalMarques }})
            </button>
            <button wire:click="$set('filterType', 'voiture')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition flex items-center gap-1.5 {{ $filterType === 'voiture' ? 'bg-white text-teal-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11 2 11.5 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                Voitures
            </button>
            <button wire:click="$set('filterType', 'moto')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition flex items-center gap-1.5 {{ $filterType === 'moto' ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Motos
            </button>
            <button wire:click="$set('filterType', 'autocar')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition flex items-center gap-1.5 {{ $filterType === 'autocar' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8m-8 4h8m-5 4h5M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                Autocars / Minibus
            </button>
        </div>
    </div>

    <!-- Brands Table Rows View -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700 min-w-[750px]">
                <thead class="bg-slate-50/80 border-b border-slate-200/80 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5 w-64">Marque</th>
                        <th class="px-5 py-3.5 w-36">Type</th>
                        <th class="px-5 py-3.5">Modèles Configurés</th>
                        <th class="px-5 py-3.5 text-right w-44">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($marques as $marque)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Marque info & Logo -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/80 p-1 flex items-center justify-center shrink-0 relative overflow-hidden shadow-2xs">
                                        <img src="{{ $marque->logo_url }}" 
                                             alt="{{ $marque->nom }}" 
                                             class="max-w-full max-h-full object-contain"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                                        <div class="w-full h-full bg-slate-100 text-slate-700 font-black text-xs items-center justify-center rounded-lg uppercase hidden">
                                            {{ substr($marque->nom, 0, 2) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-sm">{{ $marque->nom }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold">{{ $marque->modeles->count() }} modèle(s)</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Type Badge -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-[10px] font-bold tracking-wide uppercase px-2.5 py-1 rounded-md inline-flex items-center gap-1
                                             {{ $marque->type === 'voiture' ? 'bg-teal-50 text-teal-700 border border-teal-200' : ($marque->type === 'moto' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-indigo-50 text-indigo-700 border border-indigo-200') }}">
                                    {{ $marque->type === 'voiture' ? '🚗 Voiture' : ($marque->type === 'moto' ? '🏍️ Moto' : '🚌 Autocar') }}
                                </span>
                            </td>

                            <!-- Modèles Pills List -->
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    @forelse($marque->modeles as $modele)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition group">
                                            <span>{{ $modele->nom }}</span>
                                            @if($modele->libelle_annee)
                                                <span class="text-[10px] font-mono text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded-md font-semibold border border-teal-100/80">{{ $modele->libelle_annee }}</span>
                                            @endif
                                            <button wire:click="openModelModal({{ $marque->id }}, {{ $modele->id }})" title="Modifier Modèle" class="text-slate-400 hover:text-slate-700 opacity-0 group-hover:opacity-100 transition">✎</button>
                                            <button wire:confirm="Supprimer le modèle {{ $modele->nom }} ?" wire:click="deleteModel({{ $modele->id }})" title="Supprimer Modèle" class="text-slate-400 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition">✕</button>
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Aucun modèle configuré</span>
                                    @endforelse

                                    <button wire:click="openModelModal({{ $marque->id }})" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition px-2 py-1 bg-teal-50 hover:bg-teal-100 rounded-lg border border-teal-200/60 inline-flex items-center gap-1">
                                        <span>+ Ajouter Modèle</span>
                                    </button>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button wire:click="openBrandModal({{ $marque->id }})" title="Modifier Marque / Logo" class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:confirm="Voulez-vous supprimer cette marque et tous ses modèles ?" wire:click="deleteBrand({{ $marque->id }})" title="Supprimer Marque" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center">
                                <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl mx-auto flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-700">Aucune marque trouvée</h3>
                                <p class="text-xs text-slate-500 mt-1">Essayez un autre mot-clé ou ajoutez une nouvelle marque.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Brand -->
    @if($showBrandModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-800">{{ $brandId ? 'Modifier la Marque' : 'Nouvelle Marque' }}</h3>
                    <button wire:click="$set('showBrandModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Nom de la Marque</label>
                        <input wire:model="brandNom" type="text" placeholder="Ex: Tesla, Dacia, Yamaha..." 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none">
                        @error('brandNom') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Type de Véhicule</label>
                        <select wire:model="brandType" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none">
                            <option value="voiture">🚗 Voiture (Automobile / Camionnette)</option>
                            <option value="moto">🏍️ Moto / Scooter</option>
                            <option value="autocar">🚌 Autocar / Minibus</option>
                        </select>
                        @error('brandType') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">URL du Logo (Optionnel)</label>
                        <input wire:model="brandLogo" type="url" placeholder="https://cdn.simpleicons.org/tesla/CC0000 (Laissez vide pour auto)" 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none font-mono">
                        <p class="text-[11px] text-slate-400 mt-1">Par défaut, le logo officiel est chargé automatiquement.</p>
                        @error('brandLogo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showBrandModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Annuler</button>
                    <button wire:click="saveBrand" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition">Enregistrer</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Model -->
    @if($showModelModal)
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-800">{{ $modelId ? 'Modifier le Modèle' : 'Nouveau Modèle' }}</h3>
                    <button wire:click="$set('showModelModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Nom du Modèle</label>
                        <input wire:model="modelNom" type="text" placeholder="Ex: Golf 8, Clio V, Logan, TMAX..." 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none">
                        @error('modelNom') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5">Année Début (Optionnel)</label>
                            <input wire:model="modelAnneeDebut" type="number" placeholder="Ex: 2018" 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none font-mono">
                            @error('modelAnneeDebut') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1.5">Année Fin (Optionnel)</label>
                            <input wire:model="modelAnneeFin" type="number" placeholder="Ex: 2026" 
                                   class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none font-mono">
                            @error('modelAnneeFin') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showModelModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Annuler</button>
                    <button wire:click="saveModel" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-bold transition">Enregistrer</button>
                </div>
            </div>
        </div>
    @endif
</div>
