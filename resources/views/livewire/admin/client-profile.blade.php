<div class="space-y-6">
    <!-- Header 360° Banner -->
    <div class="bg-white border border-slate-200/85 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-start sm:items-center gap-5">
                <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center font-black text-2xl border border-teal-100 shadow-inner shrink-0">
                    {{ strtoupper(substr($client->first_name ?? $client->nom, 0, 1)) }}{{ strtoupper(substr($client->last_name ?? $client->prenom, 0, 1)) }}
                </div>
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $client->first_name }} {{ $client->last_name }}</h1>
                        @if($client->client_type === 'company')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                🏢 Entreprise
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-100">
                                👤 Particulier
                            </span>
                        @endif

                        <!-- Risk Score Badge -->
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-black border
                            {{ $riskScore === 'A' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                            {{ $riskScore === 'B' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                            {{ $riskScore === 'C' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                            {{ $riskScore === 'D' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}">
                            Score Risque: {{ $riskScore }}
                        </span>

                        <!-- Satisfaction Score Picker -->
                        <div class="flex items-center gap-1 bg-amber-50/70 border border-amber-200 px-2.5 py-0.5 rounded-full text-xs font-bold text-amber-700">
                            <span>Satisfaction:</span>
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="setSatisfaction({{ $i }})" class="hover:scale-125 transition-transform text-xs {{ $i <= $satisfactionScore ? 'text-amber-500' : 'text-slate-300' }}">
                                    ★
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1 font-medium">
                        <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded font-bold">Réf: {{ $client->reference ?? 'CL-' . $client->id }}</span>
                        <span>•</span>
                        <span>CIN / RC: <strong class="text-slate-700">{{ $client->cin ?? '-' }}</strong></span>
                        <span>•</span>
                        <span>Tél: <strong class="text-slate-700 font-mono">{{ $client->phone ?? '-' }}</strong></span>
                        <span>•</span>
                        <span>Ville: <strong class="text-slate-700">{{ $client->city ?? '-' }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Action Toolbar & Advisor Selector -->
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs">
                    <span class="text-slate-500 font-semibold">Conseiller:</span>
                    <select wire:model.change="assignedAdvisorId" wire:change="updateAdvisor" class="bg-transparent border-0 text-slate-800 font-bold focus:ring-0 text-xs py-0 pl-1 pr-6 cursor-pointer">
                        <option value="">-- Non attribué --</option>
                        @foreach($advisors as $advisor)
                            <option value="{{ $advisor->id }}">{{ $advisor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="$set('showAiPanel', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3.5 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-sm">
                    <span>✨</span> AI Copilot 360°
                </button>

                @if($client->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3.5 py-2 rounded-xl transition-all text-xs flex items-center gap-1.5 shadow-sm">
                        <span>💬</span> WhatsApp
                    </a>
                @endif

                <a href="{{ route('admin.clients') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-3.5 py-2 rounded-xl transition-all border border-slate-200/40 text-xs">
                    Retour
                </a>
            </div>
        </div>

        <!-- 360° KPI Metric Ribbon -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100">
            <div class="bg-slate-50/70 rounded-xl p-3.5 border border-slate-100">
                <span class="text-xs font-semibold text-slate-400 block uppercase">Valeur Client (CLV)</span>
                <span class="text-lg font-black text-slate-800 font-mono">{{ number_format($customerLifetimeValue, 2) }} <span class="text-xs text-slate-500 font-sans">DH</span></span>
            </div>
            <div class="bg-rose-50/50 rounded-xl p-3.5 border border-rose-100">
                <span class="text-xs font-semibold text-rose-500 block uppercase">Solde Impayé</span>
                <span class="text-lg font-black text-rose-600 font-mono">{{ number_format($outstandingBalance, 2) }} <span class="text-xs text-rose-400 font-sans">DH</span></span>
            </div>
            <div class="bg-teal-50/50 rounded-xl p-3.5 border border-teal-100">
                <span class="text-xs font-semibold text-teal-600 block uppercase">Dernier Contact</span>
                <span class="text-sm font-bold text-teal-800">{{ $client->last_contact_at ? $client->last_contact_at->format('d/m/Y H:i') : 'Aucun' }}</span>
            </div>
            <div class="bg-indigo-50/50 rounded-xl p-3.5 border border-indigo-100">
                <span class="text-xs font-semibold text-indigo-600 block uppercase">Prochain Contact</span>
                <span class="text-sm font-bold text-indigo-800">{{ $client->next_contact_at ? $client->next_contact_at->format('d/m/Y') : 'Non planifié' }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content 360° Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: 360° Modules Tabs -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <!-- Navigation Tabs Ribbon -->
                <div class="flex flex-wrap border-b border-slate-200 bg-slate-50/50 px-2 gap-1 overflow-x-auto">
                    <button wire:click="$set('activeTab', 'contracts')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'contracts' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        📑 Contrats ({{ $contracts->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'family')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'family' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        👨‍👩‍👧 Famille & Bénéficiaires
                    </button>
                    <button wire:click="$set('activeTab', 'financials')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'financials' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        💳 Règlements ({{ $payments->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'documents')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'documents' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        📂 Documents ({{ $documents->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'tasks')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'tasks' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        📋 Tâches ({{ $tasks->where('status', 'pending')->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'claims')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'claims' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        ⚠️ Sinistres ({{ $claims->count() }})
                    </button>
                    <button wire:click="$set('activeTab', 'timeline')" class="px-3.5 py-3 text-xs font-bold border-b-2 transition-all {{ $activeTab === 'timeline' ? 'border-teal-600 text-teal-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                        📜 Timeline 360°
                    </button>
                </div>

                <!-- Tab Body Content -->
                <div class="p-6">
                    
                    <!-- TAB 1: Contracts -->
                    @if($activeTab === 'contracts')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                <thead class="text-slate-500 font-semibold text-xs uppercase bg-slate-50/50">
                                    <tr>
                                        <th class="px-4 py-3">Réf / Compagnie</th>
                                        <th class="px-4 py-3">Produit</th>
                                        <th class="px-4 py-3">Période</th>
                                        <th class="px-4 py-3">Prime H.T / T.T.C</th>
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
                                            <td class="px-4 py-4 font-semibold text-slate-800">
                                                {{ $contract->product->nom ?? 'Contrat Assurance' }}
                                            </td>
                                            <td class="px-4 py-4 text-xs text-slate-600">
                                                Du {{ \Carbon\Carbon::parse($contract->start_date ?? $contract->date_effet)->format('d/m/Y') }}<br>
                                                au <strong class="text-slate-800">{{ \Carbon\Carbon::parse($contract->end_date ?? $contract->date_echeance)->format('d/m/Y') }}</strong>
                                            </td>
                                            <td class="px-4 py-4 font-mono font-bold text-slate-800">
                                                {{ number_format($contract->premium_amount ?? $contract->prime_totale, 2) }} DH
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                    {{ ($contract->status ?? $contract->statut) === 'active' || ($contract->status ?? $contract->statut) === 'actif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                                    {{ strtoupper($contract->status ?? $contract->statut) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-slate-400 text-sm">
                                                Aucun contrat souscrit pour ce client.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- TAB 2: Family & Beneficiaries -->
                    @if($activeTab === 'family')
                        <div class="space-y-6">
                            <!-- Family Members Section -->
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                        <span>👨‍👩‍👧</span> Membres de la Famille
                                    </h3>
                                    <button wire:click="$set('showFamilyModal', true)" class="bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 text-xs font-bold px-3 py-1.5 rounded-xl transition-all">
                                        + Ajouter Membre
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @forelse($client->family_members ?? [] as $index => $family)
                                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex justify-between items-start">
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm">{{ $family['name'] }}</div>
                                                <div class="text-xs text-slate-500 font-medium">Lien: {{ ucfirst($family['relation']) }} • CIN: {{ $family['cin'] ?? '-' }}</div>
                                                @if(!empty($family['phone']))
                                                    <div class="text-xs text-teal-600 font-mono mt-1">📞 {{ $family['phone'] }}</div>
                                                @endif
                                            </div>
                                            <button wire:click="removeFamilyMember({{ $index }})" class="text-slate-400 hover:text-rose-600 text-xs font-bold">
                                                ✕
                                            </button>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-center py-6 text-slate-400 text-xs bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                            Aucun membre de famille enregistré.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Beneficiaries Section -->
                            <div class="pt-4 border-t border-slate-100">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                        <span>📜</span> Bénéficiaires Désignés
                                    </h3>
                                    <button wire:click="$set('showBeneficiaryModal', true)" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 text-xs font-bold px-3 py-1.5 rounded-xl transition-all">
                                        + Désigner Bénéficiaire
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @forelse($client->beneficiaries ?? [] as $index => $bene)
                                        <div class="bg-indigo-50/40 border border-indigo-100 rounded-xl p-3.5 flex justify-between items-start">
                                            <div>
                                                <div class="font-bold text-indigo-950 text-sm">{{ $bene['name'] }}</div>
                                                <div class="text-xs text-indigo-700 font-semibold">Part: {{ $bene['percentage'] }}% • Lien: {{ ucfirst($bene['relation']) }}</div>
                                                <div class="text-xs text-slate-500 font-mono mt-0.5">CIN: {{ $bene['cin'] ?? '-' }}</div>
                                            </div>
                                            <button wire:click="removeBeneficiary({{ $index }})" class="text-slate-400 hover:text-rose-600 text-xs font-bold">
                                                ✕
                                            </button>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-center py-6 text-slate-400 text-xs bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                            Aucun bénéficiaire désigné.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 3: Financials & Payments -->
                    @if($activeTab === 'financials')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                <thead class="text-slate-500 font-semibold text-xs uppercase bg-slate-50/50">
                                    <tr>
                                        <th class="px-4 py-3">Réf Paiement / Réf Chèque</th>
                                        <th class="px-4 py-3">Mode</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Montant Payé</th>
                                        <th class="px-4 py-3">Solde Dû</th>
                                        <th class="px-4 py-3">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150 text-slate-700">
                                    @forelse($payments as $payment)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-4">
                                                <span class="font-bold text-slate-800 block font-mono">{{ $payment->payment_number ?? ('PAY-' . $payment->id) }}</span>
                                                @if($payment->cheque_number)
                                                    <span class="text-xs text-amber-700 font-mono block">Chèque: {{ $payment->cheque_number }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 font-semibold text-slate-700 uppercase text-xs">
                                                {{ $payment->payment_method }}
                                            </td>
                                            <td class="px-4 py-4 text-xs text-slate-600">
                                                {{ \Carbon\Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-4 font-mono font-bold text-emerald-600">
                                                {{ number_format($payment->paid_amount ?? $payment->amount, 2) }} DH
                                            </td>
                                            <td class="px-4 py-4 font-mono font-bold text-rose-600">
                                                {{ number_format($payment->remaining_amount ?? 0, 2) }} DH
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                    {{ $payment->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                    {{ strtoupper($payment->payment_status ?? 'PAID') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-8 text-slate-400 text-sm">
                                                Aucun règlement répertorié.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- TAB 4: Documents Vault -->
                    @if($activeTab === 'documents')
                        <div class="space-y-4">
                            <!-- Document Upload Form -->
                            <form wire:submit.prevent="uploadDocument" class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex flex-wrap items-center gap-3">
                                <select wire:model="documentType" class="bg-white border border-slate-200 text-xs font-semibold rounded-lg px-3 py-2 text-slate-700">
                                    <option value="cin">Carte Nationale (CIN)</option>
                                    <option value="passport">Passeport</option>
                                    <option value="driving_license">Permis de Conduire</option>
                                    <option value="vehicle_registration">Carte Grise</option>
                                    <option value="contract">Contrat Signé</option>
                                    <option value="invoice">Facture / Quittance</option>
                                    <option value="other">Autre Document</option>
                                </select>

                                <input type="file" wire:model="uploadedFile" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">

                                <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs px-4 py-2 rounded-lg transition-all ml-auto shadow-sm">
                                    Téléverser Document
                                </button>
                            </form>

                            <!-- Document Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @forelse($documents as $doc)
                                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 flex items-center justify-between shadow-2xs hover:border-slate-300 transition-all">
                                        <div class="flex items-center gap-3 truncate">
                                            <div class="w-9 h-9 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center font-bold text-sm shrink-0">
                                                📄
                                            </div>
                                            <div class="truncate">
                                                <div class="font-bold text-slate-800 text-xs truncate">{{ $doc->file_name ?? $doc->nom }}</div>
                                                <div class="text-2xs text-slate-400 uppercase font-semibold">{{ $doc->type }} • {{ $doc->created_at->format('d/m/Y') }}</div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <button wire:click="downloadDocument({{ $doc->id }})" class="text-teal-600 hover:text-teal-800 text-xs font-bold px-2 py-1 bg-teal-50 rounded-md border border-teal-100">
                                                Télécharger
                                            </button>
                                            <button wire:click="deleteDocument({{ $doc->id }})" class="text-rose-500 hover:text-rose-700 text-xs font-bold px-1.5 py-1">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 text-center py-8 text-slate-400 text-xs">
                                        Aucun document dans le coffre-fort client.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 5: Tasks & Follow-ups -->
                    @if($activeTab === 'tasks')
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-slate-800 text-sm">📋 Tâches & Rendez-vous</h3>
                                <button wire:click="$set('showTaskModal', true)" class="bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs px-3.5 py-1.5 rounded-xl shadow-sm">
                                    + Créer Tâche
                                </button>
                            </div>

                            <div class="space-y-2">
                                @forelse($tasks as $task)
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <button wire:click="completeTask({{ $task->id }})" class="w-5 h-5 rounded border border-slate-300 flex items-center justify-center {{ $task->status === 'completed' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white' }}">
                                                @if($task->status === 'completed') ✓ @endif
                                            </button>
                                            <div>
                                                <div class="font-bold text-xs text-slate-800 {{ $task->status === 'completed' ? 'line-through text-slate-400' : '' }}">{{ $task->title }}</div>
                                                <div class="text-2xs text-slate-500">Échéance: {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }} • Priorité: {{ strtoupper($task->priority) }}</div>
                                            </div>
                                        </div>
                                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold {{ $task->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ strtoupper($task->status) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400 text-xs">
                                        Aucune tâche en attente.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 6: Claims History -->
                    @if($activeTab === 'claims')
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-left text-sm">
                                <thead class="text-slate-500 font-semibold text-xs uppercase bg-slate-50/50">
                                    <tr>
                                        <th class="px-4 py-3">Réf Sinistre</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Montant Réclamé</th>
                                        <th class="px-4 py-3">Statut</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-150 text-slate-700">
                                    @forelse($claims as $claim)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-4 font-mono font-bold text-slate-800">
                                                {{ $claim->reference ?? ('SIN-' . $claim->id) }}
                                            </td>
                                            <td class="px-4 py-4 font-semibold text-slate-700 text-xs">
                                                {{ $claim->nature ?? 'Assurance Auto / Risques' }}
                                            </td>
                                            <td class="px-4 py-4 text-xs text-slate-600">
                                                {{ $claim->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-4 font-mono font-bold text-rose-600">
                                                {{ number_format($claim->montant_reclame ?? 0, 2) }} DH
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    {{ strtoupper($claim->statut ?? 'EN COURS') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-slate-400 text-sm">
                                                Aucun sinistre enregistré pour ce client (Excellent profil risque).
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- TAB 7: Interactive 360° Timeline -->
                    @if($activeTab === 'timeline')
                        <div class="space-y-4">
                            <!-- New Activity Form -->
                            <form wire:submit.prevent="addCommunication" class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <div class="flex items-center gap-3">
                                    <select wire:model="communicationType" class="bg-white border border-slate-200 text-xs font-bold rounded-lg px-3 py-1.5 text-slate-700">
                                        <option value="whatsapp">💬 WhatsApp</option>
                                        <option value="email">📧 Email</option>
                                        <option value="sms">📱 SMS</option>
                                        <option value="call">📞 Appels Téléphoniques</option>
                                        <option value="note">📝 Note Interne</option>
                                    </select>
                                    <span class="text-2xs text-slate-400 font-semibold">Enregistrer une interaction</span>
                                </div>
                                <textarea wire:model="communicationMessage" rows="2" placeholder="Saisissez le compte-rendu de l'interaction..." class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-xs text-slate-800 focus:ring-teal-500 focus:border-teal-500"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs px-4 py-1.5 rounded-lg shadow-sm">
                                        Publier dans la Timeline 360°
                                    </button>
                                </div>
                            </form>

                            <!-- Timeline Event Feed -->
                            <div class="relative border-l-2 border-slate-200 ml-4 space-y-6 pt-2">
                                @forelse($timeline as $event)
                                    <div class="relative pl-6">
                                        <div class="absolute -left-2.5 top-0 w-5 h-5 rounded-full border-2 border-white flex items-center justify-center text-2xs font-bold
                                            {{ $event->type === 'whatsapp' ? 'bg-emerald-500 text-white' : '' }}
                                            {{ $event->type === 'email' ? 'bg-indigo-500 text-white' : '' }}
                                            {{ $event->type === 'call' ? 'bg-amber-500 text-white' : '' }}
                                            {{ $event->type === 'note' ? 'bg-slate-600 text-white' : '' }}
                                            {{ $event->type === 'system' ? 'bg-teal-600 text-white' : '' }}">
                                        </div>
                                        <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs">
                                            <div class="flex justify-between items-center text-2xs text-slate-400 font-semibold mb-1">
                                                <span class="uppercase font-bold text-slate-600">{{ $event->type }} • Par {{ $event->user->name ?? 'Système' }}</span>
                                                <span>{{ $event->created_at->format('d/m/Y à H:i') }}</span>
                                            </div>
                                            <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $event->message }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-400 text-xs">
                                        Aucune activité répertoriée dans la Timeline.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Right 1 Col: Internal Notes & Details Sidebar -->
        <div class="space-y-6">
            <!-- Notes Box -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                    <span>📌</span> Notes Internal Advisor
                </h3>
                <textarea wire:model="clientNotes" rows="5" placeholder="Ajouter des remarques confidentielles sur ce client..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-teal-500 focus:border-teal-500"></textarea>
                <div class="flex justify-end">
                    <button wire:click="saveNotes" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition-all shadow-sm">
                        Enregistrer Notes
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Family Member Modal -->
    @if($showFamilyModal)
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="font-bold text-slate-800 text-base">Ajouter Membre de la Famille</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Nom & Prénom *</label>
                        <input type="text" wire:model="familyMemberName" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Lien de Parenté *</label>
                        <select wire:model="familyMemberRelation" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                            <option value="conjoint">Conjoint(e)</option>
                            <option value="enfant">Enfant</option>
                            <option value="parent">Père / Mère</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">N° CIN</label>
                        <input type="text" wire:model="familyMemberCin" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Téléphone</label>
                        <input type="text" wire:model="familyMemberPhone" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showFamilyModal', false)" class="px-3.5 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Annuler</button>
                    <button wire:click="addFamilyMember" class="px-4 py-1.5 bg-teal-600 text-white rounded-lg text-xs font-bold">Enregistrer</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Beneficiary Modal -->
    @if($showBeneficiaryModal)
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="font-bold text-slate-800 text-base">Désigner un Bénéficiaire</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Nom & Prénom Bénéficiaire *</label>
                        <input type="text" wire:model="beneficiaryName" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Lien de Parenté *</label>
                        <select wire:model="beneficiaryRelation" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                            <option value="conjoint">Conjoint(e)</option>
                            <option value="enfant">Enfant</option>
                            <option value="héritiers">Héritiers Légaux</option>
                            <option value="autre">Autre Désigné</option>
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Pourcentage d'Allocation (%) *</label>
                        <input type="number" wire:model="beneficiaryPercentage" min="1" max="100" class="w-full border border-slate-200 rounded-lg p-2 text-xs font-mono">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">N° CIN</label>
                        <input type="text" wire:model="beneficiaryCin" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showBeneficiaryModal', false)" class="px-3.5 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Annuler</button>
                    <button wire:click="addBeneficiary" class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold">Confirmer Bénéficiaire</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Task Modal -->
    @if($showTaskModal)
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <h3 class="font-bold text-slate-800 text-base">Planifier Tâche / Rendez-vous</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Intitulé de la Tâche *</label>
                        <input type="text" wire:model="taskTitle" placeholder="Ex: Relance renouvellement auto..." class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Date d'Échéance *</label>
                        <input type="date" wire:model="taskDueDate" class="w-full border border-slate-200 rounded-lg p-2 text-xs font-mono">
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Priorité</label>
                        <select wire:model="taskPriority" class="w-full border border-slate-200 rounded-lg p-2 text-xs">
                            <option value="low">Faible</option>
                            <option value="normal">Normale</option>
                            <option value="high">Haute</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button wire:click="$set('showTaskModal', false)" class="px-3.5 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Annuler</button>
                    <button wire:click="addTask" class="px-4 py-1.5 bg-teal-600 text-white rounded-lg text-xs font-bold">Créer Tâche</button>
                </div>
            </div>
        </div>
    @endif
</div>
