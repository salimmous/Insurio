<div class="p-6 bg-slate-50 min-h-screen text-slate-800 flex flex-col gap-6">

    <!-- Top header & filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white border border-slate-200/80 rounded-2xl px-6 py-4 shadow-sm gap-4">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-teal-600">Production</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Registre de Production Assurance</h1>
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
                <option value="">Statuts (Tous)</option>
                <option value="actif">Actif</option>
                <option value="expiring_7_days">Échéance < 7 jours</option>
                <option value="expire">Expiré</option>
                <option value="resilie">Résilié</option>
                <option value="annule">Annulé</option>
            </select>
        </div>
    </div>

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

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-6 gap-6 items-start">
        
        <!-- Main content area (Left - 5/6 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            
            <!-- Table / Grid -->
            <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200/80">
                            <tr>
                                <th class="px-3 py-3">ID</th>
                                <th class="px-3 py-3">Réf</th>
                                <th class="px-3 py-3">Code client</th>
                                <th class="px-3 py-3">Nom du client</th>
                                <th class="px-3 py-3">Police</th>
                                <th class="px-3 py-3">Avenant</th>
                                <th class="px-3 py-3">Attest</th>
                                <th class="px-3 py-3">Matricule</th>
                                <th class="px-3 py-3">Date d'effet</th>
                                <th class="px-3 py-3">Expiration</th>
                                <th class="px-3 py-3 text-right">Prime Total</th>
                                <th class="px-3 py-3">Compagnie</th>
                                <th class="px-3 py-3 text-center">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium font-mono text-[11px]">
                            @forelse($contrats as $contrat)
                            <tr wire:click="selectContrat({{ $contrat->id }})" 
                                class="hover:bg-slate-50 cursor-pointer transition-colors {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-2 border-l-teal-600 text-slate-900' : 'text-slate-700' }}">
                                <td class="px-3 py-2.5 text-slate-400">{{ $contrat->id }}</td>
                                <td class="px-3 py-2.5 text-teal-600 font-bold">{{ $contrat->numero_contrat }}</td>
                                <td class="px-3 py-2.5 text-slate-500">CL-{{ str_pad($contrat->client_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-3 py-2.5 text-slate-900 font-sans font-semibold">{{ $contrat->souscripteur }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->police }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->avenant ?? '-' }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->attestation ?? '-' }}</td>
                                <td class="px-3 py-2.5 text-slate-800">{{ $contrat->matricule }}</td>
                                <td class="px-3 py-2.5 text-slate-600">{{ $contrat->date_effet->format('d/m/Y') }}</td>
                                <td class="px-3 py-2.5 text-slate-600">
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
                                <td class="px-3 py-2.5 text-right text-slate-900 font-bold font-mono">{{ number_format($contrat->prime_totale, 2) }} DH</td>
                                <td class="px-3 py-2.5 font-sans text-slate-800">{{ $contrat->compagnie->nom }}</td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold {{ $contrat->type_affaire === 'AN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-blue-50 text-blue-700 border border-blue-200/60' }}">
                                        {{ $contrat->type_affaire }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="px-4 py-8 text-center text-slate-500 font-sans">
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
                        <div wire:click="selectContrat({{ $contrat->id }})" class="p-4 flex flex-col gap-2 hover:bg-slate-50 cursor-pointer {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-4 border-teal-600' : '' }}">
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

            <!-- Zone Résumé (Bas de la fiche, sous la grille) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block border-b border-slate-100 pb-2">Zone Résumé (Fiche active)</span>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block">Usage</span>
                        <span class="font-semibold text-slate-800 block mt-0.5">{{ $selectedContrat ? $selectedContrat->usage : '-' }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 block">Branche (code + libellé)</span>
                        <span class="font-semibold text-slate-800 block mt-0.5">
                            @if($selectedContrat && $selectedContrat->branche_code)
                                {{ $selectedContrat->branche_code }} - {{ $selectedContrat->branche_libelle }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Prime nette</span>
                        <span class="font-mono font-bold text-slate-700 block mt-0.5">{{ $selectedContrat ? number_format($selectedContrat->prime_nette, 2) : '0.00' }} DH</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Prime totale</span>
                        <span class="font-mono font-bold text-teal-600 block mt-0.5">{{ $selectedContrat ? number_format($selectedContrat->prime_totale, 2) : '0.00' }} DH</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Reglement Client</span>
                        <span class="font-mono font-bold text-emerald-600 block mt-0.5">{{ $selectedContrat ? number_format($selectedContrat->reglements->sum('montant'), 2) : '0.00' }} DH</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 text-xs border-t border-slate-100 pt-3">
                    <div>
                        <span class="text-slate-400 block">Chèques à verser</span>
                        <span class="font-mono font-bold text-amber-600 block mt-0.5">{{ $selectedContrat ? number_format($selectedContrat->reglements->where('mode', 'cheque')->sum('montant'), 2) : '0.00' }} DH</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Solde</span>
                        <span class="font-mono font-bold text-rose-600 block mt-0.5">{{ $selectedContrat ? number_format($selectedContrat->solde, 2) : '0.00' }} DH</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Date d'effet</span>
                        <span class="font-mono text-slate-600 block mt-0.5">{{ $selectedContrat ? $selectedContrat->date_effet->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Date échéance</span>
                        <span class="font-mono text-slate-600 block mt-0.5">{{ $selectedContrat ? $selectedContrat->date_echeance->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Date resiliation</span>
                        <span class="font-mono text-slate-600 block mt-0.5">{{ ($selectedContrat && $selectedContrat->date_resiliation) ? $selectedContrat->date_resiliation->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Apporteur</span>
                        <span class="font-semibold text-slate-800 block mt-0.5">{{ $selectedContrat && $selectedContrat->apporteur ? $selectedContrat->apporteur->nom : '-' }}</span>
                    </div>
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

        <!-- Sidebar Actions (Right - 1/5 cols) -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm flex flex-col gap-2">
            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-2 border-b border-slate-100 pb-2 block">Actions</span>
            
            <a href="{{ route('automobile.create') }}" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all shadow-sm">
                Nouveau
            </a>

            @if($selectedContratId)
                <a href="{{ route('automobile.edit', $selectedContratId) }}" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all border border-slate-200">
                    Modifier
                </a>
                <button wire:click="renouvelerContrat" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all border border-slate-200">
                    Renouvellement
                </button>
                <button wire:click="openReglementsModal" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Reglements
                </button>
                <button wire:click="resilierContrat" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-amber-200/60">
                    Resiliation
                </button>
                <button wire:click="annulerContrat" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-rose-200/60">
                    Annulation
                </button>
            @else
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Modifier</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Renouvellement</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Reglements</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Resiliation</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Annulation</button>
            @endif

            @if($selectedContratId && $selectedContrat)
                <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mt-4 mb-2 border-b border-slate-100 pb-2 block">📢 Relances Échéance</span>
                
                <!-- WhatsApp reminder -->
                @php
                    $clientPhone = $selectedContrat->client->telephone ?? '';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $clientPhone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '212' . substr($cleanPhone, 1);
                    }
                    $waText = "Bonjour " . $selectedContrat->client->nom . " " . $selectedContrat->client->prenom . ", votre contrat d'assurance automobile Insurio N° " . $selectedContrat->numero_contrat . " chez la compagnie " . $selectedContrat->compagnie->nom . " arrive à échéance le " . $selectedContrat->date_echeance->format('d/m/Y') . ". Pour continuer à rouler en toute sécurité, veuillez nous contacter pour le renouveler. Cordialement.";
                    $waLink = "https://wa.me/" . $cleanPhone . "?text=" . urlencode($waText);
                @endphp
                <a href="{{ $waLink }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all shadow-sm flex items-center justify-center gap-1">
                    💬 Relancer WhatsApp
                </a>

                <!-- Email reminder -->
                <button wire:click="relancerParEmail" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all shadow-sm flex items-center justify-center gap-1">
                    ✉️ Relancer Email
                </button>
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
                                <span class="block text-md font-bold text-amber-600 font-mono mt-0.5">{{ number_format($selectedContrat->solde, 2) }} DH</span>
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
                            <div class="border-t border-slate-150 pt-5">
                                <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Enregistrer un nouveau règlement
                                </h4>
                                <form wire:submit.prevent="addReglement" class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/50">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Montant (DH)</label>
                                        <input type="number" step="0.01" wire:model="reglementMontant" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 font-mono font-semibold">
                                        @error('reglementMontant') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Date</label>
                                        <input type="date" wire:model="reglementDate" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 font-mono">
                                        @error('reglementDate') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Mode de règlement</label>
                                        <select wire:model="reglementMode" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                            <option value="especes">Espèces</option>
                                            <option value="cheque">Chèque</option>
                                            <option value="virement">Virement</option>
                                            <option value="carte">Carte bancaire</option>
                                        </select>
                                        @error('reglementMode') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Référence / Numéro</label>
                                        <input type="text" wire:model="reglementReference" placeholder="ex: N° de chèque, transaction" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        @error('reglementReference') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-span-2 flex justify-end mt-2">
                                        <button type="submit" class="inline-flex justify-center px-4 py-2 bg-teal-600 hover:bg-teal-700 border border-transparent rounded-lg font-semibold text-white text-sm transition-colors shadow">
                                            Enregistrer le règlement
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
