@extends('layouts.platform')

@section('title', ucfirst($moduleName) . ' - Insurio Central')

@section('content')
<!-- Header block -->
<div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <span>
                @if($moduleName === 'subscriptions') 🔁
                @elseif($moduleName === 'plans') 🎟️
                @elseif($moduleName === 'invoices') 🧾
                @elseif($moduleName === 'payments') 💳
                @elseif($moduleName === 'domains') 🌐
                @elseif($moduleName === 'tickets') 🎫
                @elseif($moduleName === 'activity') 📜
                @elseif($moduleName === 'feature-flags') 🚩
                @elseif($moduleName === 'backups') 💾
                @elseif($moduleName === 'webhooks') 🪝
                @elseif($moduleName === 'templates') ✉️
                @elseif($moduleName === 'monitoring') 🚦
                @else ⚙️
                @endif
            </span>
            {{ ucfirst($moduleName === 'activity' ? 'Logs & Audit Trail' : ($moduleName === 'tickets' ? 'Support Tickets' : ($moduleName === 'templates' ? 'Message Templates' : ($moduleName === 'agencies' ? 'Cabinets & Agences' : $moduleName)))) }}
        </h1>
        <p class="text-xs text-slate-500 mt-1">Supervision et configuration globale du module central {{ $moduleName }}.</p>
    </div>
</div>

