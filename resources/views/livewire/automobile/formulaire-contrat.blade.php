<div class="p-6 bg-slate-50 min-h-screen text-slate-800">
    <div class="w-full px-6 space-y-6">

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
            
            <!-- SECTION 1: Identification & Dates du Contrat -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    1. Identification & Dates du Contrat
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
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

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Attestation</label>
                        <input wire:model="attestation" type="text" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>

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
                        <input wire:model.live="date_echeance" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date Production</label>
                        <input wire:model="date_production" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                    </div>
                </div>

                @if(!empty($historiqueRenouvellementsList) && count($historiqueRenouvellementsList) > 0)
                <div class="mt-4 p-4 bg-amber-50/60 border border-amber-200/80 rounded-xl space-y-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-amber-800 uppercase tracking-wider">
                        <svg width="16" height="16" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Historique des Renouvellements & Dates Précédentes ({{ count($historiqueRenouvellementsList) }})</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 pt-1">
                        @foreach($historiqueRenouvellementsList as $h)
                        <div class="p-2.5 bg-white border border-amber-200/60 rounded-lg text-xs font-mono flex justify-between items-center shadow-2xs">
                            <div>
                                <span class="text-slate-500 block text-[10px] font-sans">Renouvelé le {{ $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-' }}</span>
                                <span class="font-bold text-slate-800">{{ $h->anc_date_effet ? $h->anc_date_effet->format('d/m/Y') : '-' }} ➔ {{ $h->anc_date_echeance ? $h->anc_date_echeance->format('d/m/Y') : '-' }}</span>
                            </div>
                            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded border border-emerald-200">{{ number_format($h->prime_totale, 2) }} DH</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
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
                            <label class="block text-sm font-medium text-slate-500 mb-2">Apporteur</label>
                            <div class="flex gap-3">
                                <input type="text" readonly wire:model="nom_apporteur" placeholder="Sélectionnez un apporteur..." 
                                       class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 outline-none cursor-pointer"
                                       wire:click="$dispatch('openVisionApporteur')">
                                <button type="button" wire:click="$dispatch('openVisionApporteur')" 
                                        class="bg-teal-600 hover:bg-teal-500 text-white font-medium px-5 rounded-xl transition-all shadow-sm">
                                    Rechercher
                                </button>
                            </div>
                        </div>

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
                    </div>
                </div>

                <!-- Véhicule -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-lg font-semibold text-teal-600 border-b border-slate-100 pb-2 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h2m-6 0a1 1 0 001-1v-3a1 1 0 00-1-1H9m12 0h-3M12 9h4"/></svg>
                            3. Spécifications Véhicule
                        </span>
                        <span class="text-xs font-mono bg-teal-50 text-teal-700 px-2 py-0.5 rounded border border-teal-200 font-bold">
                            {{ $matricule ? $matricule : 'Non renseigné' }}
                        </span>
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Matricule -->
                        <div class="col-span-1 sm:col-span-2 bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                            <label class="block text-sm font-bold text-slate-800 mb-1.5 flex items-center justify-between">
                                <span>Matricule / Immatriculation</span>
                                <span class="text-xs text-rose-500 font-bold">* Obligatoire</span>
                            </label>
                            <input type="text" wire:model="matricule" placeholder="ex: 12345-A-6 ou 12345-أ-6" class="w-full bg-white border border-slate-300 focus:border-teal-600 focus:ring-teal-600 rounded-xl px-4 py-2.5 text-slate-900 font-mono font-bold text-base uppercase shadow-2xs outline-none transition-all placeholder-slate-400">
                        </div>

                        <!-- Usage -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Usage</label>
                            <select wire:model="usage" class="w-full bg-white border border-slate-300 focus:border-teal-600 focus:ring-teal-600 rounded-xl px-4 py-2.5 text-slate-900 font-medium outline-none shadow-2xs transition-all">
                                <option value="">Sélectionner usage...</option>
                                <option value="A">A - Promenade & Affaires</option>
                                <option value="B">B - Commerce</option>
                                <option value="C">C - Transport public</option>
                                <option value="D">D - Transport personnel</option>
                                <option value="E">E - Usage Spécial</option>
                            </select>
                        </div>

                        <!-- Marque véhicule -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Marque véhicule</label>
                            <input type="text" list="marques-catalog" wire:model.live="marque" placeholder="Saisir ou choisir marque..." class="w-full bg-white border border-slate-300 focus:border-teal-600 focus:ring-teal-600 rounded-xl px-4 py-2.5 text-slate-900 font-sans font-semibold outline-none shadow-2xs transition-all">
                            <datalist id="marques-catalog">
                                @foreach($this->getMarquesDisponibles() as $m)
                                    <option value="{{ $m }}">
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Modèle -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Modèle</label>
                            <input type="text" list="modeles-catalog" wire:model="modele" placeholder="Saisir ou choisir modèle..." class="w-full bg-white border border-slate-300 focus:border-teal-600 focus:ring-teal-600 rounded-xl px-4 py-2.5 text-slate-900 font-sans font-semibold outline-none shadow-2xs transition-all">
                            <datalist id="modeles-catalog">
                                @foreach($this->getModelesDisponibles() as $mod)
                                    <option value="{{ $mod }}">
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Sous CLASSE -->
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Sous CLASSE</label>
                            <div class="flex items-center gap-4 py-2">
                                <label class="inline-flex items-center text-slate-800 font-medium cursor-pointer">
                                    <input type="radio" wire:model="sous_classe" value="Definitive" class="text-teal-600 bg-white border-slate-300 focus:ring-teal-500">
                                    <span class="ms-2">Définitive</span>
                                </label>
                                <label class="inline-flex items-center text-slate-800 font-medium cursor-pointer">
                                    <input type="radio" wire:model="sous_classe" value="Provisoire" class="text-teal-600 bg-white border-slate-300 focus:ring-teal-500">
                                    <span class="ms-2">Provisoire</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Bloc Automobile -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h2 class="text-md font-semibold text-emerald-600 border-b border-slate-100 pb-2 flex justify-between items-center">
                        <span>Calculs Partie AUTOMOBILE</span>
                    </h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Prime Nette</label>
                            <input wire:model.live="prime_rc" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Taxe</label>
                            <input wire:model.live="taxe_auto" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Acc</label>
                            <input wire:model.live="accessoire_auto_cie" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Prime Total</label>
                            <input wire:model.live="prime_totale" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono font-bold">
                        </div>
                    </div>
                </div>

            <!-- SUMMARY BLOCK: Récapitulatif du Tarif Contrat -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 text-white rounded-2xl p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-teal-400"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-200">Synthèse Financière Contrat</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800">
                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Prime Nette (HT)</span>
                        <div class="text-2xl font-bold text-white mt-1 font-mono">{{ number_format($this->primeNette, 2) }} DH</div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Base Pre-Tax</span>
                    </div>
                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Taxes & Accessoires</span>
                        <div class="text-2xl font-bold text-slate-200 mt-1 font-mono">{{ number_format($this->totalTaxe, 2) }} DH</div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">Taxes & Frais</span>
                    </div>
                    <div class="py-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-400 block">Prime Totale (TTC)</span>
                        <div class="text-3xl font-extrabold text-teal-400 mt-1 font-mono">{{ number_format($this->primeTotale, 2) }} DH</div>
                        <span class="text-[10px] text-teal-400/80 block mt-0.5">À Régler par Client</span>
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
    <!-- Vision Apporteur Modal Component -->
    @livewire('apporteur.vision-apporteur-modal')
</div>
