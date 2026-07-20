<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header block -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Moteur d'Automation & Relances</h1>
                <p class="text-xs text-slate-500 mt-1">Configurez des scénarios automatiques déclenchés sur les échéances des contrats (WhatsApp, Tâches, E-mails).</p>
            </div>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-full border border-emerald-200">Moteur Actif</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Form: Create Rule (1/3) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4 h-fit">
                <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Nouvelle Règle d'Automation</h2>
                
                <form wire:submit.prevent="saveRule" class="space-y-4 text-xs font-semibold text-slate-650">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Nom de la Règle</label>
                        <input type="text" wire:model="name" placeholder="Ex: Relance WhatsApp 15 Jours" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:bg-white transition-all text-slate-700">
                        @error('name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Événement Déclencheur</label>
                        <select wire:model="event" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:bg-white transition-all text-slate-700">
                            <option value="contract.expiring">Échéance de Contrat Approchante</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Condition : Jours avant l'échéance</label>
                        <input type="number" wire:model="daysBeforeExpiry" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs outline-none focus:bg-white transition-all text-slate-700">
                        @error('daysBeforeExpiry') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2.5 pt-2">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Actions à Exécuter</label>
                        
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="actionWhatsapp" class="rounded text-teal-600 focus:ring-teal-500 border-slate-350 bg-slate-50">
                            <span>Relancer via WhatsApp</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="actionTask" class="rounded text-teal-600 focus:ring-teal-500 border-slate-350 bg-slate-50">
                            <span>Créer une tâche de suivi pour l'agent</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="actionEmail" class="rounded text-teal-600 focus:ring-teal-500 border-slate-350 bg-slate-50">
                            <span>Envoyer un E-mail récapitulatif</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-xl text-xs transition-all shadow-sm">
                        Créer la Règle d'Automation
                    </button>
                </form>
            </div>

            <!-- List: Active Rules (2/3) -->
            <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Règles actives configurées</span>
                    <span class="text-[10px] font-bold font-mono text-slate-450">Total: {{ count($rulesList) }}</span>
                </div>

                <div class="divide-y divide-slate-150">
                    @forelse($rulesList as $rule)
                        <div class="p-5 flex justify-between items-start gap-4 hover:bg-slate-50 transition-colors">
                            <div class="space-y-1.5">
                                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    {{ $rule->name }}
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-bold {{ $rule->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-50 text-slate-500 border border-slate-200' }}">
                                        {{ $rule->is_active ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-slate-450 font-medium">Déclencheur: <span class="font-mono bg-slate-100 rounded px-1">{{ $rule->event }}</span></p>
                                <p class="text-xs text-slate-450 font-medium">Condition: <span class="font-bold text-slate-700">Si échéance dans {{ $rule->conditions['days_before_expiry'] ?? '-' }} jours</span></p>
                                
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    @foreach($rule->actions as $action)
                                        <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-[9px] font-bold rounded-lg uppercase tracking-wide">
                                            @if($action['type'] === 'whatsapp') 📲 WhatsApp
                                            @elseif($action['type'] === 'task') 📝 Tâche Suivi
                                            @elseif($action['type'] === 'email') ✉️ E-mail
                                            @else {{ $action['type'] }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button wire:click="toggleRule({{ $rule->id }})" class="px-3 py-1.5 rounded-lg border text-xs font-bold transition-all {{ $rule->is_active ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'border-emerald-250 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                    {{ $rule->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                                <button onclick="confirm('Supprimer cette règle ?') || event.stopImmediatePropagation()" wire:click="deleteRule({{ $rule->id }})" class="px-3 py-1.5 rounded-lg border border-rose-250 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition-all">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Aucune règle d'automation créée.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
