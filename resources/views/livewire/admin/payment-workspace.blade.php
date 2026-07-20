<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- HEADER BAR: Case Info & Actions -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-extrabold text-xl shadow-inner">
                    PM
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-gray-900">{{ $payment->payment_number }}</h1>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 rounded-full uppercase tracking-wider">
                            {{ $payment->payment_method }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 font-medium mt-1">
                        Client: <span class="font-bold text-gray-800">{{ $payment->client->nom_complet ?? '-' }}</span> | 
                        Contrat: <span class="font-mono font-bold text-indigo-600">{{ $payment->contract->contract_number ?? '-' }}</span>
                    </p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <div class="flex items-center gap-1.5 bg-slate-100 border border-gray-200 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-700">
                    Statut: 
                    <span class="uppercase text-indigo-700 font-black">{{ $payment->payment_status }}</span>
                </div>

                @if(in_array($payment->payment_status, ['pending', 'draft']))
                    <button wire:click="approvePayment" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                        Approuver & Encaisser
                    </button>
                @endif

                @if($payment->payment_method === 'cheque' && $payment->payment_status === 'deposited')
                    <button wire:click="$set('showRejectionModal', true)" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all">
                        Chèque Rejeté / Impayé
                    </button>
                @endif

                <button wire:click="simulateSendWhatsApp" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all">
                    Partager WhatsApp
                </button>
                <button wire:click="simulateSendEmail" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all">
                    Envoyer Reçu Email
                </button>
            </div>
        </div>

        <!-- PAYMENT TIMELINE PROGRESSION BAR -->
        <div class="bg-white px-6 py-4 rounded-xl border border-gray-200 shadow-sm overflow-x-auto scrollbar-none">
            <div class="flex items-center justify-between min-w-max text-xs font-bold">
                <!-- Step 1: Created -->
                <div class="flex items-center gap-2 text-indigo-650">
                    <span class="h-5 w-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px]">1</span>
                    Créé
                </div>

                <div class="h-0.5 w-16 bg-indigo-600"></div>

                <!-- Step 2: Receipt -->
                <div class="flex items-center gap-2 {{ $payment->receipt_number ? 'text-indigo-650' : 'text-slate-400' }}">
                    <span class="h-5 w-5 rounded-full {{ $payment->receipt_number ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px]">2</span>
                    Reçu Généré
                </div>

                <div class="h-0.5 w-16 {{ $payment->cheque_deposit_date || $payment->payment_status === 'paid' ? 'bg-indigo-600' : 'bg-slate-200' }}"></div>

                <!-- Step 3: Deposited / Received -->
                <div class="flex items-center gap-2 {{ $payment->cheque_deposit_date || $payment->payment_status === 'paid' ? 'text-indigo-650' : 'text-slate-400' }}">
                    <span class="h-5 w-5 rounded-full {{ $payment->cheque_deposit_date || $payment->payment_status === 'paid' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px]">3</span>
                    Déposé / Reçu
                </div>

                <div class="h-0.5 w-16 {{ $payment->payment_status === 'paid' ? 'bg-indigo-600' : 'bg-slate-200' }}"></div>

                <!-- Step 4: Cleared / Validated -->
                <div class="flex items-center gap-2 {{ $payment->payment_status === 'paid' ? 'text-indigo-650' : 'text-slate-400' }}">
                    <span class="h-5 w-5 rounded-full {{ $payment->payment_status === 'paid' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px]">4</span>
                    Encaissé / Validé
                </div>

                <div class="h-0.5 w-16 {{ $payment->payment_status === 'paid' && ($payment->contract->statut ?? '') === 'actif' ? 'bg-indigo-600' : 'bg-slate-200' }}"></div>

                <!-- Step 5: Contract Activated -->
                <div class="flex items-center gap-2 {{ $payment->payment_status === 'paid' && ($payment->contract->statut ?? '') === 'actif' ? 'text-indigo-650' : 'text-slate-400' }}">
                    <span class="h-5 w-5 rounded-full {{ $payment->payment_status === 'paid' && ($payment->contract->statut ?? '') === 'actif' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500' }} flex items-center justify-center text-[10px]">5</span>
                    Contrat Activé
                </div>
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT: Tabs Workspace (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tab Headers -->
                <div class="bg-white p-1 rounded-xl border border-gray-200 shadow-sm flex gap-1">
                    <button wire:click="$set('activeTab', 'overview')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'overview' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                        📝 Vue d'ensemble
                    </button>
                    <button wire:click="$set('activeTab', 'installments')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'installments' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                        🗓️ Échéances
                    </button>
                    <button wire:click="$set('activeTab', 'timeline')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'timeline' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                        📜 Historique & Audit
                    </button>
                    <button wire:click="$set('activeTab', 'followups')" class="px-4 py-2 text-xs font-bold rounded-lg transition-all {{ $activeTab === 'followups' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:bg-slate-50' }}">
                        🔔 Recouvrement ({{ $payment->followUps->count() }})
                    </button>
                </div>

                <!-- Tab content wrapper -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm min-h-[400px]">
                    <!-- TAB 1: OVERVIEW -->
                    @if($activeTab === 'overview')
                        <div class="space-y-6 text-xs font-semibold text-slate-700">
                            <!-- Basic details list -->
                            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Montant total:</span>
                                        <span class="text-slate-800 font-extrabold">{{ number_format($payment->amount, 2) }} DH</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Montant payé:</span>
                                        <span class="text-emerald-600 font-extrabold">{{ number_format($payment->paid_amount, 2) }} DH</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Reste à payer:</span>
                                        <span class="text-rose-600 font-extrabold">{{ number_format($payment->remaining_amount, 2) }} DH</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Date d'opération:</span>
                                        <span class="text-slate-800 font-mono">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Date limite:</span>
                                        <span class="text-slate-800 font-mono">{{ $payment->due_date ? $payment->due_date->format('d/m/Y') : '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-400">Mode de paiement:</span>
                                        <span class="text-slate-800 uppercase">{{ $payment->payment_method }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cheque specifics -->
                            @if($payment->payment_method === 'cheque')
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                                    <h3 class="text-[10px] font-extrabold uppercase text-slate-400">Détails Chèque Enregistrés</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-slate-450">N° Chèque: <span class="text-slate-800 font-bold font-mono">{{ $payment->cheque_number }}</span></p>
                                            <p class="text-slate-450 mt-1">Banque émettrice: <span class="text-slate-800 font-bold">{{ $payment->bank_name }}</span></p>
                                        </div>
                                        <div>
                                            <p class="text-slate-450">Date d'émission: <span class="text-slate-800 font-mono">{{ $payment->cheque_issue_date ? $payment->cheque_issue_date->format('d/m/Y') : '-' }}</span></p>
                                            <p class="text-slate-450 mt-1">Date encaissement: <span class="text-slate-800 font-mono">{{ $payment->cheque_clearance_date ? $payment->cheque_clearance_date->format('d/m/Y') : '-' }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Notes section -->
                            <div class="space-y-2">
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Notes de Règlement</h3>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/50 font-mono text-[11px] whitespace-pre-line text-slate-655 min-h-[60px]">
                                    {{ $payment->notes ?: 'Aucune note.' }}
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" wire:model="newNote" placeholder="Ajouter une note..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none">
                                    <button wire:click="addNote" class="px-4 py-1.5 bg-slate-900 text-white rounded-xl font-bold">Ajouter</button>
                                </div>
                            </div>

                            <!-- Attachments -->
                            <div class="space-y-2 pt-4">
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Justificatifs & Pièces Jointes</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    @forelse($payment->attachments ?: [] as $index => $att)
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/50 flex justify-between items-center">
                                            <div>
                                                <span class="block font-bold text-slate-700">{{ $att['label'] }}</span>
                                                <span class="block text-[9px] text-slate-400 font-mono">{{ $att['file_name'] }}</span>
                                            </div>
                                            <a href="{{ Storage::disk('local')->url($att['path']) }}" target="_blank" class="text-indigo-650 hover:underline">Ouvrir</a>
                                        </div>
                                    @empty
                                        <div class="col-span-2 text-slate-400 italic">Aucun justificatif téléversé.</div>
                                    @endforelse
                                </div>

                                <div class="flex gap-2 items-center bg-slate-50 p-3 rounded-xl border border-slate-200 border-dashed mt-2">
                                    <input type="file" wire:model="uploadedAttachment" class="text-xs">
                                    <input type="text" wire:model="attachmentLabel" placeholder="Libellé (ex: Bordereau dépôt)" class="bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                    <button type="button" wire:click="uploadAttachment" class="px-3 py-1 bg-indigo-600 text-white font-bold rounded-lg text-xs">Uploader</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 2: INSTALLMENTS -->
                    @if($activeTab === 'installments')
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Détails des Échéances du Contrat</h3>
                            <div class="space-y-3">
                                @forelse($payment->installments as $inst)
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center text-xs">
                                        <div>
                                            <span class="block font-extrabold text-slate-800">{{ number_format($inst->amount, 2) }} DH</span>
                                            <span class="block text-[10px] text-slate-400 font-mono">Date limite: {{ $inst->due_date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase
                                                {{ $inst->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}
                                            ">
                                                {{ $inst->status }}
                                            </span>
                                            @if($inst->status !== 'paid')
                                                <button wire:click="recordInstallmentPayment({{ $inst->id }})" class="px-2.5 py-1 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700">Acquitter</button>
                                            @else
                                                <span class="text-slate-450 font-bold font-mono">{{ $inst->receipt_number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-slate-450 italic text-center py-6">Aucun plan d'échéances généré pour ce règlement.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- TAB 3: TIMELINE & AUDIT LOGS -->
                    @if($activeTab === 'timeline')
                        <div class="space-y-6">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b pb-2">Journal d'Audit & Modifications</h3>
                            <div class="flow-root">
                                <ul class="-mb-8 text-xs">
                                    @forelse($payment->auditLogs as $log)
                                        <li class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                                        👤
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-slate-700 font-bold">
                                                            {{ $log->action === 'create' ? 'Création' : 'Modification' }} du règlement par 
                                                            <span class="text-slate-900 font-extrabold">{{ $log->user->name ?? 'Système' }}</span>
                                                        </p>
                                                        @if($log->old_values || $log->new_values)
                                                            <div class="mt-2 bg-slate-50 p-2.5 rounded-lg font-mono text-[10px] text-slate-600 max-w-lg overflow-x-auto">
                                                                @if($log->old_values)
                                                                    <div class="text-rose-650">- {{ json_encode($log->old_values) }}</div>
                                                                @endif
                                                                @if($log->new_values)
                                                                    <div class="text-emerald-750">+ {{ json_encode($log->new_values) }}</div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-[10px] whitespace-nowrap text-slate-400 font-mono">
                                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <div class="text-slate-450 italic py-6">Aucun log d'audit.</div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- TAB 4: FOLLOW UPS -->
                    @if($activeTab === 'followups')
                        <div class="space-y-6 text-xs">
                            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Suivi du Recouvrement Amiable</h3>
                            
                            <!-- Add Followup Form -->
                            <form wire:submit.prevent="addFollowup" class="bg-slate-50 p-4 rounded-xl border border-slate-200/50 space-y-3 font-semibold text-slate-700">
                                <h4 class="text-[10px] font-extrabold uppercase text-slate-400">Planifier un rappel de recouvrement</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-slate-500 mb-0.5">Date limite du rappel</label>
                                        <input type="date" wire:model="followup_date" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 font-mono">
                                    </div>
                                    <div>
                                        <label class="block text-slate-500 mb-0.5">Priorité de l'action</label>
                                        <select wire:model="followup_priority" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5">
                                            <option value="low">Basse</option>
                                            <option value="medium">Moyenne</option>
                                            <option value="high">Haute</option>
                                            <option value="critical">Critique</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-slate-500 mb-0.5">Notes de l'action de recouvrement</label>
                                    <textarea wire:model="followup_notes" rows="2" placeholder="Saisir les consignes de relance..." class="w-full bg-white border border-slate-200 rounded-xl px-3 py-1.5 focus:outline-none"></textarea>
                                    @error('followup_notes') <span class="text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="px-4 py-1.5 bg-slate-900 text-white font-bold rounded-xl">Planifier</button>
                                </div>
                            </form>

                            <!-- Followups list -->
                            <div class="space-y-2">
                                @forelse($payment->followUps as $f)
                                    <div class="p-4 rounded-xl border flex justify-between items-start transition-all {{ $f->completed ? 'bg-slate-50 border-slate-200 text-slate-400' : 'bg-white border-gray-200 shadow-sm text-slate-700' }}">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-extrabold text-[10px] font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">RAPPEL #{{ $f->id }}</span>
                                                <span class="font-bold">Pour: {{ $f->reminder_date->format('d/m/Y') }}</span>
                                                <span class="px-1.5 py-0.2 rounded text-[9px] uppercase font-bold bg-amber-100 text-amber-800">{{ $f->priority }}</span>
                                            </div>
                                            <p class="font-medium text-[11px] leading-relaxed">{{ $f->notes }}</p>
                                        </div>
                                        <div>
                                            <button wire:click="toggleFollowupCompleted({{ $f->id }})" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded text-[10px] font-bold">
                                                {{ $f->completed ? 'Marquer Non Fait' : 'Marquer Fait' }}
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-slate-400 italic text-center py-4">Aucun rappel de recouvrement planifié.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RIGHT COLUMN: Sticky Client Summary Panel -->
            <div class="space-y-6">
                <!-- Client Summary Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4 text-xs font-semibold text-slate-700">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-450 border-b pb-2">Résumé Client CRM</h3>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-gray-600 text-sm">
                            {{ substr($payment->client->nom_complet ?? 'C', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">{{ $payment->client->nom_complet ?? '-' }}</h4>
                            <span class="px-2 py-0.5 text-[8px] font-bold bg-slate-150 text-slate-700 rounded uppercase tracking-wider">
                                {{ $payment->client->client_type ?? 'individual' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 text-slate-600">
                        <div class="flex justify-between">
                            <span>Téléphone:</span>
                            <span class="font-bold text-slate-800">{{ $payment->client->phone ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email:</span>
                            <span class="font-bold text-slate-800 truncate max-w-[150px]">{{ $payment->client->email ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Risque Solvabilité:</span>
                            <span class="font-bold {{ $payment->client->incident ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $payment->client->incident ? 'ÉLEVÉ (Défaut)' : 'NORMAL' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contract Summary Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm space-y-4 text-xs font-semibold text-slate-700">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-450 border-b pb-2">Contrat Lié</h3>
                    <div class="space-y-2 text-slate-600">
                        <div class="flex justify-between">
                            <span>N° Contrat:</span>
                            <span class="font-mono font-bold text-slate-800">{{ $payment->contract->contract_number ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Compagnie:</span>
                            <span class="font-bold text-slate-800">{{ $payment->contract->compagnie->nom ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-indigo-650 font-bold border-t pt-2">
                            <span>Montant Prime Totale:</span>
                            <span>{{ number_format($payment->contract->prime_totale ?? 0, 2) }} DH</span>
                        </div>
                        <div class="flex justify-between text-rose-600 font-bold">
                            <span>Solde Restant Contrat:</span>
                            <span>{{ number_format($payment->contract->solde ?? 0, 2) }} DH</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- REJECTION DETAILS MODAL -->
    @if($showRejectionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-xl w-full max-w-sm p-6 space-y-4 animate-scale-up">
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-base font-extrabold text-slate-800">Déclarer un rejet de chèque</h2>
                    <button wire:click="$set('showRejectionModal', false)" class="text-slate-400 hover:text-slate-700 text-sm">✕</button>
                </div>
                
                <form wire:submit.prevent="recordChequeReturned" class="space-y-4 text-xs font-semibold text-slate-700">
                    <div>
                        <label class="block text-slate-500 mb-1">Motif du rejet bancaire</label>
                        <select wire:model="rejection_reason" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                            <option value="">Sélectionner la raison...</option>
                            <option value="provision_insuffisante">Provision insuffisante (Sans provision)</option>
                            <option value="opposition">Opposition sur chèque</option>
                            <option value="signature_non_conforme">Signature non conforme</option>
                            <option value="compte_bloque">Compte bloqué ou clôturé</option>
                            <option value="autre">Autre motif</option>
                        </select>
                        @error('rejection_reason') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t">
                        <button type="button" wire:click="$set('showRejectionModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl">Confirmer le Rejet</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
