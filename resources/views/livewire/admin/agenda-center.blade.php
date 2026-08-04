<div class="max-w-[1600px] mx-auto p-6 space-y-6 font-sans">
    
    <!-- Top Header & Controls -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold text-slate-800">Agenda & Échéancier de Renouvellements</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-slate-900 text-white uppercase tracking-widest">
                    Production & Échéances
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Supervision globale des échéances de contrats, relances clients et rendez-vous d'agence.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.renouvellements') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md flex items-center gap-2">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21v-5h5"/></svg>
                Voir tous les Renouvellements
            </a>
        </div>
    </div>

    <!-- Calendar Month View Grid Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4 w-full">
        
        <!-- Navigation Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-slate-800 capitalize">
                    {{ $startOfMonth->locale('fr')->isoFormat('MMMM YYYY') }}
                </h2>
                <button wire:click="goToToday" class="px-3 py-1 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg transition-colors">
                    Aujourd'hui
                </button>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="previousMonth" class="px-3 py-2 text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all font-bold text-xs flex items-center gap-1">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                    Mois Précédent
                </button>
                <button wire:click="nextMonth" class="px-3 py-2 text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all font-bold text-xs flex items-center gap-1">
                    Mois Suivant
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        <!-- Days of Week Header (Explicit 7-Column CSS Grid) -->
        <div class="w-full text-center text-[11px] font-extrabold text-slate-500 uppercase tracking-widest py-2 border-b border-slate-100" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 0.5rem;">
            <div>Lun</div>
            <div>Mar</div>
            <div>Mer</div>
            <div>Jeu</div>
            <div>Ven</div>
            <div>Sam</div>
            <div>Dim</div>
        </div>

        @php
            $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
            $daysInMonth = $startOfMonth->daysInMonth;
            $todayStr = now()->toDateString();
        @endphp

        <!-- Calendar Days Grid (Explicit 7-Column CSS Grid) -->
        <div class="w-full" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 0.5rem;">
            <!-- Empty Padding Cells before 1st day of month -->
            @for($i = 1; $i < $startDayOfWeek; $i++)
                <div class="bg-slate-50/40 min-h-[110px] rounded-xl border border-slate-200/30 p-2 opacity-30"></div>
            @endfor

            <!-- Month Days -->
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $currentDate = Carbon\Carbon::createFromDate($year, $month, $d)->toDateString();
                    $isToday = ($currentDate === $todayStr);
                    $dayContracts = $expiringContracts->get($currentDate, collect());
                    $dayTasks = $tasks->get($currentDate, collect());
                    $hasEvents = $dayContracts->isNotEmpty() || $dayTasks->isNotEmpty();
                @endphp

                <div wire:click="selectDate('{{ $currentDate }}')" 
                     class="min-h-[110px] rounded-xl border p-2 flex flex-col justify-between transition-all cursor-pointer group relative {{ $isToday ? 'bg-indigo-50/50 border-indigo-400 ring-2 ring-indigo-400/20' : 'bg-slate-50 border-slate-200/80 hover:bg-white hover:border-slate-400 hover:shadow-md' }}">
                    
                    <div class="flex justify-between items-center">
                        <span class="font-extrabold text-xs {{ $isToday ? 'text-white bg-indigo-600 rounded-full h-5 w-5 flex items-center justify-center shadow-sm' : 'text-slate-700 group-hover:text-slate-900' }}">
                            {{ $d }}
                        </span>
                        @if($dayContracts->isNotEmpty())
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-amber-500 text-white shadow-sm">
                                {{ $dayContracts->count() }} écheance(s)
                            </span>
                        @endif
                    </div>

                    <!-- Events & Renouvellements Badges -->
                    <div class="space-y-1.5 my-1.5 overflow-hidden">
                        @foreach($dayContracts->take(2) as $contract)
                            <div class="bg-amber-500/10 text-amber-900 border border-amber-300/80 rounded-lg p-1 text-[10px] font-bold truncate flex items-center justify-between gap-1 hover:bg-amber-500/20 transition-all">
                                <span class="truncate">🔄 {{ $contract->contract_number ?? $contract->numero_contrat ?? 'Police' }}</span>
                                <span class="text-[9px] font-mono shrink-0 text-amber-800">{{ number_format($contract->premium_amount ?? $contract->prime_totale ?? 0, 0) }} MAD</span>
                            </div>
                        @endforeach

                        @if($dayContracts->count() > 2)
                            <div class="text-[9px] font-bold text-amber-700 hover:underline">
                                + {{ $dayContracts->count() - 2 }} autre(s) renouvellement(s)
                            </div>
                        @endif

                        @foreach($dayTasks->take(1) as $task)
                            <div class="bg-slate-200/80 text-slate-800 border border-slate-300 rounded-lg p-1 text-[10px] font-bold truncate flex items-center gap-1">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600 shrink-0"></span>
                                <span class="truncate">{{ $task->title }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="text-[9px] font-bold text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity text-right">
                        Détails →
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- DATE DETAILS & RENEWAL MODAL -->
    @if($showModal && $selectedDate)
    <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 space-y-6 animate-fade-in max-h-[90vh] overflow-y-auto">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span>📅</span> Échéances & Renouvellements du {{ Carbon\Carbon::parse($selectedDate)->locale('fr')->isoFormat('D MMMM YYYY') }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Contrats arrivant à échéance et relances d'agence prévues.</p>
                </div>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-700 font-black text-base p-1">✕</button>
            </div>

            <!-- Expiring Contracts List -->
            <div class="space-y-3">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    Contrats à Renouveler ({{ $selectedDateContracts->count() }})
                </h4>

                @forelse($selectedDateContracts as $contract)
                    @php
                        $clientName = $contract->client ? ($contract->client->nom . ' ' . $contract->client->prenom) : ($contract->souscripteur ?? 'Client Agence');
                        $clientPhone = $contract->client->telephone ?? $contract->client->phone ?? '';
                        $contractNum = $contract->contract_number ?? $contract->numero_contrat ?? $contract->policy_number ?? 'POL-2026';
                        $dateEnd = $contract->end_date ?? $contract->date_echeance;
                        $formattedDate = $dateEnd ? Carbon\Carbon::parse($dateEnd)->format('d/m/Y') : $selectedDate;
                        $whatsappMsg = rawurlencode("Bonjour {$clientName},\n\nVotre contrat d'assurance N° {$contractNum} arrive à échéance le {$formattedDate}.\n\nMerci de contacter l'agence pour effectuer le renouvellement.\n\nCordialement, AXA Assurance Maarif.");
                    @endphp

                    <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-extrabold text-sm text-slate-900 block">{{ $clientName }}</span>
                                <span class="text-xs font-mono text-slate-600 block mt-0.5">Police: <strong>{{ $contractNum }}</strong></span>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl text-xs font-black bg-amber-500 text-white shadow-sm font-mono">
                                {{ number_format($contract->premium_amount ?? $contract->prime_totale ?? 0, 2) }} MAD
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-amber-200/60">
                            <span class="text-xs text-slate-500 font-medium">Échéance: <strong class="text-amber-900 font-bold">{{ $formattedDate }}</strong></span>
                            
                            <div class="flex items-center gap-2">
                                @if($clientPhone)
                                    <a href="https://wa.me/212{{ preg_replace('/[^0-9]/', '', ltrim($clientPhone, '0')) }}?text={{ $whatsappMsg }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-xl transition-all flex items-center gap-1 shadow-sm">
                                        <span>💬</span> Relance WhatsApp
                                    </a>
                                @endif

                                <a href="{{ route('admin.renouvellements') }}" class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all shadow-sm flex items-center gap-1">
                                    <span>🔄</span> Traiter le Renouvellement
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 rounded-xl bg-slate-50 text-center text-xs text-slate-450 border border-slate-200/60">
                        Aucune échéance de contrat enregistrée pour cette date.
                    </div>
                @endforelse
            </div>

            <!-- Tasks List -->
            @if($selectedDateTasks->isNotEmpty())
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-slate-600"></span>
                    Tâches & Relances ({{ $selectedDateTasks->count() }})
                </h4>

                @foreach($selectedDateTasks as $t)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-slate-800 block">{{ $t->title }}</span>
                            <span class="text-[10px] text-slate-500">{{ $t->client ? ($t->client->nom . ' ' . $t->client->prenom) : 'Général' }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-800 uppercase">{{ $t->status }}</span>
                    </div>
                @endforeach
            </div>
            @endif

            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                <a href="{{ route('admin.renouvellements') }}" class="text-xs font-bold text-slate-900 hover:underline">
                    Accéder à la liste complète des renouvellements →
                </a>
                <button wire:click="closeModal" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
