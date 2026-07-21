<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/85 rounded-2xl p-6 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tableau Kanban des Tâches CRM</h1>
                <p class="text-sm text-slate-500">Gérez, attribuez et suivez les actions et relances clients en équipe.</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle Tâche
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full">
                <input type="text" wire:model.live="search" placeholder="Rechercher une tâche par titre ou description..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="priorityFilter" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Toutes priorités</option>
                    <option value="low">Basse</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                </select>
            </div>
        </div>

        <!-- Kanban Board Columns Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Column 1: A faire -->
            <div class="bg-slate-100/60 border border-slate-200/60 rounded-2xl p-4 flex flex-col h-screen max-h-[700px] overflow-y-auto">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-200/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                        <h2 class="font-bold text-slate-700 text-sm uppercase">À faire ({{ $todoTasks->count() }})</h2>
                    </div>
                </div>
                <div class="space-y-4 flex-1">
                    @forelse($todoTasks as $task)
                        @include('livewire.admin.partials.task-card', ['task' => $task, 'nextStatus' => 'progress', 'nextLabel' => 'Lancer'])
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs bg-white/60 border border-dashed rounded-xl">Aucune tâche.</div>
                    @endif
                </div>
            </div>

            <!-- Column 2: En cours -->
            <div class="bg-slate-100/60 border border-slate-200/60 rounded-2xl p-4 flex flex-col h-screen max-h-[700px] overflow-y-auto">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-200/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                        <h2 class="font-bold text-slate-700 text-sm uppercase">En cours ({{ $progressTasks->count() }})</h2>
                    </div>
                </div>
                <div class="space-y-4 flex-1">
                    @forelse($progressTasks as $task)
                        @include('livewire.admin.partials.task-card', ['task' => $task, 'nextStatus' => 'completed', 'nextLabel' => 'Terminer', 'prevStatus' => 'todo', 'prevLabel' => 'Remettre à faire'])
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs bg-white/60 border border-dashed rounded-xl">Aucune tâche.</div>
                    @endif
                </div>
            </div>

            <!-- Column 3: Terminé -->
            <div class="bg-slate-100/60 border border-slate-200/60 rounded-2xl p-4 flex flex-col h-screen max-h-[700px] overflow-y-auto">
                <div class="flex justify-between items-center mb-4 pb-2 border-b border-slate-200/50">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h2 class="font-bold text-slate-700 text-sm uppercase">Terminé ({{ $completedTasks->count() }})</h2>
                    </div>
                </div>
                <div class="space-y-4 flex-1">
                    @forelse($completedTasks as $task)
                        @include('livewire.admin.partials.task-card', ['task' => $task, 'prevStatus' => 'progress', 'prevLabel' => 'Recommencer'])
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs bg-white/60 border border-dashed rounded-xl">Aucune tâche.</div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Task Create/Edit Modal -->
        @if($isModalOpen)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-150">
                            <h3 class="text-lg font-bold text-slate-800" id="modal-title">
                                {{ $taskId ? 'Modifier la Tâche' : 'Créer une Nouvelle Tâche' }}
                            </h3>
                        </div>
                        <form wire:submit.prevent="saveTask" class="p-6 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Titre de la tâche</label>
                                <input type="text" wire:model="title" placeholder="ex: Rappeler le client pour signature" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Description / Notes</label>
                                <textarea wire:model="description" rows="3" placeholder="Saisir des précisions supplémentaires..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Client concerné</label>
                                    <select wire:model="client_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Sélectionner...</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Contrat associé (facultatif)</label>
                                    <select wire:model="contract_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                                        <option value="">Aucun</option>
                                        @foreach($contracts as $con)
                                            <option value="{{ $con->id }}">{{ $con->contract_number }} ({{ $con->client->last_name ?? '' }})</option>
                                        @endforeach
                                    </select>
                                    @error('contract_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Assigné à</label>
                                    <select wire:model="assigned_user_id" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- Non attribué --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Date d'échéance</label>
                                    <input type="date" wire:model="due_date" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    @error('due_date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Priorité</label>
                                    <select wire:model="priority" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="low">Basse</option>
                                        <option value="medium">Moyenne</option>
                                        <option value="high">Haute</option>
                                    </select>
                                    @error('priority') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Statut</label>
                                    <select wire:model="status" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="todo">À faire</option>
                                        <option value="progress">En cours</option>
                                        <option value="completed">Terminé</option>
                                    </select>
                                    @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 -mx-6 -mb-6 border-t border-slate-150">
                                <button type="button" wire:click="closeModal" class="inline-flex justify-center px-4 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Annuler
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
