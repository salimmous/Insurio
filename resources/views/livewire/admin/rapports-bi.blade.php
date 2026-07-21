<div class="p-6 space-y-6 font-sans">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Intelligence & Analytics BI</h1>
            <p class="text-xs text-slate-500">Performances multi-succursales, sinistralité et ventilation par compagnie d'assurance.</p>
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="selectedYear" class="border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold bg-white focus:ring-teal-500">
                <option value="2026">Exercice 2026</option>
                <option value="2025">Exercice 2025</option>
            </select>
        </div>
    </div>

    <!-- Top Executive KPI Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Volume Primes Total</span>
            <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ number_format($totalPrimes, 2) }} DH</span>
            <span class="text-[9px] text-teal-600 font-bold block mt-1">Chiffre d'Affaires Emis</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Sinistres Déclarés</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block font-mono">{{ number_format($totalSinistres) }}</span>
            <span class="text-[9px] text-slate-400 font-bold block mt-1">Dossiers d'Accidents</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Réseau Agences</span>
            <span class="text-2xl font-black text-indigo-600 mt-1 block font-mono">{{ $succursales->count() }} Succursales</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">Implantation Nationale</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Ratios Clés</span>
            <span class="text-2xl font-black text-slate-800 mt-1 block font-mono">100% SLA</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">Règlement Conforme</span>
        </div>
    </div>

    <!-- Branch Performance Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">Performance par Succursale</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead>
                        <tr class="text-left font-extrabold uppercase tracking-wider text-slate-400 text-[9px]">
                            <th class="pb-3">Agence</th>
                            <th class="pb-3">Ville</th>
                            <th class="pb-3 text-center">Contrats Émis</th>
                            <th class="pb-3 text-center">Sinistres</th>
                            <th class="pb-3 text-right">Volume Primes (DH)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($branchPerformance as $bp)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3.5 font-bold text-slate-900">{{ $bp['name'] }} <span class="text-[10px] text-slate-400 font-mono">[{{ $bp['code'] }}]</span></td>
                            <td class="py-3.5 text-slate-500">{{ $bp['city'] ?? 'Non spécifiée' }}</td>
                            <td class="py-3.5 text-center font-mono font-bold text-slate-700">{{ $bp['contracts'] }}</td>
                            <td class="py-3.5 text-center font-mono font-bold text-amber-600">{{ $bp['sinistres'] }}</td>
                            <td class="py-3.5 text-right font-mono font-black text-teal-600">{{ number_format($bp['prime_volume'], 2) }} DH</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Insurer Distribution -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
            <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3">Ventilation par Compagnie</h2>
            <div class="space-y-3">
                @foreach($insurerStats as $ins)
                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-800">
                        <span>{{ $ins['nom'] }}</span>
                        <span class="font-mono text-teal-600">{{ number_format($ins['volume'], 2) }} DH</span>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-400">
                        <span>{{ $ins['count'] }} polices actives</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
