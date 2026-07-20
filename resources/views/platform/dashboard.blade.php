@extends('layouts.platform')

@section('title', 'Tableau de Bord Central - Insurio')

@section('content')
<!-- Header block -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Tableau de Bord Central</h1>
        <p class="text-xs text-slate-500 mt-1">Console de Supervision Centrale de l'infrastructure multi-tenant d'Insurio.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('platform.tenants.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition-all shadow-sm flex items-center gap-1.5">
            <span>➕</span> Nouvelle Agence
        </a>
    </div>
</div>

@if(session()->has('message'))
    <div class="bg-emerald-50 border border-emerald-200/60 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold">
        {{ session('message') }}
    </div>
@endif

<!-- Stripe-style Analytical KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- MRR & ARR Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Revenu Récurrent (MRR / ARR)</span>
        <div>
            <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($mrr, 2) }} DH</div>
            <div class="text-[10px] text-slate-400 font-mono mt-0.5">ARR: {{ number_format($arr, 2) }} DH</div>
        </div>
        <span class="text-[9px] text-emerald-600 font-bold flex items-center gap-1">🟢 En croissance stable</span>
    </div>

    <!-- Monthly/Yearly Revenue Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Chiffre d'Affaires Encaissé</span>
        <div>
            <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($revenueThisMonth, 2) }} DH</div>
            <div class="text-[10px] text-slate-400 font-mono mt-0.5">Annuel: {{ number_format($revenueThisYear, 2) }} DH</div>
        </div>
        <span class="text-[9px] text-slate-400 font-medium">Ce mois-ci (Date de valeur)</span>
    </div>

    <!-- Agency Subscriptions Status -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Cabinets Enregistrés</span>
        <div>
            <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $activeTenantsCount }} / {{ $tenants->count() }}</div>
            <div class="text-[10px] text-slate-400 font-medium mt-0.5">Suspendus: {{ $suspendedTenantsCount }} | Trial: {{ $trialTenantsCount }}</div>
        </div>
        <span class="text-[9px] text-amber-600 font-bold">⚠️ {{ $expiringSubscriptionsCount }} abonnements expirent bientôt</span>
    </div>

    <!-- Invoices & Pending Payments -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/85 shadow-sm flex flex-col justify-between h-32 hover:shadow-md transition-shadow">
        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-400">Factures & Paiements en Attente</span>
        <div>
            <div class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ number_format($pendingInvoicesSum, 2) }} DH</div>
            <div class="text-[10px] text-slate-400 font-mono mt-0.5">Factures impayées: {{ $pendingInvoicesCount }} | Échecs: {{ $failedPaymentsCount }}</div>
        </div>
        <span class="text-[9px] text-rose-600 font-bold">🔴 À relancer</span>
    </div>
</div>

<!-- Aggregated System Telemetry (Tenant DB totals summed up) -->
<div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm">
    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block border-b border-slate-100 pb-3">Activité Consolidée du Réseau (Tous Cabinets)</span>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 pt-4 text-xs font-medium">
        <div>
            <span class="text-slate-400 block">Total Clients</span>
            <span class="text-base font-bold text-slate-800 block mt-0.5 font-mono">{{ number_format($totalClients) }}</span>
        </div>
        <div>
            <span class="text-slate-400 block">Polices Actives</span>
            <span class="text-base font-bold text-slate-800 block mt-0.5 font-mono">{{ number_format($totalContracts) }}</span>
        </div>
        <div>
            <span class="text-slate-400 block">Volume Primes Émises</span>
            <span class="text-base font-bold text-emerald-600 block mt-0.5 font-mono">{{ number_format($totalPrimes, 2) }} DH</span>
        </div>
        <div>
            <span class="text-slate-400 block">Employés d'Agences</span>
            <span class="text-base font-bold text-slate-800 block mt-0.5 font-mono">{{ number_format($totalEmployees) }}</span>
        </div>
        <div>
            <span class="text-slate-400 block">Documents Archivés</span>
            <span class="text-base font-bold text-slate-800 block mt-0.5 font-mono">{{ number_format($totalDocuments) }}</span>
        </div>
        <div>
            <span class="text-slate-400 block">Sinistres Déclarés</span>
            <span class="text-base font-bold text-rose-600 block mt-0.5 font-mono">{{ number_format($totalClaims) }}</span>
        </div>
    </div>
</div>

