<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Agenda & Calendrier</h1>
                <p class="text-xs text-slate-450 mt-1">Supervision de l'ensemble des rendez-vous, visites d'experts, relances et échéances.</p>
            </div>
        </div>

        <!-- Calendar Month View Grid -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-sm font-bold text-slate-800">Juillet 2026</span>
                <span class="text-xs text-slate-400 font-medium">Vue Mensuelle</span>
            </div>

            <!-- Calendar Days Header -->
            <div class="grid grid-cols-7 gap-2 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mer</div>
                <div>Jeu</div>
                <div>Ven</div>
                <div>Sam</div>
                <div>Dim</div>
            </div>

            <!-- Calendar Grid cells (e.g. July 2026 starting on Wednesday) -->
            <div class="grid grid-cols-7 gap-2 text-xs">
                <!-- Blank days before July 1st -->
                <div class="bg-slate-50 min-h-[90px] rounded-xl border border-slate-200/40 p-2 text-slate-300">29</div>
                <div class="bg-slate-50 min-h-[90px] rounded-xl border border-slate-200/40 p-2 text-slate-300">30</div>

                <!-- July Days -->
                @for($day = 1; $day <= 31; $day++)
                    <div class="bg-slate-50 min-h-[90px] rounded-xl border border-slate-200/80 p-2 hover:bg-slate-100/50 transition-colors flex flex-col justify-between">
                        <span class="font-bold text-slate-700">{{ $day }}</span>
                        
                        <!-- Event Indicators -->
                        @if($day === 21)
                            <div class="space-y-1">
                                <div class="bg-indigo-50 text-indigo-700 rounded p-1 text-[9px] font-bold border border-indigo-150 truncate">Rdv Client</div>
                            </div>
                        @elseif($day === 15)
                            <div class="space-y-1">
                                <div class="bg-rose-50 text-rose-700 rounded p-1 text-[9px] font-bold border border-rose-150 truncate">Échéance</div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

    </div>
</div>
