<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex justify-between items-center bg-white border border-slate-200/85 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-full flex items-center justify-center font-bold text-xl border border-teal-100">
                {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-800">{{ $client->first_name }} {{ $client->last_name }}</h1>
                    @if($client->client_type === 'company')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">Entreprise</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-100">Particulier</span>
                    @endif
                </div>
                <div class="text-sm text-slate-500 mt-1 flex items-center gap-4">
                    <span class="font-mono">ID: {{ $client->uuid ?? $client->id }}</span>
                    <span>•</span>
                    <span>CIN: {{ $client->cin ?? '-' }}</span>
                    <span>•</span>
                    <span>Ville: {{ $client->city ?? '-' }}</span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button wire:click="$set('showAiPanel', true)" class="bg-indigo-650 hover:bg-indigo-750 text-white font-semibold px-4 py-2 rounded-xl transition-all text-sm flex items-center gap-1.5 shadow-sm border border-indigo-700">
                <span>✨</span> Copilot AI
            </button>
            <a href="{{ route('admin.clients') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl transition-all border border-slate-200/40 text-sm">
                Retour à la liste
            </a>
            @if($client->phone)
                <a href="tel:{{ $client->phone }}" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-2 rounded-xl transition-all text-sm flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Appeler
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Tabs Content (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-200 bg-slate-50/50 px-4">
                    <button wire:click="$set('activeTab', 'contracts')" class="px-4 py-3.5 text-sm font-semibold border-b-2 transition-all {{ $activeTab === 'contracts' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Contrats ({{ $contracts->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'documents')" class="px-4 py-3.5 text-sm font-semibold border-b-2 transition-all {{ $activeTab === 'documents' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Documents ({{ $documents->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'payments')" class="px-4 py-3.5 text-sm font-semibold border-b-2 transition-all {{ $activeTab === 'payments' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Règlements ({{ $payments->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'timeline')" class="px-4 py-3.5 text-sm font-semibold border-b-2 transition-all {{ $activeTab === 'timeline' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        Timeline & Contacts
                    </button>
                </div>

                <!-- Tab Body -->
                <div class="p-6">
                    
                    <!-- Contracts Tab -->
                    @if($activeTab === 'contracts')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                <thead class="text-slate-500 font-semibold text-xs uppercase bg-slate-50/50">
                                    <tr>
                                        <th class="px-4 py-3">Réf / Compagnie</th>
                                        <th class="px-4 py-3">Produit</th>
                                        <th class="px-4 py-3">Période</th>
                                        <th class="px-4 py-3">Montant</th>
                                        <th class="px-4 py-3">Paiement</th>
                                        <th class="px-4 py-3">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150 text-slate-700">
                                    @forelse($contracts as $contract)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-4">
                                                <span class="font-bold text-slate-850 block font-mono">{{ $contract->contract_number }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $contract->compagnie->nom ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="font-medium text-slate-700 block">{{ $contract->product->nom ?? 'AUTO' }}</span>
                                                <span class="text-[10px] uppercase font-bold text-slate-400 block">{{ $contract->product->code ?? 'AUTO' }}</span>
                                            </td>
                                            <td class="px-4 py-4 font-mono text-xs text-slate-500">
                                                <div>Du {{ $contract->start_date->format('d/m/Y') }}</div>
                                                <div>Au {{ $contract->end_date->format('d/m/Y') }}</div>
                                            </td>
                                            <td class="px-4 py-4 font-mono font-semibold text-slate-850">
                                                {{ number_format($contract->premium_amount, 2) }} DH
                                            </td>
                                            <td class="px-4 py-4">
                                                @if($contract->payment_status === 'paid')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Soldé</span>
                                                @elseif($contract->payment_status === 'partial')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800">Partiel</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800">Non payé</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                @if($contract->status === 'active')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-teal-100 text-teal-800">Actif</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $contract->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-slate-400">Aucun contrat d'assurance enregistré pour ce client.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Documents Tab -->
                    @if($activeTab === 'documents')
                        <div class="space-y-6">
                            <!-- Upload Form -->
                            <form wire:submit.prevent="uploadDocument" class="bg-slate-50 p-4 rounded-xl border border-slate-200/50 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type de Document</label>
                                    <select wire:model="documentType" class="w-full bg-white border border-slate-350 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500">
                                        <option value="cin">CIN (Carte d'identité)</option>
                                        <option value="passport">Passeport</option>
                                        <option value="driving_license">Permis de conduire</option>
                                        <option value="vehicle_registration">Carte Grise</option>
                                        <option value="contract">Contrat signé</option>
                                        <option value="invoice">Quittance / Facture</option>
                                        <option value="other">Autre document</option>
                                    </select>
                                </div>
                                <div class="relative">
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Fichier (PDF, Image, max 10Mo)</label>
                                    <input type="file" wire:model="uploadedFile" class="w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
                                    @error('uploadedFile') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-all shadow-sm">
                                        Téléverser le fichier
                                    </button>
                                </div>
                            </form>

                            <!-- Documents List -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @forelse($documents as $doc)
                                    <div class="flex justify-between items-center p-3.5 bg-white border border-slate-200 rounded-xl shadow-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center font-bold text-xs uppercase">
                                                {{ $doc->type }}
                                            </div>
                                            <div class="max-w-[200px] overflow-hidden">
                                                <span class="block font-semibold text-slate-700 truncate text-sm" title="{{ $doc->file_name }}">{{ $doc->file_name }}</span>
                                                <span class="text-[10px] text-slate-400 block font-mono">Type: {{ strtoupper($doc->type) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" class="text-slate-500 hover:text-slate-850 p-1" title="Prévisualiser">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <button wire:click="downloadDocument({{ $doc->id }})" class="text-slate-500 hover:text-slate-800 transition-colors p-1" title="Télécharger">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </button>
                                            <button onclick="confirm('Supprimer ce document ?') || event.stopImmediatePropagation()" wire:click="deleteDocument({{ $doc->id }})" class="text-rose-500 hover:text-rose-700 transition-colors p-1" title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center py-8 text-slate-400">Aucun document téléversé pour ce client.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- Payments Tab -->
                    @if($activeTab === 'payments')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                <thead class="text-slate-500 font-semibold text-xs uppercase bg-slate-50/50">
                                    <tr>
                                        <th class="px-4 py-3">Date règlement</th>
                                        <th class="px-4 py-3">Contrat</th>
                                        <th class="px-4 py-3">Montant</th>
                                        <th class="px-4 py-3">Mode</th>
                                        <th class="px-4 py-3">Référence / Banque</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150 text-slate-700 font-mono text-xs">
                                    @forelse($payments as $pay)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-3.5">{{ $pay->date_reglement->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3.5 font-sans">
                                                <span class="font-bold text-slate-800 block">{{ $pay->contrat->contract_number }}</span>
                                                <span class="text-[10px] text-slate-400 block">{{ $pay->contrat->compagnie->nom ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3.5 font-semibold text-emerald-600">{{ number_format($pay->montant, 2) }} DH</td>
                                            <td class="px-4 py-3.5 uppercase font-sans font-semibold text-slate-500">{{ $pay->mode_reglement }}</td>
                                            <td class="px-4 py-3.5 font-sans text-slate-500">{{ $pay->reference_paiement ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-slate-400 font-sans">Aucun règlement enregistré pour le moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- Timeline & Communications Tab -->
                    @if($activeTab === 'timeline')
                        <div class="space-y-6">
                            <!-- New Activity Form -->
                            <form wire:submit.prevent="addCommunication" class="bg-slate-50 p-4 rounded-xl border border-slate-200/50 space-y-4">
                                <div class="flex items-center gap-6">
                                    <span class="text-xs font-semibold text-slate-500 uppercase">Type de contact :</span>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center text-sm text-slate-700">
                                            <input type="radio" wire:model="communicationType" value="whatsapp" class="text-teal-600 bg-white border-slate-350 focus:ring-teal-500">
                                            <span class="ms-1.5">WhatsApp</span>
                                        </label>
                                        <label class="inline-flex items-center text-sm text-slate-700">
                                            <input type="radio" wire:model="communicationType" value="email" class="text-teal-600 bg-white border-slate-350 focus:ring-teal-500">
                                            <span class="ms-1.5">Email</span>
                                        </label>
                                        <label class="inline-flex items-center text-sm text-slate-700">
                                            <input type="radio" wire:model="communicationType" value="call" class="text-teal-600 bg-white border-slate-350 focus:ring-teal-500">
                                            <span class="ms-1.5">Appel tél.</span>
                                        </label>
                                        <label class="inline-flex items-center text-sm text-slate-700">
                                            <input type="radio" wire:model="communicationType" value="note" class="text-teal-600 bg-white border-slate-350 focus:ring-teal-500">
                                            <span class="ms-1.5">Note interne</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <textarea wire:model="communicationMessage" rows="3" placeholder="Saisir les détails de la conversation, message envoyé, ou compte rendu..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500"></textarea>
                                    @error('communicationMessage') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-all shadow-sm">
                                        Enregistrer l'activité
                                    </button>
                                </div>
                            </form>

                            <!-- Timeline Feed -->
                            <div class="relative border-l border-slate-200 ml-4 space-y-6">
                                @forelse($timeline as $item)
                                    <div class="relative pl-6">
                                        <!-- Dot Icon -->
                                        <div class="absolute -left-3.5 top-1.5 w-7 h-7 rounded-full flex items-center justify-center border text-xs font-bold bg-white {{ $item->type === 'whatsapp' ? 'text-emerald-600 border-emerald-200 bg-emerald-50' : ($item->type === 'email' ? 'text-indigo-600 border-indigo-200 bg-indigo-50' : ($item->type === 'call' ? 'text-blue-600 border-blue-200 bg-blue-50' : 'text-slate-600 border-slate-200 bg-slate-50')) }}">
                                            @if($item->type === 'whatsapp')
                                                WA
                                            @elseif($item->type === 'email')
                                                @
                                            @elseif($item->type === 'call')
                                                T
                                            @else
                                                N
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-800 text-sm">
                                                    {{ $item->type === 'whatsapp' ? 'Relance WhatsApp' : ($item->type === 'email' ? 'Envoi Email' : ($item->type === 'call' ? 'Appel Téléphonique' : 'Note interne')) }}
                                                </span>
                                                <span class="text-xs text-slate-400 font-mono">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                                @if($item->user)
                                                    <span class="text-xs text-slate-500">• par {{ $item->user->name }}</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-slate-600 mt-1 leading-relaxed">{{ $item->message }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400 pl-0">Aucun historique d'activité ou de contact pour le moment.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Personal details and internal Notes (1 col) -->
        <div class="space-y-6">
            <!-- Client Information Details Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Fiche d'Information
                </h3>
                <div class="space-y-3 text-sm text-slate-600">
                    @if($client->company_name)
                        <div>
                            <span class="text-slate-400 block text-xs font-semibold uppercase">Nom Entreprise</span>
                            <span class="font-bold text-slate-800">{{ $client->company_name }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">Téléphone</span>
                        <span class="font-mono">{{ $client->phone ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">WhatsApp</span>
                        <span class="font-mono text-emerald-600 font-semibold">{{ $client->whatsapp_number ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">E-mail</span>
                        <span>{{ $client->email ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">Passeport</span>
                        <span class="font-mono">{{ $client->passport ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">Date de naissance</span>
                        <span>{{ $client->date_of_birth ? $client->date_of_birth->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">Profession</span>
                        <span>{{ $client->profession ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-xs font-semibold uppercase">Adresse</span>
                        <span>{{ $client->address ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes Section Card -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-md font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Notes & Commentaires
                </h3>
                <form wire:submit.prevent="saveNotes" class="space-y-3">
                    <textarea wire:model="clientNotes" rows="8" placeholder="Saisir des instructions spécifiques, particularités de tarification, ou remarques sur le client..." class="w-full border border-slate-350 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 bg-slate-50/30"></textarea>
                    @error('clientNotes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    <div class="flex justify-end">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-all shadow-sm">
                            Enregistrer les Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- AI Copilot Panel Drawer -->
    <div x-data="{ open: @entangle('showAiPanel') }" x-show="open" class="fixed inset-0 overflow-hidden z-50" style="display: none;">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-xs transition-opacity" @click="open = false"></div>
            
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md bg-white shadow-xl flex flex-col h-full border-l border-slate-200">
                    <div class="px-6 py-4 bg-slate-900 text-white flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">✨</span>
                            <span class="font-extrabold text-sm tracking-tight text-white">Copilot AI - Insurio</span>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-white">✕</button>
                    </div>

                    <!-- Chat / Suggestion space -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50">
                        <div class="bg-indigo-50 border border-indigo-200/60 p-4 rounded-2xl text-xs text-indigo-950 font-medium leading-relaxed">
                            Bonjour ! Je suis votre Copilot. Je peux analyser le profil de <strong>{{ $client->first_name }} {{ $client->last_name }}</strong>, rédiger des messages de relance personnalisés, ou vous conseiller sur des opportunités de multi-équipement.
                        </div>

                        <!-- Suggestions Quick Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="$set('aiQuery', 'opportunité de vente croisée')" class="bg-white border border-slate-200 hover:bg-slate-50 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-full transition-all">
                                💡 Opportunités Cross-sell
                            </button>
                            <button wire:click="$set('aiQuery', 'rédiger un message de relance whatsapp')" class="bg-white border border-slate-200 hover:bg-slate-50 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-full transition-all">
                                📲 WhatsApp Relance
                            </button>
                            <button wire:click="$set('aiQuery', 'rédiger un email de renouvellement')" class="bg-white border border-slate-200 hover:bg-slate-50 text-[10px] font-bold text-slate-700 px-3 py-1.5 rounded-full transition-all">
                                ✉️ Relance Email
                            </button>
                        </div>

                        <!-- Result display -->
                        @if($aiResult)
                            <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm space-y-3">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-450">RÉPONSE DU COPILOT</span>
                                <div class="text-xs text-slate-700 whitespace-pre-line leading-relaxed font-medium">
                                    {!! $aiResult !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Input form -->
                    <div class="p-4 border-t border-slate-200 bg-white">
                        <form wire:submit.prevent="askAiCopilot" class="flex gap-2">
                            <input type="text" wire:model="aiQuery" placeholder="Posez une question au Copilot..." class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:bg-white transition-all text-slate-700">
                            <button type="submit" class="bg-indigo-650 hover:bg-indigo-750 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-sm">
                                Envoyer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
