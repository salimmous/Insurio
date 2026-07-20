<div class="p-6 bg-slate-50 min-h-screen text-slate-800">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Banner context -->
        <div class="flex justify-between items-center bg-white border border-slate-200/80 rounded-2xl px-6 py-4 shadow-sm">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-teal-600">{{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}</span>
                <h1 class="text-2xl font-bold text-slate-900 mt-0.5">
                    {{ $contratId ? 'Modifier le Contrat' : "Nouveau Contrat d'Assurance" }}
                </h1>
            </div>
            <a href="{{ route('automobile.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-5 py-2.5 rounded-xl transition-all flex items-center gap-2 border border-slate-200/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- SECTION 1: Identification & dates -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    1. Identification & Dates du Contrat
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Produit d'Assurance</label>
                        <select wire:model.live="product_id" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-semibold">
                            <option value="">Sélectionner...</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->nom }}</option>
                            @endforeach
                        </select>
                        @error('product_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Référence Cabinet</label>
                        <input wire:model="numero_contrat" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        @error('numero_contrat') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Terme</label>
                        <div class="flex items-center gap-4 py-2">
                            <label class="inline-flex items-center text-slate-700">
                                <input type="radio" wire:model="terme" :value="true" class="text-teal-600 bg-slate-50 border-slate-200 focus:ring-teal-500">
                                <span class="ms-2">Oui</span>
                            </label>
                            <label class="inline-flex items-center text-slate-700">
                                <input type="radio" wire:model="terme" :value="false" class="text-teal-600 bg-slate-50 border-slate-200 focus:ring-teal-500">
                                <span class="ms-2">Non</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Compagnie d'Assurance</label>
                        <select wire:model="compagnie_id" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                            <option value="">Sélectionner...</option>
                            @foreach($compagnies as $compagnie)
                            <option value="{{ $compagnie->id }}">{{ $compagnie->nom }}</option>
                            @endforeach
                        </select>
                        @error('compagnie_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Numéro Police</label>
                        <input wire:model="police" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        @error('police') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Avenant</label>
                        <input wire:model="avenant" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Type d'Affaire</label>
                        <select wire:model="type_affaire" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                            <option value="AN">Affaire Nouvelle (AN)</option>
                            <option value="RN">Renouvellement (RN)</option>
                            <option value="RC">Remplacement (RC)</option>
                            <option value="AV">Avenant (AV)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Attestation</label>
                        <input wire:model="attestation" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Quittance</label>
                        <input wire:model="quittance" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date d'effet</label>
                        <input wire:model.live="date_effet" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        @error('date_effet') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Durée (Mois)</label>
                        <input wire:model.live="nbr_mois" type="number" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date d'échéance</label>
                        <input wire:model="date_echeance" type="date" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 outline-none cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date Production</label>
                        <input wire:model="date_production" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date résiliation</label>
                        <input wire:model="date_resiliation" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Client, Apporteur & Véhicule -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Client & Apporteur -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        2. Client & Apporteur
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Client (Souscripteur)</label>
                            <div class="flex gap-3">
                                <input type="text" readonly wire:model="souscripteur" placeholder="Sélectionnez un client..." 
                                       class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 outline-none cursor-pointer"
                                       wire:click="$dispatch('openVisionClient')">
                                <button type="button" wire:click="$dispatch('openVisionClient')" 
                                        class="bg-teal-600 hover:bg-teal-500 text-white font-medium px-5 rounded-xl transition-all shadow-sm">
                                    Rechercher
                                </button>
                            </div>
                            @error('client_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-2">Apporteur</label>
                                <select wire:model.live="apporteur_id" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                    <option value="">Aucun</option>
                                    @foreach($apporteurs as $apporteur)
                                    <option value="{{ $apporteur->id }}">{{ $apporteur->nom }} {{ $apporteur->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-2">Nom Apporteur</label>
                                <input type="text" readonly wire:model="nom_apporteur" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 outline-none cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-2">Code Branche</label>
                                <input wire:model="branche_code" type="text" placeholder="ex: 116" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-slate-500 mb-2">Libellé Branche</label>
                                <input wire:model="branche_libelle" type="text" placeholder="ex: VELO < OU = 50CC" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Branche Rattachée (Agence)</label>
                            <select wire:model="branch_id" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                <option value="">Sélectionner...</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2m-6 0a1 1 0 001-1v-3a1 1 0 00-1-1H9m12 0h-3M12 9h4"/></svg>
                        3. Spécifications Véhicule
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Usage</label>
                            <select wire:model="usage" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                <option value="">Sélectionner...</option>
                                <option value="A">A - Promenade & Affaires</option>
                                <option value="B">B - Commerce</option>
                                <option value="C">C - Transport public</option>
                                <option value="D">D - Transport personnel</option>
                                <option value="E">E - Usage Spécial</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Matricule</label>
                            <input wire:model="matricule" type="text" placeholder="12345-A-26" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Marque véhicule</label>
                            <input wire:model="marque" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Code CLASSE</label>
                            <input wire:model="code_classe" type="text" placeholder="Classe tarifaire" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Sous CLASSE</label>
                            <div class="flex items-center gap-4 py-2">
                                <label class="inline-flex items-center text-slate-700">
                                    <input type="radio" wire:model="sous_classe" value="Definitive" class="text-teal-600 bg-slate-50 border-slate-200 focus:ring-teal-500">
                                    <span class="ms-2">Définitive</span>
                                </label>
                                <label class="inline-flex items-center text-slate-700">
                                    <input type="radio" wire:model="sous_classe" value="Provisoire" class="text-teal-600 bg-slate-50 border-slate-200 focus:ring-teal-500">
                                    <span class="ms-2">Provisoire</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Puis. fiscale</label>
                            <input wire:model="puissance_fiscale" type="number" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Nb place</label>
                            <input wire:model="nb_places" type="number" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Carburant</label>
                            <select wire:model="carburant" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                <option value="">Sélectionner...</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Essence">Essence</option>
                                <option value="Hybride">Hybride</option>
                                <option value="Electrique">Électrique</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Val. Véhicule</label>
                            <input wire:model="valeur_vehicule" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">D.Mise Circul.</label>
                            <input wire:model="date_mise_circulation" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Primes de Garanties -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    4. Primes Garanties
                </h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Prime RC (Obligatoire)</label>
                        <input wire:model.live="prime_rc" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Défense & Recours (DR)</label>
                        <input wire:model.live="def_rec" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Tierce</label>
                        <input wire:model.live="tierce" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Collision</label>
                        <input wire:model.live="collision" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Vol</label>
                        <input wire:model.live="vol" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Incendie</label>
                        <input wire:model.live="incendie" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Bris de Glace</label>
                        <input wire:model.live="bris_glace" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Individuelle (Conducteur)</label>
                        <input wire:model.live="individuel" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Bloc de Calculs & Taxes (Side-by-Side Auto vs PTA) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Bloc Automobile -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-md font-semibold text-emerald-600 border-b border-slate-100 pb-2 flex justify-between items-center">
                        <span>Calculs Partie AUTOMOBILE</span>
                        <span class="text-xs text-slate-400 uppercase">Bloc 1</span>
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Taxe Auto</label>
                            <input wire:model.live="taxe_auto" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Accessoires Compagnie</label>
                            <input wire:model.live="accessoire_auto_cie" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Droit de Timbre</label>
                            <input wire:model.live="timbre" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Commission Auto</label>
                            <input wire:model.live="commission_auto" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Taxe sur Commission (TPS)</label>
                            <input wire:model.live="tps_auto" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>
                    </div>
                </div>

                <!-- Bloc PTA (Garanties Annexes) -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-md font-semibold text-indigo-600 border-b border-slate-100 pb-2 flex justify-between items-center">
                        <span>Calculs Partie PTA (Annexes)</span>
                        <span class="text-xs text-slate-400 uppercase">Bloc 2</span>
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Garantie PTA (Montant)</label>
                            <input wire:model.live="montant_pta" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Taxe PTA</label>
                            <input wire:model.live="montant_taxe_pta" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Commission PTA</label>
                            <input wire:model.live="commission_pta" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Taxe Commission (TPS)</label>
                            <input wire:model.live="tps_pta" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Autres Accessoires</label>
                            <input wire:model.live="accessoires" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY BLOCK: Totaux en bas -->
            <div class="bg-gradient-to-r from-teal-50 via-slate-50 to-indigo-50 border border-teal-200/60 rounded-2xl p-6 shadow-sm space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center divide-x divide-slate-200">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Prime Nette</span>
                        <div class="text-2xl font-bold text-slate-800 mt-1 font-mono">{{ number_format($this->primeNette, 2) }} DH</div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Taxes</span>
                        <div class="text-2xl font-bold text-slate-800 mt-1 font-mono">{{ number_format($this->totalTaxe, 2) }} DH</div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Commissions</span>
                        <div class="text-2xl font-bold text-emerald-600 mt-1 font-mono">{{ number_format($this->totalCommission, 2) }} DH</div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total TPS</span>
                        <div class="text-2xl font-bold text-slate-800 mt-1 font-mono">{{ number_format($this->totalTps, 2) }} DH</div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-teal-600">Prime Totale (À Payer)</span>
                        <div class="text-3xl font-extrabold text-teal-600 mt-1 font-mono">{{ number_format($this->primeTotale, 2) }} DH</div>
                    </div>
                </div>
            </div>

            <!-- Actions buttons -->
            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('automobile.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium px-6 py-3 rounded-xl transition-all">
                    Annuler
                </a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-8 py-3 rounded-xl transition-all shadow-sm">
                    {{ $contratId ? 'Enregistrer les modifications' : 'Émettre le Contrat Auto' }}
                </button>
            </div>

        </form>

    </div>

    <!-- Vision Client Modal Component -->
    @livewire('client.vision-client-modal')
</div>
