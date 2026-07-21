<div class="max-w-[1600px] mx-auto p-6 space-y-6 font-sans">
    <div>

        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Agenda & Calendrier</h1>
                <p class="text-xs text-slate-450 mt-1">Supervision de l'ensemble des rendez-vous, visites d'experts, relances et échéances.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Mois en cours
                </span>
            </div>
        </div>

        <!-- Calendar Month View Grid -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4 w-full">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <span class="text-base font-bold text-slate-800">Juillet 2026</span>
                <span class="text-xs text-slate-400 font-medium">Vue Mensuelle</span>
            </div>

            <!-- Calendar Days Header -->
            <div class="w-full text-center text-[11px] font-bold text-slate-500 uppercase tracking-widest py-2 border-b border-slate-100" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 0.5rem;">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mer</div>
                <div>Jeu</div>
                <div>Ven</div>
                <div>Sam</div>
                <div>Dim</div>
            </div>

            <!-- Calendar Grid cells (e.g. July 2026 starting on Wednesday) -->
            <div class="w-full" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 0.5rem;">
                <!-- Blank days before July 1st -->
                <div class="bg-slate-50/60 min-h-[100px] rounded-xl border border-slate-200/40 p-2 text-slate-300 font-semibold">29</div>
                <div class="bg-slate-50/60 min-h-[100px] rounded-xl border border-slate-200/40 p-2 text-slate-300 font-semibold">30</div>

                <!-- July Days -->
                @for($day = 1; $day <= 31; $day++)
                    <div class="bg-slate-50 min-h-[100px] rounded-xl border border-slate-200/80 p-2 hover:bg-white hover:shadow-md hover:border-blue-300 transition-all flex flex-col justify-between group">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-700 text-sm group-hover:text-blue-600">{{ $day }}</span>
                        </div>
                        
                        <!-- Event Indicators -->
                        <div class="space-y-1 mt-2">
                            @if($day === 21)
                                <div class="bg-indigo-50 text-indigo-700 rounded-lg p-1.5 text-[10px] font-bold border border-indigo-200 truncate flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                    <span>Rdv Client</span>
                                </div>
                            @elseif($day === 15)
                                <div class="bg-rose-50 text-rose-700 rounded-lg p-1.5 text-[10px] font-bold border border-rose-200 truncate flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                    <span>Échéance</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>

    </div>
</div>
