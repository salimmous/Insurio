<div class="p-6 space-y-6 font-sans">
    <div>

        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Journal des Communications</h1>
                <p class="text-xs text-slate-450 mt-1">Historique centralisé de tous les échanges WhatsApp, Emails, SMS et Appels sortants/entrants.</p>
            </div>
        </div>

        <!-- Logs Timeline/Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <h2 class="font-bold text-slate-850 text-xs uppercase tracking-wider">Échanges Récents</h2>
            </div>
            
            <div class="divide-y divide-slate-150">
                @forelse($communications as $comm)
                    <div class="p-4 flex items-start gap-4 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-sm">
                            @if($comm->type === 'whatsapp') 🟢
                            @elseif($comm->type === 'email') ✉️
                            @elseif($comm->type === 'sms') 📱
                            @else 📞 @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-xs text-slate-800">{{ $comm->client->nom_complet ?? 'Client Inconnu' }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $comm->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">{{ $comm->message }}</p>
                            <span class="text-[9px] text-slate-400 uppercase font-semibold mt-1.5 block">Envoyé par: {{ $comm->user->name ?? 'Système' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">
                        Aucun échange enregistré.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
