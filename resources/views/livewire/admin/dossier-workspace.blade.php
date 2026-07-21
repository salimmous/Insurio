<div class="p-6 space-y-6 font-sans">
    <div>
        
        <!-- HEADER BAR: Case Info & Actions -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-extrabold text-xl shadow-inner">
                    DS
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900">{{ $dossier->dossier_number }}</h1>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 rounded-full uppercase tracking-wider">{{ $dossier->type }}</span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-1">Client: <span class="font-bold text-gray-800">{{ $dossier->client->nom_complet }}</span> | Créé le {{ $dossier->creation_date->format('d/m/Y') }}</p>
                </div>
            </div>
            
            <!-- Quick Workflow Status Badge Dropdown -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <div class="flex items-center gap-1.5 bg-slate-100 border border-gray-200 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-700">
                    Statut Actuel: 
                    <span class="uppercase text-indigo-700">{{ $dossier->status }}</span>
                </div>

                <select wire:change="updateStatus($event.target.value)" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-3 py-2 rounded-xl focus:outline-none cursor-pointer border-none shadow-sm shadow-indigo-900/10">
                    <option value="">Changer Statut...</option>
                    <option value="open">Ouvert</option>
                    <option value="assigned">Assigné</option>
                    <option value="waiting_client">Attente Client</option>
                    <option value="waiting_company">Attente Compagnie</option>
                    <option value="waiting_expert">Attente Expert</option>
                    <option value="waiting_garage">Attente Garage</option>
                    <option value="docs_missing">Pièces Manquantes</option>
                    <option value="in_progress">En Cours</option>
                    <option value="approved">Approuvé</option>
                    <option value="resolved">Résolu</option>
                    <option value="closed">Fermé</option>
                    <option value="cancelled">Annulé</option>
                </select>
            </div>
        </div>

        <!-- WORKFLOW PATH TRACKER (Chevron Banner) -->
        <div class="bg-white px-6 py-4 rounded-xl border border-gray-200 shadow-sm mb-6 overflow-x-auto scrollbar-none">
            <div class="flex items-center min-w-max">
                @php
                    $steps = ['open' => 'Ouvert', 'assigned' => 'Assigné', 'in_progress' => 'En Cours', 'waiting_company' => 'Compagnie', 'resolved' => 'Résolu', 'closed' => 'Fermé'];
                    $currentReached = true;
                @endphp
                @foreach($steps as $key => $label)
                    <div class="flex items-center">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold transition-all
                            @if($dossier->status === $key) bg-indigo-600 text-white shadow-sm shadow-indigo-900/10
                            @elseif($dossier->progress >= ($loop->index * 20)) text-indigo-700 bg-indigo-50/50
                            @else text-gray-400 @endif">
                            <span class="h-4 w-4 rounded-full bg-current opacity-25 flex items-center justify-center text-[10px] text-white font-bold">{{ $loop->iteration }}</span>
                            {{ $label }}
                        </div>
                        @if(!$loop->last)
                            <svg class="h-4 w-4 mx-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- MAIN LAYOUT (2 Columns) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- LEFT COLUMN: Workspace Tabs & Details (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tab Headers -->
                <div class="bg-white p-1 rounded-xl border border-gray-200 shadow-sm flex overflow-x-auto scrollbar-none gap-1">
                    @foreach([
                        'overview' => '📁 Vue d\'ensemble',
                        'timeline' => '🕒 Timeline',
                        'communications' => '💬 Communications',
                        'tasks' => '✔️ Tâches',
                        'payments' => '💰 Paiements',
                        'parties' => '👥 Parties',
                        'expert_garage' => '🔧 Expert & Garage',
                        'documents' => '📄 Documents',
                        'ai_assistant' => '⚡ Copilot AI'
                    ] as $tabKey => $tabLabel)
                        <button wire:click="$set('activeTab', '{{ $tabKey }}')" class="flex-shrink-0 px-4 py-2.5 text-xs font-bold rounded-lg transition-all
                            @if($activeTab === $tabKey) bg-indigo-50 text-indigo-700
                            @else text-gray-500 hover:text-gray-900 hover:bg-slate-50 @endif">
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </div>

                <!-- Tab Contents -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm min-h-[500px]">
                    
                    <!-- TAB 1: OVERVIEW -->
                    @if($activeTab === 'overview')
                        <div class="space-y-6">
                            <!-- Checklist & Details -->
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Checklist de Validation</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($dossier->checklist ?? [] as $index => $item)
                                    <div wire:click="toggleChecklistItem({{ $index }})" class="p-3 bg-slate-50 border border-gray-200 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-slate-100 transition-all select-none">
                                        <div class="h-5 w-5 rounded-full flex items-center justify-center border transition-all
                                            @if($item['completed']) bg-emerald-500 border-emerald-500 text-white @else border-gray-300 text-transparent @endif">
                                            ✓
                                        </div>
                                        <span class="text-xs font-semibold text-gray-700 @if($item['completed']) line-through text-gray-400 @endif">{{ $item['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @if($dossier->type === 'claim')
                                <!-- Accident details specifically for claims -->
                                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mt-8 mb-4">Déclaration de l'Accident</h2>
                                <form wire:submit.prevent="saveAccidentDetails" class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date de l'accident</label>
                                            <input type="date" wire:model.defer="accident_date" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Heure de l'accident</label>
                                            <input type="time" wire:model.defer="accident_time" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Ville</label>
                                            <input type="text" wire:model.defer="accident_city" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Casablanca">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Adresse</label>
                                            <input type="text" wire:model.defer="accident_address" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Rte d'El Jadida">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Météo</label>
                                            <input type="text" wire:model.defer="weather" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Pluie, Soleil">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">État de la route</label>
                                            <input type="text" wire:model.defer="road_condition" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Humide, Sec">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Responsable</label>
                                            <input type="text" wire:model.defer="responsible_party" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Client, Tiers">
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                                            <input type="checkbox" wire:model.defer="police_present" class="rounded border-gray-300 text-indigo-600">
                                            Police présente
                                        </label>
                                        <label class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                                            <input type="checkbox" wire:model.defer="ambulance_present" class="rounded border-gray-300 text-indigo-600">
                                            Ambulance présente
                                        </label>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Description narrative</label>
                                        <textarea wire:model.defer="description" rows="3" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Description de la collision..."></textarea>
                                    </div>
                                    
                                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mt-4">Notes des Parties</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Déclaration du client</label>
                                            <textarea wire:model.defer="statement_client" rows="2" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Notes internes (Conseiller)</label>
                                            <textarea wire:model.defer="notes_employee" rows="2" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2 text-xs"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                        💾 Enregistrer la Déclaration
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <!-- TAB 2: TIMELINE / HISTORY -->
                    @if($activeTab === 'timeline')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Fil d'Activités & Historique du Cas</h2>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @php
                                        // Collect both Communications notes and System logs into one timeline
                                        $timeline = $dossier->communications->where('type', 'note')->sortByDesc('created_at');
                                    @endphp
                                    @forelse($timeline as $event)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-sm ring-8 ring-white">
                                                            @if(str_starts_with($event->message, 'Système')) ⚙️ @else 📝 @endif
                                                        </span>
                                                    </div>
                                                    <div class="flex-1 min-w-0 pt-1.5">
                                                        <p class="text-xs text-gray-800 font-semibold">{{ $event->message }}</p>
                                                        <div class="text-[10px] text-gray-400 mt-0.5">
                                                            Par <span class="font-bold text-gray-600">{{ $event->user ? $event->user->name : 'Système' }}</span> le {{ $event->created_at->format('d/m/Y à H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="py-8 text-center text-xs text-gray-450 italic">Aucune activité enregistrée.</div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 3: COMMUNICATIONS -->
                    @if($activeTab === 'communications')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Enregistrer un Contact Client (Email, WhatsApp, Appel)</h2>
                            
                            <!-- Record Comm Form -->
                            <form wire:submit.prevent="sendMessage" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-gray-200">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Type de Contact</label>
                                        <select wire:model="comm_type" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-semibold">
                                            <option value="whatsapp">💬 WhatsApp (Envoi Direct)</option>
                                            <option value="email">✉️ E-mail</option>
                                            <option value="call">📞 Appel Téléphonique</option>
                                            <option value="note">📝 Note Interne / Appel Entrant</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Message / Notes de la conversation</label>
                                    <textarea wire:model.defer="comm_message" rows="3" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ecrivez votre note ici..."></textarea>
                                    @error('comm_message') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    Enregistrer dans le dossier
                                </button>
                            </form>

                            <!-- Comm Thread -->
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Fil des communications</h3>
                                @forelse($dossier->communications->sortByDesc('created_at') as $comm)
                                    <div class="p-4 rounded-xl border border-gray-150 flex flex-col gap-2">
                                        <div class="flex justify-between items-center">
                                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded
                                                @if($comm->type === 'whatsapp') bg-emerald-50 text-emerald-700
                                                @elseif($comm->type === 'email') bg-blue-50 text-blue-700
                                                @elseif($comm->type === 'call') bg-amber-50 text-amber-700
                                                @else bg-slate-50 text-slate-700 @endif">
                                                {{ $comm->type }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-semibold">{{ $comm->created_at?->format('d/m/Y H:i') }}</span>
                                        </div>
                                        <p class="text-xs text-gray-800 font-medium leading-relaxed">{{ $comm->message }}</p>
                                        <span class="text-[9px] text-gray-450 self-end font-semibold">Par {{ $comm->user ? $comm->user->name : 'Système' }}</span>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs text-gray-450 italic">Aucune note de communication enregistrée.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 4: TASKS -->
                    @if($activeTab === 'tasks')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Planification des Tâches Opérationnelles</h2>
                            
                            <!-- Task Create Form -->
                            <form wire:submit.prevent="addTask" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-slate-50 p-4 rounded-xl border border-gray-200">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Libellé de la tâche</label>
                                    <input type="text" wire:model.defer="task_title" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Récupérer constat amiable">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Échéance</label>
                                    <input type="date" wire:model.defer="task_due_date" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs py-2 rounded-xl transition-all shadow-sm">
                                    Ajouter Tâche
                                </button>
                            </form>

                            <!-- Task list -->
                            <div class="space-y-2">
                                @forelse($dossier->tasks as $t)
                                    <div class="p-3 border border-gray-150 rounded-xl flex items-center justify-between hover:bg-slate-50/30 transition-all">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" wire:click="toggleTask({{ $t->id }})" @if($t->status === 'completed') checked @endif class="rounded border-gray-300 text-indigo-600 h-4 w-4">
                                            <span class="text-xs font-semibold text-gray-700 @if($t->status === 'completed') line-through text-gray-400 @endif">{{ $t->title }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($t->due_date)
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-bold">Échéance: {{ $t->due_date->format('d/m/Y') }}</span>
                                            @endif
                                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded @if($t->status === 'completed') bg-emerald-50 text-emerald-700 @else bg-amber-50 text-amber-700 @endif">{{ $t->status }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs text-gray-450 italic">Aucune tâche planifiée pour ce dossier.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 5: PAYMENTS & CHEQUES -->
                    @if($activeTab === 'payments')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Gestion des Règlements & Chèques Impayés</h2>
                            
                            @if($dossier->type === 'returned_cheque' || $dossier->type === 'payment_issue')
                                <form wire:submit.prevent="saveChequeDetails" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-gray-200 mb-6">
                                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Fiche d'incident du Chèque</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Numéro du Chèque</label>
                                            <input type="text" wire:model.defer="cheque_number" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Banque émettrice</label>
                                            <input type="text" wire:model.defer="cheque_bank" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Attijariwafa Bank">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Émission</label>
                                            <input type="date" wire:model.defer="cheque_issue_date" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Remise</label>
                                            <input type="date" wire:model.defer="cheque_deposit_date" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Retour</label>
                                            <input type="date" wire:model.defer="cheque_returned_date" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Motif de rejet</label>
                                            <input type="text" wire:model.defer="cheque_reason" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Sans provision">
                                        </div>
                                    </div>
                                    <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                        💾 Enregistrer Informations Chèque
                                    </button>
                                </form>
                            @endif

                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Historique des Règlements Liés</h3>
                                <div class="bg-slate-50 p-4 rounded-xl border border-gray-200 flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-600">Total Enregistré pour le Client :</span>
                                    <span class="text-sm font-extrabold text-slate-800">
                                        {{ $dossier->client->solde_impaye ?? 0 }} DH (Impayé Global)
                                    </span>
                                </div>
                                <div class="py-8 text-center text-xs text-gray-450 italic">Aucun règlement direct lié à cet incident.</div>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 6: PARTIES INVOLVED -->
                    @if($activeTab === 'parties')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Annuaire des Parties Prenantes / Intervenants</h2>
                            
                            <!-- Add Party Form -->
                            <form wire:submit.prevent="addInvolvedParty" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-gray-200">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nom Complet</label>
                                        <input type="text" wire:model.defer="party_name" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Expert Alami">
                                        @error('party_name') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rôle</label>
                                        <select wire:model="party_role" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-semibold">
                                            <option value="driver">Conducteur</option>
                                            <option value="owner">Propriétaire</option>
                                            <option value="victim">Victime</option>
                                            <option value="witness">Témoin</option>
                                            <option value="expert">Expert Sinistres</option>
                                            <option value="garage">Garage Partenaire</option>
                                            <option value="lawyer">Avocat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Téléphone</label>
                                        <input type="text" wire:model.defer="party_phone" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">E-mail</label>
                                        <input type="email" wire:model.defer="party_email" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Société / Cabinet</label>
                                        <input type="text" wire:model.defer="party_company" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    Ajouter l'Intervenant
                                </button>
                            </form>

                            <!-- Parties List -->
                            <div class="space-y-4">
                                @forelse($dossier->involvedParties as $party)
                                    <div class="p-4 border border-gray-150 rounded-xl flex justify-between items-start hover:bg-slate-50/50">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-bold text-gray-800">{{ $party->name }}</h4>
                                                <span class="px-2 py-0.5 text-[9px] font-bold bg-slate-100 text-slate-700 uppercase rounded">{{ $party->role }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Tél: {{ $party->phone ?? 'N/A' }} | Email: {{ $party->email ?? 'N/A' }}</p>
                                            @if($party->company)
                                                <p class="text-[10px] text-gray-400 mt-0.5">Société: <span class="font-semibold text-gray-650">{{ $party->company }}</span></p>
                                            @endif
                                        </div>
                                        <button wire:click="deleteInvolvedParty({{ $party->id }})" class="text-xs text-rose-600 hover:text-rose-900 bg-rose-50 px-2.5 py-1 rounded-lg font-bold">Retirer</button>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs text-gray-450 italic">Aucun intervenant externe lié.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 7: EXPERT & GARAGE -->
                    @if($activeTab === 'expert_garage')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Interventions Experts & Garages Partenaires</h2>
                            
                            <!-- Expert Details -->
                            <form wire:submit.prevent="saveExpertDetails" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-gray-200">
                                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Suivi Expertises</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nom de l'expert</label>
                                        <input type="text" wire:model.defer="expert_name" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date visite</label>
                                        <input type="date" wire:model.defer="expert_visit_date" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cabinet expertise</label>
                                        <input type="text" wire:model.defer="expert_company" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Dégâts estimés (DH)</label>
                                        <input type="number" step="0.01" wire:model.defer="estimated_damage" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Coût de réparation (DH)</label>
                                        <input type="number" step="0.01" wire:model.defer="repair_cost" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                                        <input type="checkbox" wire:model.defer="repairable" class="rounded border-gray-300 text-indigo-600">
                                        Véhicule réparable
                                    </label>
                                    <label class="flex items-center gap-2 text-xs font-semibold text-gray-700">
                                        <input type="checkbox" wire:model.defer="total_loss" class="rounded border-gray-300 text-indigo-600">
                                        Perte totale (Épave)
                                    </label>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Conclusions / Recommandations</label>
                                    <textarea wire:model.defer="expert_recommendations" rows="2" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs" placeholder="Synthèse du rapport..."></textarea>
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    💾 Enregistrer Expertise
                                </button>
                            </form>

                            <!-- Garage Details -->
                            <form wire:submit.prevent="saveGarageDetails" class="space-y-4 bg-slate-50 p-4 rounded-xl border border-gray-200 mt-6">
                                <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Suivi Réparations (Garage)</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Garage</label>
                                        <input type="text" wire:model.defer="garage_name" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs" placeholder="Ex: Garage du Centre">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rendez-vous dépose</label>
                                        <input type="datetime-local" wire:model.defer="garage_appointment_at" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Statut réparation</label>
                                        <select wire:model.defer="garage_status" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs font-semibold">
                                            <option value="pending">En attente de dépose</option>
                                            <option value="in_progress">En cours de réparation</option>
                                            <option value="completed">Réparations Terminées</option>
                                            <option value="cancelled">Annulé</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Entrée Réparation</label>
                                        <input type="date" wire:model.defer="garage_repair_start_date" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date Sortie Réparation</label>
                                        <input type="date" wire:model.defer="garage_repair_end_date" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Montant Devis (DH)</label>
                                        <input type="number" step="0.01" wire:model.defer="garage_estimate" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Facture Réelle (DH)</label>
                                        <input type="number" step="0.01" wire:model.defer="garage_invoice" class="w-full bg-white border border-gray-250 rounded-xl px-4 py-2 text-xs">
                                    </div>
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    💾 Enregistrer Réparation
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- TAB 8: DOCUMENTS & PHOTOS -->
                    @if($activeTab === 'documents')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Centre de Documents & Galerie Photos</h2>
                            
                            <!-- Upload Form -->
                            <form wire:submit.prevent="uploadDocument" class="bg-slate-50 p-4 rounded-xl border border-gray-200 flex flex-col md:flex-row gap-4 items-end">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Choisir le Fichier</label>
                                    <input type="file" wire:model="upload_file" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    @error('upload_file') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Type de Document</label>
                                    <select wire:model="upload_type" class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-semibold">
                                        <option value="constat">Constat Amiable</option>
                                        <option value="police_report">Rapport de Police</option>
                                        <option value="expert_report">Rapport d'Expertise</option>
                                        <option value="invoice">Facture</option>
                                        <option value="photo">Photo du Sinistre</option>
                                        <option value="other">Autre Pièce</option>
                                    </select>
                                </div>
                                <button type="submit" class="bg-indigo-650 hover:bg-indigo-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                                    Télécharger
                                </button>
                            </form>

                            <!-- Document Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @forelse($dossier->documents as $doc)
                                    <div class="p-3 border border-gray-150 rounded-xl bg-slate-50 flex flex-col justify-between h-32 hover:shadow-sm">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <span class="text-[9px] bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded font-bold uppercase tracking-wider">{{ $doc->type }}</span>
                                                <span class="text-[8px] text-gray-400 font-semibold">{{ $doc->created_at?->format('d/m') }}</span>
                                            </div>
                                            <h4 class="text-xs font-bold text-gray-800 mt-2 truncate">{{ $doc->nom }}</h4>
                                        </div>
                                        <div class="flex gap-2">
                                            <!-- Link to document preview using the secure preview route -->
                                            <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" class="text-[10px] bg-white border border-gray-200 px-2 py-1 rounded text-gray-600 font-bold hover:bg-gray-50 flex-1 text-center">Consulter</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs text-gray-450 italic col-span-3">Aucun document importé.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 9: AI COPILOT ASSISTANT -->
                    @if($activeTab === 'ai_assistant')
                        <div class="space-y-6">
                            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider border-b pb-2 mb-4">Insurio Copilot AI Assistant ⚡</h2>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <button wire:click="askAi('summary')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold text-xs p-3 rounded-xl transition-all shadow-inner text-left flex flex-col justify-between h-20">
                                    <span>📊</span>
                                    <span>Résumer Dossier</span>
                                </button>
                                <button wire:click="askAi('client_response')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold text-xs p-3 rounded-xl transition-all shadow-inner text-left flex flex-col justify-between h-20">
                                    <span>💬</span>
                                    <span>Réponse Client</span>
                                </button>
                                <button wire:click="askAi('insurance_response')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold text-xs p-3 rounded-xl transition-all shadow-inner text-left flex flex-col justify-between h-20">
                                    <span>✉️</span>
                                    <span>Réponse Compagnie</span>
                                </button>
                                <button wire:click="askAi('missing_docs')" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-bold text-xs p-3 rounded-xl transition-all shadow-inner text-left flex flex-col justify-between h-20">
                                    <span>🔍</span>
                                    <span>Détecter Pièces Manquantes</span>
                                </button>
                            </div>

                            @if($aiResponse)
                                <div class="bg-slate-50 border border-indigo-100 rounded-2xl p-6 space-y-4">
                                    <div class="flex justify-between items-center border-b pb-2 border-slate-200">
                                        <span class="text-xs font-extrabold text-indigo-700 tracking-wider uppercase">Rapport Copilot AI</span>
                                        <button wire:click="$set('aiResponse', '')" class="text-xs text-gray-400 font-bold hover:text-gray-650">Effacer</button>
                                    </div>
                                    <div class="text-xs text-gray-800 leading-relaxed font-medium whitespace-pre-line">
                                        {!! $aiResponse !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>

            <!-- RIGHT COLUMN: Sticky Info Panel (1/3 width) -->
            <div class="space-y-6">
                <!-- Checklist Tracker Card -->
                <div class="bg-indigo-950 text-white p-6 rounded-2xl border border-indigo-900 shadow-sm space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-indigo-200">Suivi Progression SLA</h3>
                    <div class="flex justify-between items-baseline">
                        <span class="text-3xl font-black">{{ $dossier->progress }}%</span>
                        <span class="text-[10px] font-bold bg-indigo-800 text-indigo-100 px-2 py-0.5 rounded uppercase">{{ $dossier->status }}</span>
                    </div>
                    <div class="w-full bg-indigo-900 rounded-full h-2 overflow-hidden">
                        <div class="bg-teal-400 h-2 rounded-full transition-all duration-500" style="width: {{ $dossier->progress }}%"></div>
                    </div>
                </div>

                <!-- Client Summary Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-450 border-b pb-2">Résumé Client</h3>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-gray-600 text-sm">
                            {{ substr($dossier->client->nom_complet, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-sm font-bold text-gray-800 truncate">{{ $dossier->client->nom_complet }}</h4>
                            <span class="px-2 py-0.5 text-[8px] font-bold bg-slate-150 text-slate-700 rounded uppercase">{{ $dossier->client->client_type }}</span>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-xs font-medium text-gray-600">
                        <div class="flex justify-between">
                            <span>Téléphone:</span>
                            <span class="font-bold text-gray-850">{{ $dossier->client->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>National ID:</span>
                            <span class="font-bold text-gray-850">{{ $dossier->client->national_id ?? $dossier->client->cin ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Solde Impayé:</span>
                            <span class="font-bold text-rose-600">{{ $dossier->client->solde_impaye ?? 0 }} DH</span>
                        </div>
                    </div>
                </div>

                <!-- Contract Summary Card -->
                @if($dossier->contract)
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-450 border-b pb-2">Contrat Associé</h3>
                        <div class="space-y-2 text-xs font-medium text-gray-600">
                            <div class="flex justify-between">
                                <span>Numéro Police:</span>
                                <span class="font-bold text-indigo-700">{{ $dossier->contract->policy_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Date Fin:</span>
                                <span class="font-bold text-gray-850">{{ $dossier->contract->end_date?->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Compagnie:</span>
                                <span class="font-bold text-gray-850">{{ $dossier->compagnie ? $dossier->compagnie->nom : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Assignment Settings Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-450 border-b pb-2">Attribution du Dossier</h3>
                    <div class="space-y-4">
                        <!-- Employee Selection -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Conseiller Assigné</label>
                            <select wire:model.live="assigned_employee_id" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700">
                                <option value="">Non assigné</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nom_complet }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Manager Selection -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Superviseur / Manager</label>
                            <select wire:model.live="manager_id" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-gray-700">
                                <option value="">Sélectionner un manager</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority & Urgency Update -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Priorité</label>
                                <select wire:model="priority" wire:change="updatePriorityUrgency" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-2 py-1 text-xs font-semibold text-gray-700">
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                    <option value="critical">Critique</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Urgence</label>
                                <select wire:model="urgency" wire:change="updatePriorityUrgency" class="w-full bg-slate-50 border border-gray-200 rounded-xl px-2 py-1 text-xs font-semibold text-gray-700">
                                    <option value="low">Basse</option>
                                    <option value="medium">Moyenne</option>
                                    <option value="high">Haute</option>
                                    <option value="critical">Critique</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
