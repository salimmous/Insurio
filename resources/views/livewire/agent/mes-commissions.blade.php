<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Mes Commissions</h1>
            <p class="text-sm text-gray-500">Consultez l'historique et le statut de vos commissions internes sur les contrats réalisés.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ number_format(collect($commissions)->where('statut', 'calculee')->sum('montant_commission'), 2) }} DH
                    </span>
                    <span class="text-sm text-gray-500">Calculées (Attente validation)</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ number_format(collect($commissions)->where('statut', 'validee')->sum('montant_commission'), 2) }} DH
                    </span>
                    <span class="text-sm text-gray-500">Validées (À recevoir)</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-green-50 text-green-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ number_format(collect($commissions)->where('statut', 'payee')->sum('montant_commission'), 2) }} DH
                    </span>
                    <span class="text-sm text-gray-500">Payées</span>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Période (Mois)</label>
                <select wire:model.live="selectedPeriod" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Toutes les périodes</option>
                    @foreach($periods as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-64">
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Statut</label>
                <select wire:model.live="selectedStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tous les statuts</option>
                    <option value="calculee">Calculée</option>
                    <option value="validee">Validée (En attente paiement)</option>
                    <option value="payee">Payée</option>
                    <option value="annulee">Annulée</option>
                </select>
            </div>
        </div>

        <!-- List table -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-55 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Période</th>
                            <th class="px-6 py-3">Contrat</th>
                            <th class="px-6 py-3">Client</th>
                            <th class="px-6 py-3">Base Calcul</th>
                            <th class="px-6 py-3">Taux</th>
                            <th class="px-6 py-3">Ma Commission</th>
                            <th class="px-6 py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-900">
                        @forelse($commissions as $com)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-600 font-mono text-xs">
                                    {{ $com->periode }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-700 font-bold">
                                    #{{ $com->contrat->numero_contrat }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $com->contrat->client->nom_complet }}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-600">
                                    {{ number_format($com->base_calcul, 2) }} DH
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500 text-xs">
                                    {{ $com->taux_applique }}%
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                    {{ number_format($com->montant_commission, 2) }} DH
                                </td>
                                <td class="px-6 py-4">
                                    @if($com->statut === 'calculee')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Calculée</span>
                                    @elseif($com->statut === 'validee')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Validée</span>
                                    @elseif($com->statut === 'payee')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Payée</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    Aucune commission enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($commissions as $com)
                    <div class="p-4 flex flex-col gap-2 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="font-mono text-xs text-gray-400 block">[{{ $com->periode }}] - Contrat #{{ $com->contrat->numero_contrat }}</span>
                                <span class="font-semibold text-gray-800">Client: {{ $com->contrat->client->nom_complet }}</span>
                            </div>
                            @if($com->statut === 'calculee')
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-yellow-100 text-yellow-800">Calculée</span>
                            @elseif($com->statut === 'validee')
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">Validée</span>
                            @elseif($com->statut === 'payee')
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-green-100 text-green-800">Payée</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">Annulée</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-600 flex justify-between items-center bg-gray-55 p-2 rounded border border-gray-150">
                            <div>
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">Taux ({{ $com->taux_applique }}%)</div>
                                <div class="font-bold">{{ number_format($com->base_calcul, 2) }} DH</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[10px] text-gray-400 uppercase font-semibold">Ma Commission</div>
                                <div class="font-bold text-indigo-600 text-sm">{{ number_format($com->montant_commission, 2) }} DH</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 text-sm">
                        Aucune commission enregistrée.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
