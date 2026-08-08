<div class="p-4 md:p-5 bg-slate-50 min-h-screen text-slate-800 flex flex-col gap-4">

    <!-- Top header & filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white border border-slate-200/80 rounded-xl px-5 py-3 shadow-sm gap-3">
        <div>
            @if($isRenouvellements)
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Échéances & Relances</span>
                <h1 class="text-xl font-bold text-slate-900 mt-0.5">Gestion des Renouvellements Assurance</h1>
            @else
                <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600">Production</span>
                <h1 class="text-xl font-bold text-slate-900 mt-0.5">Registre de Production Assurance</h1>
            @endif
        </div>
        
        <!-- Search & Filter Controls -->
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input wire:model.live="search" type="text" placeholder="Rechercher client, contrat, police, immatriculation..." 
                   class="bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2 text-sm text-slate-800 placeholder-slate-400 outline-none transition-all w-full md:w-80">

            <a href="{{ route('automobile.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-3.5 py-2 rounded-xl text-xs transition-all shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Nouveau</span>
            </a>
        </div>
    </div>

    <!-- Date Filter Bar -->
    <div class="bg-white border border-slate-200/80 rounded-xl px-5 py-3 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Filtrer par Date :
            </span>

            <select wire:model.live="dateField" class="bg-slate-50 border border-slate-200 focus:border-teal-500 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 outline-none">
                <option value="date_echeance">Date d'échéance (Expiration)</option>
                <option value="date_effet">Date d'effet (Début)</option>
                <option value="date_production">Date de production (Saisie)</option>
            </select>

            <div class="flex items-center gap-1.5">
                <span class="text-xs text-slate-400 font-medium">Du:</span>
                <input wire:model.live="dateFrom" type="date" class="bg-slate-50 border border-slate-200 focus:border-teal-500 rounded-xl px-2.5 py-1 text-xs text-slate-800 outline-none">
            </div>

            <div class="flex items-center gap-1.5">
                <span class="text-xs text-slate-400 font-medium">Au:</span>
                <input wire:model.live="dateTo" type="date" class="bg-slate-50 border border-slate-200 focus:border-teal-500 rounded-xl px-2.5 py-1 text-xs text-slate-800 outline-none">
            </div>
        </div>

        <!-- Quick Date Range Preset Pills -->
        <div class="flex flex-wrap items-center gap-1.5">
            <button wire:click="setDateRangePreset('today')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">Aujourd'hui</button>
            <button wire:click="setDateRangePreset('this_month')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">Ce mois</button>
            <button wire:click="setDateRangePreset('this_quarter')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">Ce trimestre</button>
            <button wire:click="setDateRangePreset('this_year')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all">Cette année</button>
            @if(!empty($dateFrom) || !empty($dateTo))
            <button wire:click="setDateRangePreset('clear')" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-600 transition-all">Effacer date</button>
            @endif
        </div>
    </div>

    @if(!$isRenouvellements)
    <!-- Production Payment Quick Filters -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3 shadow-sm flex flex-wrap items-center gap-3">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">Filtrer par Règlement :</span>
        
        <!-- Soldé (Payé) -->
        <button wire:click="$set('filterStatut', 'reglement_solde')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $filterStatut === 'reglement_solde' ? 'bg-emerald-600 text-white shadow-md' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' }}">
            <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Soldé (Payé)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-black {{ $filterStatut === 'reglement_solde' ? 'bg-white text-emerald-800' : 'bg-emerald-200 text-emerald-900' }}">{{ $countReglementSolde }}</span>
        </button>

        <!-- Paiement Partiel -->
        <button wire:click="$set('filterStatut', 'reglement_partiel')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $filterStatut === 'reglement_partiel' ? 'bg-amber-600 text-white shadow-md' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' }}">
            <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Paiement Partiel</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-black {{ $filterStatut === 'reglement_partiel' ? 'bg-white text-amber-800' : 'bg-amber-200 text-amber-900' }}">{{ $countReglementPartiel }}</span>
        </button>

        <!-- Non Payé -->
        <button wire:click="$set('filterStatut', 'reglement_non_paye')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $filterStatut === 'reglement_non_paye' ? 'bg-rose-600 text-white shadow-md' : 'bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200' }}">
            <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Non Payé</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-black {{ $filterStatut === 'reglement_non_paye' ? 'bg-white text-rose-800' : 'bg-rose-200 text-rose-900' }}">{{ $countReglementNonPaye }}</span>
        </button>

        <!-- Reste à Payer (Non Soldé) -->
        <button wire:click="$set('filterStatut', 'reglement_impaye')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $filterStatut === 'reglement_impaye' ? 'bg-purple-600 text-white shadow-md' : 'bg-purple-50 text-purple-800 hover:bg-purple-100 border border-purple-200' }}">
            <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Reste à Payer (Non Soldé)</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-black {{ $filterStatut === 'reglement_impaye' ? 'bg-white text-purple-800' : 'bg-purple-200 text-purple-900' }}">{{ $countReglementImpaye }}</span>
        </button>

        <!-- Tous les contrats -->
        <button wire:click="$set('filterStatut', '')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ empty($filterStatut) ? 'bg-slate-800 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200' }}">
            <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 shrink-0 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span>Tous les contrats</span>
        </button>
    </div>
    @endif

    <!-- Alert Message -->
    @if (session()->has('message'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold">
        {{ session('message') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
        {{ session('error') }}
    </div>
    @endif

    <!-- Content Area (Full width) -->
    <div class="flex flex-col gap-6">
        
        <!-- Table / Grid -->
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">
                                <input type="checkbox" wire:model.live="selectAll" class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 cursor-pointer">
                            </th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">ID</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Réf</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Code client</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Nom du client</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Police</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Avenant</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Attest</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Matricule</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Marque & Modèle</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Date d'effet</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Expiration</th>
                            <th class="px-2.5 py-2.5 text-right whitespace-nowrap">Prime Total</th>
                            @if(!$isRenouvellements)
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Statut Règlement</th>
                            @endif
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Compagnie</th>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Type</th>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-[11px]">
                        @forelse($contrats as $contrat)
                        <tr wire:key="contrat-row-{{ $contrat->id }}"
                            wire:click="selectContrat({{ $contrat->id }})" 
                            class="hover:bg-slate-50 cursor-pointer transition-colors {{ $selectedContratId == $contrat->id || in_array((string)$contrat->id, $selectedContrats) ? 'bg-teal-50/60 border-l-2 border-l-teal-600 text-slate-900' : 'text-slate-700' }}">
                            <td class="px-2.5 py-2 text-center whitespace-nowrap" wire:click.stop>
                                <input type="checkbox" value="{{ $contrat->id }}" wire:model.live="selectedContrats" class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 cursor-pointer">
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-400">{{ $contrat->id }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-teal-600 font-bold">{{ $contrat->numero_contrat }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-500">CL-{{ str_pad($contrat->client_id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-900 font-sans font-semibold">{{ $contrat->souscripteur }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->police }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->avenant ?? '-' }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->attestation ?? '-' }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-900 font-bold font-mono">{{ $contrat->matricule ?? ($contrat->vehicule->matricule ?? '-') }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap font-sans text-slate-700 font-semibold">
                                @php
                                    $marqueStr = $contrat->vehicule ? trim(($contrat->vehicule->marque ?? '') . ' ' . ($contrat->vehicule->modele ?? '')) : trim(($contrat->marque ?? '') . ' ' . ($contrat->modele ?? ''));
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-800 border border-slate-200 font-bold">
                                    {{ !empty(trim($marqueStr)) ? $marqueStr : '-' }}
                                </span>
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-600">{{ $contrat->date_effet->format('d/m/Y') }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-600">
                                @php
                                    $isExpiringSoon = $contrat->statut === 'actif' && $contrat->date_echeance->between(now()->startOfDay(), now()->addDays(7)->endOfDay());
                                @endphp
                                @if($isExpiringSoon)
                                    <span class="text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200" title="Expire bientôt">
                                        {{ $contrat->date_echeance->format('d/m/Y') }} ⚠️
                                    </span>
                                @else
                                    {{ $contrat->date_echeance->format('d/m/Y') }}
                                @endif
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-right font-mono">
                                <span class="inline-block text-emerald-700 font-black text-[12px] bg-emerald-50/90 px-2 py-0.5 rounded-md border border-emerald-200 shadow-2xs">
                                    {{ number_format($contrat->prime_totale, 2) }} DH
                                </span>
                            </td>
                            @if(!$isRenouvellements)
                            <td class="px-2.5 py-2 whitespace-nowrap text-center font-sans">
                                @if($contrat->statut_reglement === 'solde')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Soldé</span>
                                @elseif($contrat->statut_reglement === 'partiel')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Partiel</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">Non payé</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-2.5 py-2 whitespace-nowrap font-sans text-slate-800">{{ $contrat->compagnie->nom }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold {{ $contrat->type_affaire === 'AN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-blue-50 text-blue-700 border border-blue-200/60' }}">
                                    {{ $contrat->type_affaire }}
                                </span>
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-center font-sans">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($isRenouvellements)
                                         @if($contrat->type_affaire === 'RN' || ($contrat->historiqueRenouvellements && $contrat->historiqueRenouvellements->count() > 0))
                                             <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg text-[11px] font-extrabold shadow-2xs">
                                                 ✓ Renouvelé
                                             </span>
                                         @else
                                        <!-- Confirmer Renewal Button -->
                                        <button wire:click.stop="renouvelerContrat({{ $contrat->id }})" 
                                                title="Confirmer & Générer le renouvellement" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition-all">
                                            <svg width="14" height="14" style="width:14px;height:14px;" class="w-3.5 h-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            <span>Confirmer</span>
                                        </button>

                                        <!-- Rejeter Renewal Button -->
                                        <button wire:click.stop="rejeterRenouvellement({{ $contrat->id }})" 
                                                title="Rejeter / Refuser le renouvellement" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-[11px] font-bold shadow-2xs transition-all">
                                            <svg width="14" height="14" style="width:14px;height:14px;" class="w-3.5 h-3.5 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Rejeter</span>
                                        </button>
                                        @endif
                                        <!-- WhatsApp Relance Button -->
                                        @php
                                            $rawPhone = $contrat->client->telephone ?? ($contrat->client->phone ?? '');
                                            $phoneNum = preg_replace('/[^0-9]/', '', $rawPhone);
                                            if (str_starts_with($phoneNum, '0')) {
                                                $phoneNum = '212' . substr($phoneNum, 1);
                                            }
                                            $wsMsg = rawurlencode("Bonjour " . $contrat->souscripteur . ", votre contrat d'assurance auto N° " . $contrat->numero_contrat . " (Véhicule: " . ($contrat->matricule ?? 'Auto') . ") arrive à échéance le " . $contrat->date_echeance->format('d/m/Y') . ". Prime à régler: " . number_format($contrat->prime_totale, 2) . " DH. Merci de nous contacter pour le renouvellement.");
                                            $wsUrl = !empty($phoneNum) ? "https://wa.me/{$phoneNum}?text={$wsMsg}" : "https://api.whatsapp.com/send?text={$wsMsg}";
                                        @endphp
                                        <a href="{{ $wsUrl }}" 
                                           target="_blank"
                                           title="Relancer le client via WhatsApp" 
                                           wire:click.stop
                                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[11px] font-bold shadow-sm transition-all">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                            <span>WhatsApp</span>
                                        </a>
                                    @else
                                        <!-- Teal + Règlement Button -->
                                        <button wire:click.stop="openReglementsModal({{ $contrat->id }})" class="inline-flex items-center px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition-colors mr-0.5">
                                            + Règlement
                                        </button>

                                        <!-- Quittance PDF Button -->
                                        <a href="{{ route('automobile.pdf', ['contratId' => $contrat->id, 'type' => 'quittance']) }}" 
                                           target="_blank"
                                           title="Télécharger Quittance PDF"
                                           wire:click.stop
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 rounded-lg text-[11px] font-bold shadow-2xs transition-all">
                                            <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            Quittance
                                        </a>
                                    @endif

                                    @if(empty($isRenouvellementMode) && empty($isRenouvellements))
                                    <!-- Modifier -->
                                    <a href="{{ route('automobile.edit', $contrat->id) }}" 
                                       title="Modifier"
                                       wire:click.stop
                                       class="p-1.5 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- History Dates Button -->
                                    <button wire:click.stop="openHistoryModal({{ $contrat->id }})" 
                                            title="Historique des Dates / Renouvellements" 
                                            class="p-1.5 rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>

                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="px-4 py-8 text-center text-slate-500 font-sans">
                                Aucun contrat trouvé dans le registre.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse($contrats as $contrat)
                    <div wire:key="contrat-card-{{ $contrat->id }}"
                         wire:click="selectContrat({{ $contrat->id }})" class="p-4 flex flex-col gap-2 hover:bg-slate-50 cursor-pointer {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-4 border-teal-600' : '' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-bold text-indigo-600 font-mono">#{{ $contrat->numero_contrat }}</span>
                                <span class="text-xs text-slate-500 block font-sans">Client: {{ $contrat->souscripteur }}</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold {{ $contrat->type_affaire === 'AN' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $contrat->type_affaire }}
                            </span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-600">
                            <div>Matricule: <span class="font-mono font-bold text-slate-900">{{ $contrat->matricule ?? ($contrat->vehicule->matricule ?? '-') }}</span></div>
                            <div class="font-mono font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">{{ number_format($contrat->prime_totale, 2) }} DH</div>
                        </div>
                        @php
                            $mStr = $contrat->vehicule ? trim(($contrat->vehicule->marque ?? '') . ' ' . ($contrat->vehicule->modele ?? '')) : trim(($contrat->marque ?? '') . ' ' . ($contrat->modele ?? ''));
                        @endphp
                        @if(!empty(trim($mStr)))
                            <div class="text-xs text-slate-500 font-sans">
                                Véhicule: <span class="font-semibold text-slate-700">{{ $mStr }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono">
                            <div>Effet: {{ $contrat->date_effet->format('d/m/Y') }}</div>
                            <div>
                                Expire: 
                                @php
                                    $isExpiringSoon = $contrat->statut === 'actif' && $contrat->date_echeance->between(now()->startOfDay(), now()->addDays(7)->endOfDay());
                                @endphp
                                @if($isExpiringSoon)
                                    <span class="text-rose-600 font-bold bg-rose-50 px-1 py-0.5 rounded border border-rose-200">
                                        {{ $contrat->date_echeance->format('d/m/Y') }} ⚠️
                                    </span>
                                @else
                                    {{ $contrat->date_echeance->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>

                        <!-- Mobile Action Buttons Row -->
                        <div class="flex flex-wrap items-center justify-between pt-2 mt-1 border-t border-slate-100 gap-2">
                            <div class="flex items-center gap-1.5">
                                @if($isRenouvellements)
                                    <button wire:click.stop="renouvelerContrat({{ $contrat->id }})" class="inline-flex items-center px-2 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold shadow-sm">
                                        ✓ Confirmer
                                    </button>
                                    <button wire:click.stop="rejeterRenouvellement({{ $contrat->id }})" class="inline-flex items-center px-2 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold">
                                        ✗ Rejeter
                                    </button>
                                    @php
                                        $rawPhone = $contrat->client->telephone ?? ($contrat->client->phone ?? '');
                                        $phoneNum = preg_replace('/[^0-9]/', '', $rawPhone);
                                        if (str_starts_with($phoneNum, '0')) {
                                            $phoneNum = '212' . substr($phoneNum, 1);
                                        }
                                        $wsMsg = rawurlencode("Bonjour " . $contrat->souscripteur . ", votre contrat d'assurance auto N° " . $contrat->numero_contrat . " (Véhicule: " . ($contrat->matricule ?? 'Auto') . ") arrive à échéance le " . $contrat->date_echeance->format('d/m/Y') . ". Prime à régler: " . number_format($contrat->prime_totale, 2) . " DH. Merci de nous contacter pour le renouvellement.");
                                        $wsUrl = !empty($phoneNum) ? "https://wa.me/{$phoneNum}?text={$wsMsg}" : "https://api.whatsapp.com/send?text={$wsMsg}";
                                    @endphp
                                    <a href="{{ $wsUrl }}" target="_blank" wire:click.stop title="WhatsApp" class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-500 text-white rounded-lg text-xs font-bold">
                                        <span>WhatsApp</span>
                                    </a>
                                @else
                                    <button wire:click.stop="openReglementsModal({{ $contrat->id }})" class="inline-flex items-center px-2.5 py-1 bg-teal-600 text-white rounded-lg text-xs font-bold shadow-sm">
                                        + Règlement
                                    </button>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if(!$isRenouvellements)
                                    <a href="{{ route('automobile.pdf', ['contratId' => $contrat->id, 'type' => 'quittance']) }}" target="_blank" wire:click.stop title="Quittance PDF" class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Quittance
                                    </a>
                                @endif
                                <a href="{{ route('automobile.edit', $contrat->id) }}" wire:click.stop title="Modifier" class="p-1.5 rounded-lg text-blue-700 bg-blue-50 border border-blue-200">
                                    <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">
                        Aucun contrat trouvé dans le registre.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 py-2 bg-slate-50 border-t border-slate-200/80">
                {{ $contrats->links() }}
            </div>
        </div>
    </div>

    <!-- Règlements Modal -->
    @if($isReglementsModalOpen && $selectedContrat)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeReglementsModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-100">
                    <!-- Header -->
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200/60 flex justify-between items-center">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-teal-600">Suivi Financier</span>
                            <h3 class="text-lg font-bold text-slate-800" id="modal-title">
                                Règlements & Paiements - Contrat #{{ $selectedContrat->numero_contrat }}
                            </h3>
                        </div>
                        <button wire:click="closeReglementsModal" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Situation Financière -->
                        @php
                            $montantSaisi = 0;
                            if (!empty($reglementLines)) {
                                foreach ($reglementLines as $line) {
                                    $montantSaisi += floatval($line['montant'] ?? 0);
                                }
                            } else {
                                $montantSaisi = floatval($reglementMontant ?? 0);
                            }
                            $soldeCalcule = max(0, $selectedContrat->solde - $montantSaisi);
                        @endphp
                        <div class="grid grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                            <div>
                                <span class="text-xs font-medium text-slate-400 uppercase">Prime Totale</span>
                                <span class="block text-md font-bold text-slate-700 font-mono mt-0.5">{{ number_format($selectedContrat->prime_totale, 2) }} DH</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-400 uppercase">Déjà Payé</span>
                                <span class="block text-md font-bold text-emerald-600 font-mono mt-0.5">{{ number_format($selectedContrat->reglements->sum('montant'), 2) }} DH</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-400 uppercase">Solde Restant</span>
                                <span class="block text-md font-bold {{ $selectedContrat->solde <= 0 ? 'text-emerald-600' : 'text-amber-600' }} font-mono mt-0.5">
                                    {{ number_format($selectedContrat->solde, 2) }} DH
                                </span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-400 uppercase">Statut</span>
                                <div class="mt-1">
                                    @if($selectedContrat->solde <= 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Soldé</span>
                                    @elseif($selectedContrat->reglements->sum('montant') > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">Partiel</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">Non payé</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($montantSaisi > 0)
                            <div class="bg-teal-50 border border-teal-200/80 text-teal-900 px-4 py-2.5 rounded-xl text-xs flex items-center justify-between font-medium">
                                <span class="flex items-center gap-1.5">
                                    <span>💡</span>
                                    <span>Nouveau solde après validation de ce règlement :</span>
                                </span>
                                <span class="font-mono font-bold text-teal-800 text-sm">
                                    {{ number_format($soldeCalcule, 2) }} DH 
                                    <span class="text-xs text-teal-600">(-{{ number_format($montantSaisi, 2) }} DH)</span>
                                </span>
                            </div>
                        @endif

                        <!-- Liste des règlements enregistrés -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Historique des règlements
                            </h4>
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                                <table class="min-w-full divide-y divide-slate-100 text-sm text-left">
                                    <thead class="bg-slate-50 text-slate-500 font-semibold text-xs uppercase">
                                        <tr>
                                            <th class="px-4 py-2.5">Date</th>
                                            <th class="px-4 py-2.5">Montant</th>
                                            <th class="px-4 py-2.5">Mode</th>
                                            <th class="px-4 py-2.5">Référence</th>
                                            <th class="px-4 py-2.5 text-right">Action</th>
                                        </tr>
                                    </thead>
                                              @forelse($selectedContrat->reglements as $reg)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-3">{{ $reg->date_reglement->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3 font-semibold text-emerald-600">{{ number_format($reg->montant, 2) }} DH</td>
                                                <td class="px-4 py-3 uppercase font-sans font-semibold text-slate-500">
                                                    {{ $reg->mode_reglement }}
                                                    @if($reg->mode_reglement === 'cheque' && $reg->date_echeance_cheque)
                                                        <span class="block text-[10px] text-teal-600 font-sans font-medium">Versement: {{ $reg->date_echeance_cheque->format('d/m/Y') }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-slate-500">{{ $reg->reference_paiement ?? '-' }}</td>
                                                <td class="px-4 py-3 text-right font-sans">
                                                    @php
                                                        $createdTime = $reg->created_at ? \Carbon\Carbon::parse($reg->created_at) : ($reg->date_reglement ? \Carbon\Carbon::parse($reg->date_reglement) : null);
                                                        $isPastDay = $createdTime ? !$createdTime->isToday() : false;
                                                    @endphp
                                                    @if(!$isPastDay)
                                                        <button onclick="confirm('Supprimer ce règlement ?') || event.stopImmediatePropagation()" wire:click="deleteReglement({{ $reg->id }})" class="text-rose-500 hover:text-rose-700 font-semibold transition-colors">
                                                            Supprimer
                                                        </button>
                                                    @else
                                                        <span class="text-slate-400 text-xs font-sans" title="Verrouillé: règlement d'une date antérieure (Journée clôturée)">
                                                            🔒 Non modifiable (Date passée)
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 font-sans">Aucun règlement enregistré pour le moment.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Ajouter un règlement -->
                        @if($selectedContrat->solde > 0)
                            <div class="border-t border-slate-200 pt-5 space-y-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <h4 class="text-sm font-semibold text-slate-800 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Enregistrer un nouveau règlement
                                    </h4>
                                    
                                    <button type="button" 
                                            wire:click="addReglementLine" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-semibold rounded-lg border border-teal-200/80 transition-colors shadow-sm cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        + Ajouter un autre mode / règlement
                                    </button>
                                </div>

                                <form wire:submit.prevent="addReglement" class="space-y-3">
                                    @foreach($reglementLines as $index => $line)
                                        <div wire:key="reglement-line-{{ $index }}" class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 relative transition-all group space-y-3">
                                            @if(count($reglementLines) > 1)
                                                <div class="flex justify-between items-center pb-1.5 border-b border-slate-200/50">
                                                    <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Règlement #{{ $index + 1 }}</span>
                                                    <button type="button" 
                                                            wire:click="removeReglementLine({{ $index }})" 
                                                            class="text-rose-500 hover:text-rose-700 text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        Supprimer
                                                    </button>
                                                </div>
                                            @endif

                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                                <div>
                                                    <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Montant (DH)</label>
                                                    <input type="number" 
                                                           step="0.01" 
                                                           wire:model.live="reglementLines.{{ $index }}.montant" 
                                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 font-mono font-semibold">
                                                    @error('reglementLines.'.$index.'.montant') 
                                                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                                                     @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Date</label>
                                                    <input type="date" 
                                                           min="{{ date('Y-m-d') }}"
                                                           wire:model="reglementLines.{{ $index }}.date" 
                                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 font-mono">
                                                    @error('reglementLines.'.$index.'.date') 
                                                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                                                     @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Mode de règlement</label>
                                                    <select wire:model.live="reglementLines.{{ $index }}.mode" 
                                                            class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                                        <option value="especes">Espèces</option>
                                                        <option value="cheque">Chèque</option>
                                                        <option value="virement">Virement</option>
                                                        <option value="carte">Carte bancaire</option>
                                                    </select>
                                                    @error('reglementLines.'.$index.'.mode') 
                                                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                                                     @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Référence / Numéro</label>
                                                    <input type="text" 
                                                           wire:model="reglementLines.{{ $index }}.reference" 
                                                           placeholder="ex: N° de chèque, transaction" 
                                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                                    @error('reglementLines.'.$index.'.reference') 
                                                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                                                     @enderror
                                                </div>
                                            </div>

                                            @if(($line['mode'] ?? '') === 'cheque')
                                                <div class="bg-teal-50/60 p-3 rounded-lg border border-teal-200/70 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="text-base">📅</span>
                                                        <div>
                                                            <span class="block text-xs font-bold text-teal-900">Date d'échéance / versement du chèque <span class="text-rose-500">*</span></span>
                                                            <span class="text-[10px] text-teal-700">Sélectionnez la date prévue d'encaissement bancaire</span>
                                                        </div>
                                                    </div>
                                                    <div class="w-full sm:w-auto">
                                                        <input type="date" 
                                                               min="{{ date('Y-m-d') }}"
                                                               wire:model.live="reglementLines.{{ $index }}.date_echeance_cheque" 
                                                               class="w-full sm:w-48 bg-white border border-teal-300 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 font-mono">
                                                        @error('reglementLines.'.$index.'.date_echeance_cheque') 
                                                            <span class="text-xs text-rose-600 mt-0.5 block font-sans">{{ $message }}</span> 
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                                        <div class="text-xs text-slate-600 font-medium">
                                            Total à enregistrer : <span class="font-mono font-bold text-teal-700 text-sm">{{ number_format($montantSaisi, 2) }} DH</span>
                                        </div>
                                        <button type="submit" 
                                                class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 border border-transparent rounded-lg font-semibold text-white text-sm transition-colors shadow cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ count($reglementLines) > 1 ? 'Enregistrer les règlements' : 'Enregistrer le règlement' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="bg-emerald-50 text-emerald-800 text-xs font-semibold p-4 rounded-xl border border-emerald-200 text-center flex items-center justify-center gap-1.5">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ce contrat est entièrement soldé. Aucun paiement supplémentaire n'est requis.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Floating Bulk Actions Toolbar -->
    @if(count($selectedContrats) > 0)
    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 bg-slate-900 text-white rounded-2xl px-6 py-3.5 shadow-2xl border border-slate-700 flex flex-wrap items-center gap-4 transition-all duration-300">
        <div class="flex items-center gap-2 pr-3 border-r border-slate-700">
            <span class="flex h-3 w-3 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
            </span>
            <span class="font-bold text-xs font-mono">{{ count($selectedContrats) }} contrat(s) sélectionné(s)</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Statut Bulk Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <span>🔄 Changer Statut</span>
                    <svg width="14" height="14" style="width:14px;height:14px;" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute bottom-full mb-2 left-0 w-44 bg-white rounded-xl shadow-xl border border-slate-200 py-1 text-slate-800 text-xs font-semibold z-50">
                    <button wire:click="bulkUpdateStatut('actif')" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-emerald-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Actif
                    </button>
                    <button wire:click="bulkUpdateStatut('expire')" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-amber-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Expiré
                    </button>
                    <button wire:click="bulkUpdateStatut('resilie')" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-rose-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Résilié
                    </button>
                    <button wire:click="bulkUpdateStatut('annule')" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-slate-50 text-slate-600 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span> Annulé
                    </button>
                </div>
            </div>

            <!-- Email Relance Bulk -->
            <button wire:click="bulkRelancerEmail" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                <svg width="14" height="14" style="width:14px;height:14px;" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Relancer (Masse)</span>
            </button>

            <!-- Export CSV Bulk -->
            <button wire:click="bulkExportCsv" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                <svg width="14" height="14" style="width:14px;height:14px;" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Exporter CSV</span>
            </button>



            <!-- Clear Selection -->
            <button wire:click="clearSelection" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                Désélectionner tout
            </button>
        </div>
    </div>
    @endif

    <!-- History of Renewal Dates Modal -->
    @if($showHistoryModal && $historyContrat)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 space-y-6 border border-slate-100 relative">
            <button wire:click="closeHistoryModal" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 p-2 rounded-xl text-lg font-bold">
                ✕
            </button>

            <div class="flex items-center gap-3">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl border border-amber-200">
                    <svg width="24" height="24" class="w-6 h-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">Historique des Renouvellements & Dates</h3>
                    <p class="text-xs text-slate-500 font-sans">
                        Contrat N° <span class="font-bold text-slate-700">{{ $historyContrat->numero_contrat }}</span> — Client: <span class="font-bold text-slate-700">{{ $historyContrat->souscripteur }}</span> ({{ $historyContrat->matricule ?? 'Sans Matricule' }})
                    </p>
                </div>
            </div>

            <!-- Timeline of dates -->
            <div class="space-y-4 pt-2">
                <!-- Current Period Card -->
                <div class="p-4 bg-emerald-50/60 border border-emerald-200/80 rounded-xl space-y-1">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Période Actuelle en Cours</span>
                        <span class="px-2 py-0.5 text-[10px] font-extrabold bg-emerald-600 text-white rounded-md">ACTIF</span>
                    </div>
                    <div class="flex items-center justify-between text-sm font-mono text-slate-800 font-bold pt-1">
                        <div>Effet: <span class="text-emerald-700">{{ $historyContrat->date_effet ? $historyContrat->date_effet->format('d/m/Y') : '-' }}</span></div>
                        <div>➔</div>
                        <div>Échéance: <span class="text-emerald-700">{{ $historyContrat->date_echeance ? $historyContrat->date_echeance->format('d/m/Y') : '-' }}</span></div>
                    </div>
                    <div class="text-xs text-slate-600 font-sans pt-1">
                        Prime Totale: <span class="font-bold font-mono text-emerald-700">{{ number_format($historyContrat->prime_totale, 2) }} DH</span>
                    </div>
                </div>

                <!-- Past Renewals List -->
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 pt-2">Périodes Précédentes ({{\count($historyContrat->historiqueRenouvellements)}})</h4>

                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @forelse($historyContrat->historiqueRenouvellements as $h)
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1 text-xs">
                        <div class="flex justify-between items-center text-slate-500 font-sans">
                            <span>Renouvelé le <strong class="text-slate-700">{{ $h->created_at ? $h->created_at->format('d/m/Y H:i') : '-' }}</strong></span>
                            <span class="font-mono font-bold text-slate-700">{{ number_format($h->prime_totale, 2) }} DH</span>
                        </div>
                        <div class="flex items-center justify-between font-mono text-slate-800 font-semibold pt-0.5">
                            <div>Ancienne Période: {{ $h->anc_date_effet ? $h->anc_date_effet->format('d/m/Y') : '-' }} ➔ {{ $h->anc_date_echeance ? $h->anc_date_echeance->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="text-[11px] font-mono text-teal-700 font-bold">
                            Nouvelle Période: {{ $h->nouv_date_effet ? $h->nouv_date_effet->format('d/m/Y') : '-' }} ➔ {{ $h->nouv_date_echeance ? $h->nouv_date_echeance->format('d/m/Y') : '-' }}
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-slate-400 text-xs italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        Aucun historique de renouvellement antérieur enregistré pour ce contrat.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100">
                <button wire:click="closeHistoryModal" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition-all">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
