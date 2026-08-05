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
                        <input wire:model="date_echeance" type="date" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 outline-none cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-2">Date Production</label>
                        <input wire:model="date_production" type="date" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all font-mono">
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

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Apporteur</label>
                            <select wire:model.live="apporteur_id" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                <option value="">Aucun</option>
                                @foreach($apporteurs as $apporteur)
                                <option value="{{ $apporteur->id }}">{{ $apporteur->nom }} {{ $apporteur->prenom }}</option>
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
                            <label class="block text-sm font-medium text-slate-500 mb-2">Marque véhicule</label>
                            <select wire:model.live="marque" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all">
                                <option value="">— Sélectionner marque —</option>
                                @foreach($this->getMarquesDisponibles() as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Modèle</label>
                            <select wire:model="modele" class="w-full bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 text-slate-800 outline-none transition-all {{ !$marque ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$marque ? 'disabled' : '' }}>
                                <option value="">— Sélectionner modèle —</option>
                                @foreach($this->getModelesDisponibles() as $mod)
                                    <option value="{{ $mod }}">{{ $mod }}</option>
                                @endforeach
                            </select>
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

            <!-- SUMMARY BLOCK: Totaux & Calcul de Marge HT Avant Taxes -->
            <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 text-white rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full bg-teal-400"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-200">Synthèse Financière & Calcul Marge HT (Avant Taxes)</h3>
                    </div>
                    <span class="text-xs text-teal-400 font-mono font-bold">Marge Produit : {{ number_format($this->margePourcentage, 2) }}%</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-center divide-x divide-slate-800">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Prime Nette (HT)</span>
                        <div class="text-xl font-bold text-white mt-1 font-mono">{{ number_format($this->primeNette, 2) }} DH</div>
                        <span class="text-[9px] text-slate-400 block mt-0.5">Base Pre-Tax</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-400 block">Marge Brute HT</span>
                        <div class="text-xl font-bold text-teal-400 mt-1 font-mono">{{ number_format($this->margeBruteHt, 2) }} DH</div>
                        <span class="text-[9px] text-teal-500 block mt-0.5">{{ number_format($this->margePourcentage, 2) }}% sur HT</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block">Commissions</span>
                        <div class="text-xl font-bold text-amber-400 mt-1 font-mono">{{ number_format($this->totalCommission, 2) }} DH</div>
                        <span class="text-[9px] text-amber-500 block mt-0.5">Apporteur / Intermédiaire</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block">Bénéfice Net Agence</span>
                        <div class="text-2xl font-black text-emerald-400 mt-1 font-mono">{{ number_format($this->beneficeNet, 2) }} DH</div>
                        <span class="text-[9px] text-emerald-500 block mt-0.5">Marge HT - Comm</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Taxes & Timbre</span>
                        <div class="text-xl font-bold text-slate-300 mt-1 font-mono">{{ number_format($this->totalTaxe, 2) }} DH</div>
                        <span class="text-[9px] text-slate-400 block mt-0.5">Taxes Éthiques / État</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-300 block">Prime Totale (TTC)</span>
                        <div class="text-2xl font-extrabold text-indigo-400 mt-1 font-mono">{{ number_format($this->primeTotale, 2) }} DH</div>
                        <span class="text-[9px] text-indigo-400 block mt-0.5">À Régler par Client</span>
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
