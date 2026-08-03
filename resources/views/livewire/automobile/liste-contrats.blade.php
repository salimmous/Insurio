<div class="p-4 md:p-5 bg-slate-50 min-h-screen text-slate-800 flex flex-col gap-4">

    <!-- Top header & filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white border border-slate-200/80 rounded-xl px-5 py-3 shadow-sm gap-3">
        <div>
            @if(request()->routeIs('admin.renouvellements'))
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Échéances & Relances</span>
                <h1 class="text-xl font-bold text-slate-900 mt-0.5">Gestion des Renouvellements Assurance</h1>
            @else
                <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600">Production</span>
                <h1 class="text-xl font-bold text-slate-900 mt-0.5">Registre de Production Assurance</h1>
            @endif
        </div>
        
        <!-- Search & Filter Controls -->
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <input wire:model.live="search" type="text" placeholder="Rechercher..." 
                   class="bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2 text-sm text-slate-800 placeholder-slate-400 outline-none transition-all w-full md:w-64">

            <select wire:model.live="filterCompagnie" class="bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3 py-2 text-sm text-slate-800 outline-none transition-all">
                <option value="">Compagnies (Toutes)</option>
                @foreach($compagnies as $compagnie)
                <option value="{{ $compagnie->id }}">{{ $compagnie->nom }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatut" class="bg-slate-50 border border-slate-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-3 py-2 text-sm text-slate-800 outline-none transition-all">
                @if(request()->routeIs('admin.renouvellements'))
                    <option value="">Tous les statuts (Priorité Échéance)</option>
                    <option value="expiring_1_day">🚨 Échéance 1 jour ({{ $countExpiring1Day }})</option>
                    <option value="expiring_7_days">⚠️ Échéance 7 jours ({{ $countExpiring7Days }})</option>
                    <option value="expiring_10_days">🔔 Échéance 10 jours ({{ $countExpiring10Days }})</option>
                @else
                    <option value="">Tous les règlements</option>
                    <option value="reglement_solde">Soldé / Totalement Payé ({{ $countReglementSolde }})</option>
                    <option value="reglement_partiel">Partiel / Reste Solde ({{ $countReglementPartiel }})</option>
                    <option value="reglement_non_paye">Non Payé ({{ $countReglementNonPaye }})</option>
                    <option value="reglement_impaye">Non Soldé / Reste à Payer ({{ $countReglementImpaye }})</option>
                @endif
                <option value="actif">Statut: Actifs</option>
                <option value="expire">Statut: Expirés</option>
                <option value="resilie">Statut: Résiliés</option>
                <option value="annule">Statut: Annulés</option>
            </select>

            <a href="{{ route('automobile.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-3.5 py-2 rounded-xl text-xs transition-all shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Nouveau</span>
            </a>
        </div>
    </div>

    @if(!request()->routeIs('admin.renouvellements'))
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

    @if(request()->routeIs('admin.renouvellements'))
    <!-- Renewal Navigation Tabs ("kola 7aja bohdha") -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-pulse"></span>
                <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Catégories de Renouvellement d'Assurance</h3>
            </div>
            <span class="text-xs font-mono font-bold text-slate-500">Total à renouveler (<= 10j): {{ $countExpiringAll }}</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Tab 1: 1 Jour Urgent -->
            <button wire:click="$set('filterStatut', 'expiring_1_day')" 
                    class="p-3 rounded-xl border text-left transition-all relative overflow-hidden group {{ $filterStatut === 'expiring_1_day' ? 'bg-rose-50 border-rose-500 ring-2 ring-rose-500/20 text-rose-900 shadow-md' : 'bg-slate-50 border-slate-200/80 hover:bg-slate-100 text-slate-700' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 flex items-center gap-1">
                        🚨 1 Jour (Urgent)
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black font-mono {{ $filterStatut === 'expiring_1_day' ? 'bg-rose-600 text-white' : 'bg-rose-100 text-rose-700' }}">
                        {{ $countExpiring1Day }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-500 font-medium">Expiration <= 24 heures</p>
            </button>

            <!-- Tab 2: 7 Jours -->
            <button wire:click="$set('filterStatut', 'expiring_7_days')" 
                    class="p-3 rounded-xl border text-left transition-all relative overflow-hidden group {{ $filterStatut === 'expiring_7_days' ? 'bg-amber-50 border-amber-500 ring-2 ring-amber-500/20 text-amber-900 shadow-md' : 'bg-slate-50 border-slate-200/80 hover:bg-slate-100 text-slate-700' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-600">
                        ⚠️ 2 à 7 Jours
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black font-mono {{ $filterStatut === 'expiring_7_days' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-700' }}">
                        {{ $countExpiring7Days }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-500 font-medium">Expirations cette semaine</p>
            </button>

            <!-- Tab 3: 10 Jours -->
            <button wire:click="$set('filterStatut', 'expiring_10_days')" 
                    class="p-3 rounded-xl border text-left transition-all relative overflow-hidden group {{ $filterStatut === 'expiring_10_days' ? 'bg-sky-50 border-sky-500 ring-2 ring-sky-500/20 text-sky-900 shadow-md' : 'bg-slate-50 border-slate-200/80 hover:bg-slate-100 text-slate-700' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-600">
                        🔔 8 à 10 Jours
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black font-mono {{ $filterStatut === 'expiring_10_days' ? 'bg-sky-600 text-white' : 'bg-sky-100 text-sky-700' }}">
                        {{ $countExpiring10Days }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-500 font-medium">Préparation relances 10j</p>
            </button>

            <!-- Tab 4: Tous les renouvellements -->
            <button wire:click="$set('filterStatut', 'expiring_all')" 
                    class="p-3 rounded-xl border text-left transition-all relative overflow-hidden group {{ $filterStatut === 'expiring_all' ? 'bg-teal-50 border-teal-500 ring-2 ring-teal-500/20 text-teal-900 shadow-md' : 'bg-slate-50 border-slate-200/80 hover:bg-slate-100 text-slate-700' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-700">
                        📋 Tous <= 10 Jours
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-black font-mono {{ $filterStatut === 'expiring_all' ? 'bg-teal-600 text-white' : 'bg-teal-100 text-teal-800' }}">
                        {{ $countExpiringAll }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-500 font-medium">Toutes les échéances 10j</p>
            </button>

            <!-- Tab 5: Tout le Registre -->
            <button wire:click="$set('filterStatut', '')" 
                    class="p-3 rounded-xl border text-left transition-all border-slate-200/80 hover:bg-slate-100 flex flex-col justify-between {{ empty($filterStatut) ? 'bg-slate-800 text-white border-slate-800 shadow-md' : 'bg-slate-50 text-slate-700' }}">
                <span class="text-xs font-bold uppercase tracking-wider block">Tout le Registre</span>
                <p class="text-[10px] opacity-70 font-medium mt-1">Actifs, expirés, etc.</p>
            </button>
        </div>
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
                            <th class="px-2.5 py-2.5 whitespace-nowrap">ID</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Réf</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Code client</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Nom du client</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Police</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Avenant</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Attest</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Matricule</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Date d'effet</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Expiration</th>
                            <th class="px-2.5 py-2.5 text-right whitespace-nowrap">Prime Total</th>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Statut Règlement</th>
                            <th class="px-2.5 py-2.5 whitespace-nowrap">Compagnie</th>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Type</th>
                            <th class="px-2.5 py-2.5 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-[11px]">
                        @forelse($contrats as $contrat)
                        <tr wire:key="contrat-row-{{ $contrat->id }}"
                            wire:click="selectContrat({{ $contrat->id }})" 
                            class="hover:bg-slate-50 cursor-pointer transition-colors {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-2 border-l-teal-600 text-slate-900' : 'text-slate-700' }}">
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-400">{{ $contrat->id }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-teal-600 font-bold">{{ $contrat->numero_contrat }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-500">CL-{{ str_pad($contrat->client_id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-900 font-sans font-semibold">{{ $contrat->souscripteur }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->police }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->avenant ?? '-' }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap">{{ $contrat->attestation ?? '-' }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-slate-800">{{ $contrat->matricule }}</td>
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
                            <td class="px-2.5 py-2 whitespace-nowrap text-right text-slate-900 font-bold font-mono">{{ number_format($contrat->prime_totale, 2) }} DH</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-center font-sans">
                                @if($contrat->statut_reglement === 'solde')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Soldé</span>
                                @elseif($contrat->statut_reglement === 'partiel')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">Partiel</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">Non payé</span>
                                @endif
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap font-sans text-slate-800">{{ $contrat->compagnie->nom }}</td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold {{ $contrat->type_affaire === 'AN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-blue-50 text-blue-700 border border-blue-200/60' }}">
                                    {{ $contrat->type_affaire }}
                                </span>
                            </td>
                            <td class="px-2.5 py-2 whitespace-nowrap text-center font-sans">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Teal + Règlement Button -->
                                    <button wire:click.stop="openReglementsModal({{ $contrat->id }})" class="inline-flex items-center px-2.5 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-[11px] font-bold shadow-sm transition-colors mr-0.5">
                                        + Règlement
                                    </button>

                                    <!-- 1. Modifier -->
                                    <a href="{{ route('automobile.edit', $contrat->id) }}" 
                                       title="Modifier"
                                       wire:click.stop
                                       class="p-1.5 rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <!-- 2. Renouvellement -->
                                    <button wire:click.stop="renouvelerContrat({{ $contrat->id }})" 
                                            wire:confirm="Voulez-vous vraiment renouveler ce contrat ?"
                                            title="Renouvellement"
                                            class="p-1.5 rounded-lg text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                    </button>

                                    <!-- 3. Résiliation -->
                                    <button wire:click.stop="resilierContrat({{ $contrat->id }})" 
                                            wire:confirm="Voulez-vous vraiment résilier le contrat #{{ $contrat->numero_contrat }} (Prorata temporis) ?"
                                            title="Résiliation"
                                            class="p-1.5 rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </button>

                                    <!-- 4. Annulation -->
                                    <button wire:click.stop="annulerContrat({{ $contrat->id }})" 
                                            wire:confirm="Voulez-vous vraiment annuler le contrat #{{ $contrat->numero_contrat }} rétroactivement ?"
                                            title="Annulation"
                                            class="p-1.5 rounded-lg text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all shadow-2xs">
                                        <svg width="14" height="14" style="width:14px;height:14px;min-width:14px;min-height:14px;" class="w-3.5 h-3.5 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
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
                            <div>Matricule: <span class="font-mono font-bold">{{ $contrat->matricule }}</span></div>
                            <div class="font-mono font-bold text-slate-900">{{ number_format($contrat->prime_totale, 2) }} DH</div>
                        </div>
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
                        <div class="flex items-center justify-between pt-2 mt-1 border-t border-slate-100">
                            <button wire:click.stop="openReglementsModal({{ $contrat->id }})" class="inline-flex items-center px-2.5 py-1 bg-teal-600 text-white rounded-lg text-xs font-bold shadow-sm">
                                + Règlement
                            </button>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('automobile.edit', $contrat->id) }}" wire:click.stop title="Modifier" class="p-1.5 rounded-lg text-blue-700 bg-blue-50 border border-blue-200">
                                    <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </a>
                                <button wire:click.stop="renouvelerContrat({{ $contrat->id }})" wire:confirm="Voulez-vous vraiment renouveler ce contrat ?" title="Renouvellement" class="p-1.5 rounded-lg text-teal-700 bg-teal-50 border border-teal-200">
                                    <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                                </button>
                                <button wire:click.stop="resilierContrat({{ $contrat->id }})" wire:confirm="Résiliation ?" title="Résiliation" class="p-1.5 rounded-lg text-amber-700 bg-amber-50 border border-amber-200">
                                    <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                </button>
                                <button wire:click.stop="annulerContrat({{ $contrat->id }})" wire:confirm="Annulation ?" title="Annulation" class="p-1.5 rounded-lg text-rose-700 bg-rose-50 border border-rose-200">
                                    <svg width="16" height="16" style="width:16px;height:16px;min-width:16px;min-height:16px;" class="w-4 h-4 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </button>
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

        <!-- Boutons de Documents (Bas de fiche) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-wrap gap-2 justify-center md:justify-start">
            @if($selectedContratId)
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'carte-verte']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Carte Verte</a>
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'attestation']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Attestation Assurance</a>
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'police']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Contrat / Police</a>
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'quittance']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Quittance</a>
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'recu']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Reçu</a>
                <a href="{{ route('automobile.pdf', ['contratId' => $selectedContratId, 'type' => 'rappel']) }}" target="_blank" class="bg-slate-105 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-medium px-4 py-2 rounded-xl text-xs transition-colors border border-slate-200/40">Rappel Échéance</a>
            @else
                <span class="text-xs text-slate-400 italic">Sélectionnez un contrat pour générer les documents PDF.</span>
            @endif
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
                                <span class="block text-md font-bold {{ $soldeCalcule == 0 ? 'text-emerald-600' : 'text-amber-600' }} font-mono mt-0.5">
                                    {{ number_format($soldeCalcule, 2) }} DH
                                    @if($montantSaisi > 0 && $montantSaisi < $selectedContrat->solde)
                                        <span class="text-[10px] text-teal-600 font-sans block">(-{{ number_format($montantSaisi, 2) }} DH)</span>
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-slate-400 uppercase">Statut</span>
                                <div class="mt-1">
                                    @if($soldeCalcule <= 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Soldé</span>
                                    @elseif(($selectedContrat->reglements->sum('montant') + $montantSaisi) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">Partiel</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">Non payé</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Liste des règlements enregistrés -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                Historique des règlements
                            </h4>
                            <div class="border border-slate-150 rounded-xl overflow-hidden bg-white">
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
                                    <tbody class="divide-y divide-slate-100 font-mono text-xs text-slate-700">
                                        @forelse($selectedContrat->reglements as $reg)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-4 py-3">{{ $reg->date_reglement->format('d/m/Y') }}</td>
                                                <td class="px-4 py-3 font-semibold text-emerald-600">{{ number_format($reg->montant, 2) }} DH</td>
                                                <td class="px-4 py-3 uppercase font-sans font-semibold text-slate-500">{{ $reg->mode_reglement }}</td>
                                                <td class="px-4 py-3 text-slate-500">{{ $reg->reference_paiement ?? '-' }}</td>
                                                <td class="px-4 py-3 text-right font-sans">
                                                    <button onclick="confirm('Supprimer ce règlement ?') || event.stopImmediatePropagation()" wire:click="deleteReglement({{ $reg->id }})" class="text-rose-500 hover:text-rose-700 font-semibold transition-colors">
                                                        Supprimer
                                                    </button>
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
                            <div class="border-t border-slate-150 pt-5 space-y-4">
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
                                        <div wire:key="reglement-line-{{ $index }}" class="bg-slate-50 p-4 rounded-xl border border-slate-200/70 relative transition-all group">
                                            @if(count($reglementLines) > 1)
                                                <div class="flex justify-between items-center mb-2 pb-1.5 border-b border-slate-200/50">
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
                                                           wire:model="reglementLines.{{ $index }}.date" 
                                                           class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 font-mono">
                                                    @error('reglementLines.'.$index.'.date') 
                                                        <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> 
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Mode de règlement</label>
                                                    <select wire:model="reglementLines.{{ $index }}.mode" 
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
</div>
