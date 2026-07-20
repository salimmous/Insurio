<div class="p-6 bg-slate-50 min-h-screen text-slate-800 flex flex-col gap-6">

    <!-- Top header & filters -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white border border-slate-200/80 rounded-2xl px-6 py-4 shadow-sm gap-4">
        <div>
            <span class="text-xs font-semibold uppercase tracking-wider text-teal-600">Production Auto</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Registre de Production Automobile</h1>
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
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">
        
        <!-- Main content area (Left - 4/5 cols) -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            
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
                                class="hover:bg-slate-55 cursor-pointer transition-colors {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-2 border-l-teal-600 text-slate-900' : 'text-slate-700' }}">
                                <td class="px-3 py-2.5 text-slate-400">{{ $contrat->id }}</td>
                                <td class="px-3 py-2.5 text-teal-600 font-bold">{{ $contrat->numero_contrat }}</td>
                                <td class="px-3 py-2.5 text-slate-500">CL-{{ str_pad($contrat->client_id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-3 py-2.5 text-slate-900 font-sans font-semibold">{{ $contrat->souscripteur }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->police }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->avenant ?? '-' }}</td>
                                <td class="px-3 py-2.5">{{ $contrat->attestation ?? '-' }}</td>
                                <td class="px-3 py-2.5 text-slate-800">{{ $contrat->matricule }}</td>
                                <td class="px-3 py-2.5 text-slate-600">{{ $contrat->date_effet->format('d/m/Y') }}</td>
                                <td class="px-3 py-2.5 text-slate-600">{{ $contrat->date_echeance->format('d/m/Y') }}</td>
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
                        <div wire:click="selectContrat({{ $contrat->id }})" class="p-4 flex flex-col gap-2 hover:bg-slate-55 cursor-pointer {{ $selectedContratId == $contrat->id ? 'bg-teal-50/60 border-l-4 border-teal-600' : '' }}">
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
                                <div>Expire: {{ $contrat->date_echeance->format('d/m/Y') }}</div>
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
                <button onclick="alert('Action [Chg Vehicule] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Chg Vehicule
                </button>
                <a href="{{ route('automobile.edit', $selectedContratId) }}" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all border border-slate-200">
                    Saisie par lot
                </a>
                <button onclick="alert('Action [Rémission] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Rémission
                </button>
                <button onclick="alert('Action [Major. Sinistre] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Major. Sinistre
                </button>
                <button onclick="alert('Action [Duplicata] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Duplicata
                </button>
                <button onclick="alert('Action [CarteVerte] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    CarteVerte
                </button>
                <button onclick="alert('Action [Prorata] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Prorata
                </button>
                <button wire:click="resilierContrat" class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-amber-200/60">
                    Resiliation
                </button>
                <button wire:click="annulerContrat" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-rose-200/60">
                    Annulation
                </button>
                <button onclick="alert('Action [Frontière] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Frontière
                </button>
                <button onclick="alert('Action [Reglements] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Reglements
                </button>
                <button onclick="alert('Action [Chèque à verser] à définir')" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs transition-all border border-slate-200">
                    Chèque à verser
                </button>
                <a href="{{ route('automobile.edit', $selectedContratId) }}" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-3 rounded-xl text-xs text-center transition-all border border-slate-200">
                    Consulter
                </a>
            @else
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Modifier</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Renouvellement</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Chg Vehicule</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Saisie par lot</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Rémission</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Major. Sinistre</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Duplicata</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">CarteVerte</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Prorata</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Resiliation</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Annulation</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Frontière</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Reglements</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Chèque à verser</button>
                <button disabled class="w-full bg-slate-50 text-slate-300 font-semibold py-2 px-3 rounded-xl text-xs cursor-not-allowed border border-slate-100">Consulter</button>
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
</div>
