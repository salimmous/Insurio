<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-teal-50 rounded-xl text-teal-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Catalogue Véhicules & Marques</h1>
                    <p class="text-xs text-slate-500">Gestion centralisée des marques, modèles, motos et autocars</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-4 px-4 py-2 bg-slate-50 rounded-xl border border-slate-200/60 text-xs font-medium text-slate-600">
                <span>🏷️ <strong class="text-slate-900 font-mono">{{ $totalMarques }}</strong> Marques</span>
                <span class="text-slate-300">|</span>
                <span>🚗 <strong class="text-slate-900 font-mono">{{ $totalModeles }}</strong> Modèles</span>
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
                <span>✅</span>
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
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $filterType === 'voiture' ? 'bg-white text-teal-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                🚗 Voitures
            </button>
            <button wire:click="$set('filterType', 'moto')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $filterType === 'moto' ? 'bg-white text-amber-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                🏍️ Motos
            </button>
            <button wire:click="$set('filterType', 'autocar')" 
                    class="px-3 py-1.5 text-xs font-bold rounded-lg transition {{ $filterType === 'autocar' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-500 hover:text-slate-700' }}">
                🚌 Autocars / Minibus
            </button>
        </div>
    </div>

    <!-- Brands Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($marques as $marque)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden">
                <!-- Card Header -->
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shadow-xs
                                    {{ $marque->type === 'voiture' ? 'bg-teal-100 text-teal-800' : ($marque->type === 'moto' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800') }}">
                            @if($marque->type === 'voiture') 🚗 @elseif($marque->type === 'moto') 🏍️ @else 🚌 @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">{{ $marque->nom }}</h3>
                            <span class="text-[10px] font-semibold tracking-wide uppercase px-2 py-0.5 rounded-md 
                                         {{ $marque->type === 'voiture' ? 'bg-teal-50 text-teal-600 border border-teal-100' : ($marque->type === 'moto' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100') }}">
                                {{ $marque->type }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <button wire:click="openBrandModal({{ $marque->id }})" title="Modifier Marque" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:confirm="Voulez-vous supprimer cette marque et tous ses modèles ?" wire:click="deleteBrand({{ $marque->id }})" title="Supprimer Marque" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Modèles Pill Cloud -->
                <div class="p-4 flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Modèles ({{ $marque->modeles->count() }})</span>
                        <button wire:click="openModelModal({{ $marque->id }})" class="text-[11px] font-bold text-teal-600 hover:text-teal-800 transition flex items-center gap-1">
                            <span>+ Ajouter Modèle</span>
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto pr-1">
                        @forelse($marque->modeles as $modele)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition group">
                                {{ $modele->nom }}
                                <button wire:click="openModelModal({{ $marque->id }}, {{ $modele->id }})" class="text-slate-400 hover:text-slate-700 opacity-0 group-hover:opacity-100 transition">✎</button>
                                <button wire:confirm="Supprimer le modèle {{ $modele->nom }} ?" wire:click="deleteModel({{ $modele->id }})" class="text-slate-400 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition">✕</button>
                            </span>
                        @empty
                            <span class="text-xs text-slate-400 italic">Aucun modèle configuré</span>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200/80 text-center">
                <div class="text-4xl mb-3">🚗</div>
                <h3 class="text-base font-bold text-slate-700">Aucune marque trouvée</h3>
                <p class="text-xs text-slate-500 mt-1">Essayez un autre mot-clé ou ajoutez une nouvelle marque.</p>
            </div>
        @endforelse
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
                        <input wire:model="modelNom" type="text" placeholder="Ex: Golf 8, Clio V, TMAX 560..." 
                               class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 outline-none">
                        @error('modelNom') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
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