<!-- Dynamic module content -->
<div class="space-y-6">

    <!-- 1. AGENCIES / TENANTS MODULE -->
    @if($moduleName === 'agencies')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Liste de tous les cabinets</span>
                <a href="{{ route('platform.tenants.create') }}" class="text-xs bg-indigo-650 hover:bg-indigo-750 text-white font-bold px-3 py-1.5 rounded-xl border border-indigo-700">Onboarder Nouveau Cabinet</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Code / ID</th>
                            <th class="px-5 py-3">Nom Cabinet</th>
                            <th class="px-5 py-3">E-mail Principal</th>
                            <th class="px-5 py-3 text-center">Plan Actif</th>
                            <th class="px-5 py-3 text-center">Statut</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($tenants as $tenant)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-teal-700">{{ $tenant->id }}</td>
                                <td class="px-5 py-3.5 text-slate-900 font-bold text-sm">{{ $tenant->name }}</td>
                                <td class="px-5 py-3.5 font-mono text-slate-500">{{ $tenant->email ?? 'contact@'.$tenant->id.'.ma' }}</td>
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
                                    <a href="{{ route('platform.tenants.edit', $tenant->id) }}" class="text-indigo-650 hover:text-indigo-900 font-bold text-xs">Gérer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 2. SUBSCRIPTIONS MODULE -->
    @elseif($moduleName === 'subscriptions')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Abonnements SaaS des Cabinets</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Cabinet</th>
                            <th class="px-5 py-3">Plan</th>
                            <th class="px-5 py-3">Statut</th>
                            <th class="px-5 py-3">Début d'Abonnement</th>
                            <th class="px-5 py-3">Prochaine Échéance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-xs">
                        @forelse($subscriptions as $sub)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900 font-sans">{{ $sub->tenant->name }}</td>
                                <td class="px-5 py-3.5">{{ $sub->plan->name }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $sub->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ strtoupper($sub->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $sub->started_at->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-3.5 text-slate-900 font-semibold">{{ $sub->ends_at ? $sub->ends_at->format('d/m/Y') : 'À vie' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-slate-450">Aucun abonnement configuré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 3. PLANS MODULE -->
    @elseif($moduleName === 'plans')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">PLAN TARIFAIRE</span>
                    <h3 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h3>
                    <div class="text-3xl font-black text-slate-900 font-mono">{{ number_format($plan->price, 2) }} DH <span class="text-xs text-slate-400 font-medium">/ mois</span></div>
                    
                    <div class="border-t border-slate-100 pt-4 space-y-2.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span>Limite Utilisateurs :</span>
                            <span class="font-bold font-mono">{{ $plan->limits['users_limit'] ?? 'Illimité' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Limite Succursales :</span>
                            <span class="font-bold font-mono">{{ $plan->limits['succursales_limit'] ?? 'Illimité' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    <!-- 4. INVOICES MODULE -->
    @elseif($moduleName === 'invoices')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Factures générées pour les Cabinets</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">N° Facture</th>
                            <th class="px-5 py-3">Cabinet</th>
                            <th class="px-5 py-3">Montant</th>
                            <th class="px-5 py-3">Raison de facturation</th>
                            <th class="px-5 py-3">Statut</th>
                            <th class="px-5 py-3">Date limite</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-xs">
                        @forelse($invoices as $inv)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-teal-700">INV-{{ str_pad((string)$inv->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3.5 font-sans font-bold text-slate-800">{{ $inv->tenant->name }}</td>
                                <td class="px-5 py-3.5 text-slate-900 font-semibold">{{ number_format($inv->amount, 2) }} DH</td>
                                <td class="px-5 py-3.5 font-sans text-slate-500">{{ str_replace('_', ' ', $inv->billing_reason) }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-250/50' }}">
                                        {{ strtoupper($inv->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $inv->due_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-6 text-center text-slate-450">Aucune facture enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 5. PAYMENTS MODULE -->
    @elseif($moduleName === 'payments')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Registre des encaissements abonnements</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">ID Transaction</th>
                            <th class="px-5 py-3">Cabinet</th>
                            <th class="px-5 py-3">Montant</th>
                            <th class="px-5 py-3">Méthode</th>
                            <th class="px-5 py-3">Date paiement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-xs">
                        @forelse($payments as $pay)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 text-slate-500 font-bold">TXN-{{ str_pad((string)$pay->id, 8, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-5 py-3.5 font-sans font-bold text-slate-800">{{ $pay->invoice->tenant->name ?? 'Cabinet Insurio' }}</td>
                                <td class="px-5 py-3.5 text-slate-900 font-bold">{{ number_format($pay->amount, 2) }} DH</td>
                                <td class="px-5 py-3.5 font-sans text-slate-600">{{ ucfirst($pay->payment_method) }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $pay->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-slate-450">Aucun paiement encaissé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 6. DOMAINS MODULE -->
    @elseif($moduleName === 'domains')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Mappage des sous-domaines & DNS</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Domaine / Host</th>
                            <th class="px-5 py-3">Cabinet Propriétaire</th>
                            <th class="px-5 py-3 text-center">Vérification DNS</th>
                            <th class="px-5 py-3 text-center">Certificat SSL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium font-mono text-xs">
                        @foreach($tenants as $tenant)
                            @foreach($tenant->domains as $dom)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3.5 text-indigo-600 font-bold hover:underline">
                                        <a href="http://{{ $dom->domain }}" target="_blank">{{ $dom->domain }}</a>
                                    </td>
                                    <td class="px-5 py-3.5 font-sans font-bold text-slate-800">{{ $tenant->name }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ACTIF / VALIDE
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            SSL ACTIF
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 7. SUPPORT TICKETS MODULE -->
    @elseif($moduleName === 'tickets')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Demandes d'assistance techniques</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Sujet / Ticket</th>
                            <th class="px-5 py-3">Créateur</th>
                            <th class="px-5 py-3 text-center">Priorité</th>
                            <th class="px-5 py-3 text-center">Statut</th>
                            <th class="px-5 py-3">Créé le</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs">
                        @forelse($tickets as $t)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-900">{{ $t->subject }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800">{{ $t->creator_name }}</div>
                                    <div class="text-[10px] text-slate-450 font-mono">{{ $t->creator_email }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold {{ $t->priority === 'critical' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ strtoupper($t->priority) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ strtoupper($t->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 font-mono">{{ $t->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-slate-450">Aucun ticket ouvert.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 8. PLATFORM LOGS MODULE -->
    @elseif($moduleName === 'activity')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Historique d'activité système</span>
            </div>
            <div class="p-5 font-mono text-xs text-slate-600 space-y-3.5 max-h-[500px] overflow-y-auto">
                @forelse($activityLogs as $l)
                    <div class="flex items-start gap-3 border-b border-slate-100 pb-3">
                        <span class="text-indigo-600 font-bold">[{{ $l->created_at->format('d/m/Y H:i:s') }}]</span>
                        <div>
                            <span class="font-bold text-slate-800">{{ $l->action }}</span>
                            @if($l->details)
                                <div class="text-[10px] text-slate-450 mt-1 bg-slate-50 p-2 rounded-lg border border-slate-200/40">{{ $l->details }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-slate-400 py-6">Aucun log système enregistré.</div>
                @endforelse
            </div>
        </div>

    <!-- 9. FEATURE FLAGS MODULE -->
    @elseif($moduleName === 'feature-flags')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Contrôle des fonctionnalités SaaS</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Code Clé</th>
                            <th class="px-5 py-3">Description</th>
                            <th class="px-5 py-3 text-center">Globalement Actif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs font-mono">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">ai_copilot_assistant</td>
                            <td class="px-5 py-3.5 font-sans text-slate-500">Active le module d'intelligence artificielle sur l'espace d'agence.</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">INACTIF</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">whatsapp_renewal_automation</td>
                            <td class="px-5 py-3.5 font-sans text-slate-500">Déclenchement automatique des rappels d'échéances sur Whatsapp.</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">ACTIF</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 10. BACKUPS MODULE -->
    @elseif($moduleName === 'backups')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Sauvegardes de Sécurité</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Nom fichier</th>
                            <th class="px-5 py-3">Espace Stockage</th>
                            <th class="px-5 py-3">Taille</th>
                            <th class="px-5 py-3 text-center">État</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs font-mono">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">backup-2026-07-20-central.zip</td>
                            <td class="px-5 py-3.5 font-sans text-slate-500">Local (App Storage)</td>
                            <td class="px-5 py-3.5 text-slate-700">14.8 MB</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">RÉUSSI</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 11. WEBHOOKS MODULE -->
    @elseif($moduleName === 'webhooks')
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Webhooks sortants émis par Insurio Central</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-650">
                    <thead class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3">Événement</th>
                            <th class="px-5 py-3">Endpoint Cible</th>
                            <th class="px-5 py-3 text-center">Code Réponse</th>
                            <th class="px-5 py-3 text-center">État</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-xs font-mono">
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">tenant.created</td>
                            <td class="px-5 py-3.5 text-indigo-600">https://api.externalpartner.com/webhooks/insurio</td>
                            <td class="px-5 py-3.5 text-center text-slate-700">200</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">SUCCÈS</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- 12. MESSAGES TEMPLATES MODULE -->
    @elseif($moduleName === 'templates')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-850 text-sm">Modèle Relance E-mail (Standard)</h3>
                <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl text-xs font-mono text-slate-600 leading-relaxed whitespace-pre-line">
                    Sujet: Votre assurance automobile arrive à expiration bientôt.
                    
                    Bonjour &#123;&#123; $client->nom &#125;&#125;,
                    Votre contrat N° &#123;&#123; $contrat->numero_contrat &#125;&#125; chez la compagnie &#123;&#123; $contrat->compagnie->nom &#125;&#125; expire le &#123;&#123; $contrat->date_echeance &#125;&#125;. Pour renouveler votre assurance automobile, veuillez nous contacter.
                </div>
            </div>

            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-850 text-sm">Modèle Relance WhatsApp (Standard)</h3>
                <div class="bg-slate-50 border border-slate-200/60 p-4 rounded-xl text-xs font-mono text-slate-600 leading-relaxed whitespace-pre-line">
                    Bonjour &#123;&#123; $client->nom &#125;&#125;, votre contrat d'assurance automobile Insurio N° &#123;&#123; $contrat->numero_contrat &#125;&#125; arrive à échéance le &#123;&#123; $contrat->date_echeance &#125;&#125;. Contactez-nous pour le renouveler.
                </div>
            </div>
        </div>

    <!-- 13. INFRA MONITORING MODULE -->
    @elseif($moduleName === 'monitoring')
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-850 text-sm border-b border-slate-100 pb-2">Rapport de Performance Serveur</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs pt-2">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/40">
                    <span class="text-slate-400 block">Charge CPU moyenne (5m)</span>
                    <span class="text-xl font-bold text-slate-800 block mt-1 font-mono">1.84%</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/40">
                    <span class="text-slate-400 block">Utilisation Mémoire RAM</span>
                    <span class="text-xl font-bold text-slate-800 block mt-1 font-mono">456 MB / 4.0 GB</span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/40">
                    <span class="text-slate-400 block">Latence HTTP Moyenne</span>
                    <span class="text-xl font-bold text-emerald-600 block mt-1 font-mono">42 ms</span>
                </div>
            </div>
        </div>

    <!-- 14. DEFAULT MARKETING/OTHER MODULES -->
    @else
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4 text-center">
            <span class="text-3xl">🛠️</span>
            <h3 class="text-base font-bold text-slate-800">Module en cours de configuration</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto">Ce sous-module est en cours d'intégration dans l'infrastructure centrale. Les données de simulation et d'autorisation sont actives.</p>
        </div>
    @endif

</div>
@endsection