<!-- Main Row: Registered Agencies & Server Health -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Agencies List (2/3) -->
    <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Registre des Cabinets</h2>
            <span class="text-[10px] text-slate-450 font-bold font-mono">Total: {{ $tenants->count() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-650 min-w-[700px]">
                <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    <tr>
                        <th class="px-5 py-3">ID / Agence</th>
                        <th class="px-5 py-3">Contrats</th>
                        <th class="px-5 py-3">Primes Émises</th>
                        <th class="px-5 py-3 text-center">Plan</th>
                        <th class="px-5 py-3 text-center">Statut</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-xs">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-slate-800 text-sm">{{ $tenant->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $tenant->id }}</div>
                            </td>
                            <td class="px-5 py-3.5 font-mono font-semibold">{{ number_format($tenant->stats['contrats'] ?? 0) }}</td>
                            <td class="px-5 py-3.5 font-mono text-slate-900 font-semibold">{{ number_format($tenant->stats['primes'] ?? 0, 2) }} DH</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border {{ $tenant->plan === 'entreprise' ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : ($tenant->plan === 'premium' ? 'bg-teal-50 border-teal-200 text-teal-700' : 'bg-slate-50 border-slate-200 text-slate-600') }}">
                                    {{ ucfirst($tenant->plan ?? 'gratuit') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold border {{ $tenant->status === 'active' ? 'bg-emerald-50 border-emerald-250/50 text-emerald-700' : ($tenant->status === 'trial' ? 'bg-blue-50 border-blue-200 text-blue-700' : 'bg-rose-50 border-rose-250/50 text-rose-700') }}">
                                    {{ $tenant->status === 'active' ? 'Actif' : ($tenant->status === 'trial' ? 'Essai' : 'Suspendu') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('platform.tenants.edit', $tenant->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-bold">Gérer</a>
                                <span class="text-slate-300">|</span>
                                <a href="{{ route('platform.tenants.impersonate', $tenant->id) }}" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-900 font-bold">Impersonate</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Aucun cabinet d'assurance enregistré.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Infrastructure Health & Alert Center (1/3) -->
    <div class="flex flex-col gap-6">
        <!-- Infrastructure Health Panel -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2.5">Télémétrie Serveur & Health</h2>
            
            <div class="space-y-3.5 text-xs">
                <div class="flex justify-between items-center">
                    <span class="text-slate-450 font-medium">Uptime Serveur</span>
                    <span class="font-mono font-bold text-emerald-600">{{ $serverHealth }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-50 pt-2.5">
                    <span class="text-slate-450 font-medium">Statut des Queues</span>
                    <span class="font-mono font-bold text-emerald-600">{{ $queueStatus }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-50 pt-2.5">
                    <span class="text-slate-450 font-medium">Moteur Cron/Schedule</span>
                    <span class="font-mono font-bold text-emerald-600">{{ $cronStatus }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-50 pt-2.5">
                    <span class="text-slate-450 font-medium">Taille Base de Données</span>
                    <span class="font-mono font-bold text-slate-700">{{ $databaseUsage }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-50 pt-2.5">
                    <span class="text-slate-450 font-medium">Stockage Fichiers</span>
                    <span class="font-mono font-bold text-slate-700">{{ $storageUsage }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-50 pt-2.5">
                    <span class="text-slate-450 font-medium">Appels API (30j)</span>
                    <span class="font-mono font-bold text-slate-700">{{ number_format($apiUsageCount) }}</span>
                </div>
            </div>
        </div>

        <!-- System Notifications & Platform Alerts -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
            <h2 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2.5">Alertes & Notifications Plateforme</h2>
            
            <div class="space-y-3">
                <div class="bg-rose-50 border border-rose-200/60 p-3 rounded-xl flex items-start gap-2.5">
                    <span class="text-sm">🚨</span>
                    <div>
                        <div class="font-bold text-xs text-rose-800">Sauvegarde en retard</div>
                        <div class="text-[10px] text-rose-600 mt-0.5">La dernière sauvegarde automatisée remonte à 26h.</div>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-250/60 p-3 rounded-xl flex items-start gap-2.5">
                    <span class="text-sm">⚠️</span>
                    <div>
                        <div class="font-bold text-xs text-amber-800">Tentative DNS échouée</div>
                        <div class="text-[10px] text-amber-600 mt-0.5">Le domaine personnalisé de l'agence AXA Maarif n'est pas pointé vers le serveur.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
