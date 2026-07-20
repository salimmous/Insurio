<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gérer le Cabinet - Insurio Central</title>
        <!-- Fonts: Plus Jakarta Sans for premium SaaS feeling -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- LEFT SIDEBAR: Premium Dark Slate Theme -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
                <!-- Logo Block -->
                <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-lg font-bold text-white tracking-wide">Insurio Central</span>
                    </div>
                </div>

                <!-- User Profile Block -->
                <div class="p-4 mx-4 my-4 bg-[#1E293B]/50 rounded-xl border border-[#334155]/40 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white text-sm">
                        S
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs text-white truncate">{{ Auth::guard('platform')->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Super Admin</div>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-400 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                        <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Console Centrale
                    </a>

                    <!-- Nouvelle Agence Link -->
                    <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-400 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                        <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Nouvelle Agence
                    </a>

                    <!-- Comptabilité Link -->
                    <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-400 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                        <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Comptabilité / Charges
                    </a>
                </nav>

                <!-- Logout Block -->
                <div class="p-4 border-t border-[#1E293B] mt-auto">
                    <form method="POST" action="{{ route('platform.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-semibold rounded-xl text-rose-455 hover:bg-rose-950/20 transition-all">
                            <svg class="h-5 w-5 mr-3 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MOBILE NAV OVERLAY & DRAWER -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0F172A]/70 z-40 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-64 bg-[#0F172A] border-r border-[#1E293B] z-50 transform -translate-x-full transition-transform duration-300 lg:hidden" 
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                        </svg>
                        <span class="text-lg font-bold text-white tracking-wide">Insurio Central</span>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="px-4 py-4 space-y-1">
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Console Centrale</a>
                    <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Nouvelle Agence</a>
                    <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Comptabilité / Charges</a>
                </nav>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header bar -->
                <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-6 z-10">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="font-bold text-slate-850 text-sm">Gestion de l'Abonnement Cabinet</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-1.5 bg-indigo-50 text-indigo-850 border border-indigo-200/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            Console Centrale Super Admin
                        </div>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F8FAFC]">
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            
                            <!-- Form Card: Rounded 3xl, shadow, modern border -->
                            <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-8">
                                <div class="border-b border-slate-100 pb-5 flex justify-between items-center">
                                    <div>
                                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-teal-650 bg-teal-50/60 px-2.5 py-1 rounded-md border border-teal-100">Insurio</span>
                                        <h1 class="text-xl font-extrabold text-slate-900 mt-2.5 tracking-tight">Paramètres du Cabinet</h1>
                                    </div>
                                    <a href="{{ route('platform.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-1">
                                        <span>←</span> Retour console
                                    </a>
                                </div>

                                @if($errors->any())
                                    <div class="bg-rose-50 border border-rose-150 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('platform.tenants.update', $tenant->id) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PUT')

                                    <!-- Section 1: Informations de Base -->
                                    <div class="space-y-6">
                                        <div class="flex items-center gap-2 border-b border-slate-50 pb-2">
                                            <span class="text-sm">🔑</span>
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Informations Agence</h3>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Identifiant Unique (Tenant ID)</label>
                                                <input type="text" value="{{ $tenant->id }}" disabled
                                                       class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono text-slate-500 cursor-not-allowed">
                                                <p class="text-[10px] text-slate-400 mt-2 font-medium">L'identifiant unique ne peut pas être modifié car il définit la base de données.</p>
                                            </div>

                                            <div>
                                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Nom du Cabinet / Agence</label>
                                                <input type="text" id="name" name="name" value="{{ old('name', $tenant->name) }}" required placeholder="Ex: AXA Assurance Maarif"
                                                       class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all placeholder-slate-400">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Statut de l'Accès Cabinet</label>
                                                <select id="status" name="status" required
                                                        class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all text-slate-700">
                                                    <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Actif (Accès autorisé)</option>
                                                    <option value="trial" {{ old('status', $tenant->status) == 'trial' ? 'selected' : '' }}>Essai / Démo (Accès autorisé)</option>
                                                    <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>Suspendu / Bloqué (Accès interdit)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="plan_id" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Plan d'Abonnement SaaS</label>
                                                <select id="plan_id" name="plan_id" required
                                                    class="w-full bg-slate-50 border border-slate-200 focus:bg-white rounded-xl px-3.5 py-2.5 text-xs font-semibold outline-none transition-all text-slate-700">
                                                    @foreach($plans as $p)
                                                        <option value="{{ $p->id }}" {{ old('plan_id', $tenant->plan_id) == $p->id ? 'selected' : '' }}>
                                                            {{ $p->name }} ({{ number_format($p->price, 0) }} DH/mois)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Section 2: Facturation & Paramètres Financiers -->
                                    <div class="space-y-6 pt-4">
                                        <div class="flex items-center gap-2 border-b border-slate-50 pb-2">
                                            <span class="text-sm">💰</span>
                                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Abonnement, Facturation & Domaine</h3>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="subscription_start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Date de début d'abonnement</label>
                                                <input type="date" id="subscription_start_date" name="subscription_start_date" value="{{ old('subscription_start_date', $tenant->subscription_start_date) }}"
                                                       class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all text-slate-700">
                                            </div>

                                            <div>
                                                <label for="subscription_end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Date de fin / Échéance</label>
                                                <input type="date" id="subscription_end_date" name="subscription_end_date" value="{{ old('subscription_end_date', $tenant->subscription_end_date) }}"
                                                       class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all text-slate-700">
                                                <p class="text-[10px] text-slate-400 mt-2 font-medium">Laisser vide pour un abonnement illimité.</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="rent_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Montant du Loyer Mensuel (DH)</label>
                                                <input type="number" step="0.01" min="0" id="rent_amount" name="rent_amount" value="{{ old('rent_amount', $tenant->rent_amount) }}" placeholder="Ex: 500"
                                                       class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm font-mono transition-all placeholder-slate-400">
                                                <p class="text-[10px] text-slate-400 mt-2 font-medium">Prix de la location de l'application SaaS par mois.</p>
                                            </div>

                                            <div>
                                                <label for="custom_domain" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Domaine Personnalisé (Optionnel)</label>
                                                <input type="text" id="custom_domain" name="custom_domain" value="{{ old('custom_domain', $customDomain) }}" placeholder="Ex: moncabinet.ma"
                                                       class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm font-mono transition-all placeholder-slate-400">
                                                <p class="text-[10px] text-slate-400 mt-2 font-medium">Pointez les DNS du client (CNAME/A record) vers la plateforme.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons: Premium look & Hover transitions -->
                                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                                        <a href="{{ route('platform.dashboard') }}" class="bg-slate-50 hover:bg-slate-100 text-slate-650 font-bold px-6 py-3 rounded-xl text-sm transition-all border border-slate-200/80 shadow-sm">
                                            Annuler
                                        </a>
                                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold px-8 py-3 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 hover:-translate-y-0.5">
                                            Enregistrer les modifications
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- [NEW] Succursales du Cabinet management card f Super Admin panel -->
                            <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-8 mt-8">
                                <div class="border-b border-slate-100 pb-5">
                                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-650 bg-indigo-50/60 px-2.5 py-1 rounded-md border border-indigo-100">Bureaux Locaux</span>
                                    <h2 class="text-lg font-extrabold text-slate-900 mt-2.5 tracking-tight">🏢 Succursales du Cabinet (Branches)</h2>
                                    <p class="text-xs text-slate-500 mt-1">Créez et supprimez directement les succursales associées à ce cabinet d'assurance.</p>
                                </div>

                                @if(session()->has('message') && str_contains(session('message'), 'succursale'))
                                    <div class="bg-emerald-50 border border-emerald-150 text-emerald-800 px-4 py-3 rounded-xl text-xs font-semibold">
                                        {{ session('message') }}
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    <!-- Left: Succursales List (2 cols) -->
                                    <div class="lg:col-span-2 space-y-4">
                                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-450 border-b border-slate-50 pb-2">Liste des Succursales Actives</h3>
                                        <div class="border border-slate-150 rounded-2xl overflow-hidden shadow-sm">
                                            <table class="w-full text-left text-xs text-slate-600">
                                                <thead class="bg-slate-50 border-b border-slate-150 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    <tr>
                                                        <th class="px-4 py-3">Code</th>
                                                        <th class="px-4 py-3">Nom Succursale</th>
                                                        <th class="px-4 py-3">Ville</th>
                                                        <th class="px-4 py-3">Téléphone</th>
                                                        <th class="px-4 py-3">Domaine</th>
                                                        <th class="px-4 py-3 text-right">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 text-xs">
                                                    @forelse($succursales as $suc)
                                                        <tr class="hover:bg-slate-50 transition-colors">
                                                            <td class="px-4 py-3 font-mono font-bold text-indigo-650">{{ $suc->code_succursale }}</td>
                                                            <td class="px-4 py-3 font-bold text-slate-800">{{ $suc->nom }}</td>
                                                            <td class="px-4 py-3 text-slate-550">{{ $suc->ville ?: 'N/A' }}</td>
                                                            <td class="px-4 py-3 text-slate-550 font-mono">{{ $suc->telephone ?: 'N/A' }}</td>
                                                            <td class="px-4 py-3 text-slate-550 font-mono">
                                                                @if($suc->domain)
                                                                    <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-[10px] border border-indigo-100">{{ $suc->domain }}</span>
                                                                @else
                                                                    <span class="text-slate-400">N/A</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <form action="{{ route('platform.tenants.destroy_succursale', [$tenant->id, $suc->id]) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de cette succursale ?');" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold">
                                                                        🗑️ Supprimer
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="p-6 text-center text-slate-400">
                                                                Aucune succursale enregistrée pour ce cabinet.
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Right: Form to Add Branch (1 col) -->
                                    <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">🏢 Ajouter une Succursale</h3>
                                        <form action="{{ route('platform.tenants.store_succursale', $tenant->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label for="suc_nom" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Nom de succursale</label>
                                                <input type="text" id="suc_nom" name="nom" required placeholder="Ex: Bureau Rabat Agdal"
                                                       class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs transition-all placeholder-slate-400">
                                            </div>

                                            <div>
                                                <label for="suc_code" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Code Succursale</label>
                                                <input type="text" id="suc_code" name="code_succursale" required placeholder="Ex: SUC-RBT"
                                                       class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs font-mono transition-all placeholder-slate-400">
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label for="suc_ville" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Ville</label>
                                                    <input type="text" id="suc_ville" name="ville" placeholder="Rabat"
                                                           class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs transition-all placeholder-slate-400">
                                                </div>
                                                <div>
                                                    <label for="suc_tel" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Téléphone</label>
                                                    <input type="text" id="suc_tel" name="telephone" placeholder="0522..."
                                                           class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs transition-all placeholder-slate-400">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="suc_adr" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Adresse</label>
                                                <input type="text" id="suc_adr" name="adresse" placeholder="Avenue de France..."
                                                       class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs transition-all placeholder-slate-400">
                                            </div>

                                            <div>
                                                <label for="suc_domain" class="block text-[10px] font-bold uppercase tracking-wider text-slate-455 mb-1.5">Domaine Personnalisé (Optionnel)</label>
                                                <input type="text" id="suc_domain" name="domain" placeholder="Ex: rabatsuc.ma"
                                                       class="w-full bg-white border border-slate-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-3.5 py-2.5 outline-none text-xs font-mono transition-all placeholder-slate-400">
                                                <p class="text-[9px] text-slate-400 mt-1 font-medium">Pour affecter un nom de domaine exclusif à cette succursale.</p>
                                            </div>

                                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20">
                                                Déployer la Succursale
                                            </button>
                                        </form>
                                    </div>
                                </div>

                        </div>
                    </div>
                </main>
            </div>

        </div>
    </body>
</html>
