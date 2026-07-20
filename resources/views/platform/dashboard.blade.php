<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Console Super Admin - Insurio</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F6F8] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- LEFT SIDEBAR: Premium Dark Slate Theme (like ShopifyManager) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
                <!-- Logo Block -->
                <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-lg font-bold text-white font-sans tracking-wide">Insurio Central</span>
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
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl bg-[#1E293B] text-white">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-semibold rounded-xl text-rose-450 hover:bg-rose-950/20 transition-all">
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
                        <span class="text-lg font-bold text-white font-sans tracking-wide">Insurio Central</span>
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
                </nav>>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header bar -->
                <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-10">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- Search Box -->
                        <div class="hidden md:flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 w-80">
                            <svg class="h-4 w-4 text-slate-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Rechercher agence, email, sous-domaine..." class="bg-transparent border-none outline-none text-xs w-full text-slate-700 placeholder-slate-400 p-0 focus:ring-0">
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-1.5 bg-indigo-50 text-indigo-800 border border-indigo-200/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            Console Centrale Super Admin
                        </div>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F5F6F8]">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                            <!-- Top Filter Row -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                                <div class="flex flex-wrap gap-2 items-center">
                                    <span class="text-xs font-bold text-slate-400 mr-2 uppercase tracking-wider">Supervision:</span>
                                    <button class="px-3 py-1.5 text-xs font-semibold rounded-xl transition-all text-slate-650 hover:bg-slate-50">Aujourd'hui</button>
                                    <button class="px-3 py-1.5 text-xs font-semibold rounded-xl transition-all text-slate-650 hover:bg-slate-50">7 Jours</button>
                                    <button class="px-3 py-1.5 text-xs font-semibold rounded-xl transition-all bg-indigo-50 text-indigo-700 font-bold border border-indigo-150">30 Jours</button>
                                    <button class="px-3 py-1.5 text-xs font-semibold rounded-xl transition-all text-slate-650 hover:bg-slate-50">Historique complet</button>
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono font-bold">
                                    Mise à jour: {{ now()->format('H:i:s') }} (Casablanca)
                                </div>
                            </div>

                            <!-- Banner & Main action -->
                            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div>
                                    <h1 class="text-xl font-bold text-slate-800">Console de Supervision Centrale</h1>
                                    <p class="text-xs text-slate-500 mt-1">Gérez vos agences clientes, suivez les activations, supervisez les bases de données et onboardez de nouveaux cabinets.</p>
                                </div>
                                <a href="{{ route('platform.tenants.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Nouvelle Agence
                                </a>
                            </div>

                            @if(session()->has('message'))
                                <div class="bg-emerald-50 border border-emerald-250/60 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold">
                                    {{ session('message') }}
                                </div>
                            @endif

                            @if($errors->has('error'))
                                <div class="bg-rose-50 border border-rose-250/60 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                                    {{ $errors->first('error') }}
                                </div>
                            @endif

                            <!-- Stats Row (Landlord version) -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Card 1 -->
                                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 transition-all hover:shadow-md">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">TOTAL AGENCES</span>
                                    <div class="text-2xl font-black text-teal-650 font-mono my-1">{{ $tenants->count() }}</div>
                                    <span class="text-[10px] text-slate-400 font-medium">Instanciées sur la plateforme</span>
                                </div>
                                <!-- Card 2 -->
                                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 transition-all hover:shadow-md">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">AGENCES ACTIVES</span>
                                    <div class="text-2xl font-black text-indigo-600 font-mono my-1">{{ $tenants->where('status', 'active')->count() }}</div>
                                    <span class="text-[10px] text-slate-400 font-medium">En cours d'abonnement actif</span>
                                </div>
                                <!-- Card 3 -->
                                <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-32 transition-all hover:shadow-md">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">ACTIVITÉS CONSOLE (24H)</span>
                                    <div class="text-2xl font-black text-amber-600 font-mono my-1">{{ $logs->count() }}</div>
                                    <span class="text-[10px] text-slate-400 font-medium">Opérations d'administration effectuées</span>
                                </div>
                            </div>

                            <!-- Main workspace: left table, right logs (Stock Alert style) -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Left Table (2 Cols) -->
                                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                        <h2 class="font-bold text-slate-800 text-sm">Cabinets d'Assurance Enregistrés</h2>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider font-mono">Total: {{ $tenants->count() }}</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs text-slate-650 min-w-[950px]" style="min-width: 950px;">
                                            <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                <tr>
                                                    <th class="px-5 py-4">ID/Code</th>
                                                    <th class="px-5 py-4">Nom Agence</th>
                                                    <th class="px-5 py-4">Abonnement & Loyer</th>
                                                    <th class="px-5 py-4">Production Active</th>
                                                    <th class="px-5 py-4">Stats Base</th>
                                                    <th class="px-5 py-4 text-center">Statut / Plan</th>
                                                    <th class="px-5 py-4 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 font-medium text-xs">
                                                @forelse($tenants as $tenant)
                                                    <tr class="hover:bg-slate-50 transition-colors">
                                                        <!-- Tenant ID -->
                                                        <td class="px-5 py-4 font-mono font-bold text-teal-700">{{ $tenant->id }}</td>
                                                        
                                                        <!-- Tenant Name and Domains -->
                                                        <td class="px-5 py-4">
                                                            <div class="font-bold text-slate-800 text-sm tracking-tight">{{ $tenant->name }}</div>
                                                            <div class="text-[11px] font-mono mt-1 space-y-1">
                                                                <div class="flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 transition-colors">
                                                                    <span>🌐</span>
                                                                    <a href="http://{{ $tenant->domains->first()?->domain }}" target="_blank" class="hover:underline font-semibold">
                                                                        {{ $tenant->domains->first()?->domain }}
                                                                    </a>
                                                                </div>
                                                                @php
                                                                    $subdomainName = $tenant->id . '.' . (config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf');
                                                                    $customDomRecord = $tenant->domains->where('domain', '!=', $subdomainName)->first();
                                                                @endphp
                                                                @if($customDomRecord)
                                                                    <div class="flex items-center gap-1.5 text-emerald-600 hover:text-emerald-800 transition-colors">
                                                                        <span class="text-xs">🏷️</span>
                                                                        <a href="http://{{ $customDomRecord->domain }}" target="_blank" class="hover:underline font-bold">
                                                                            {{ $customDomRecord->domain }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        
                                                        <!-- Rent & Subscription Info -->
                                                        <td class="px-5 py-4 whitespace-nowrap" style="white-space: nowrap;">
                                                            <div class="font-bold text-slate-800 text-xs whitespace-nowrap" style="white-space: nowrap;">
                                                                @if($tenant->rent_amount)
                                                                    {{ number_format($tenant->rent_amount, 2) }} DH <span class="text-[10px] text-slate-400 font-normal">/ mois</span>
                                                                @else
                                                                    <span class="text-slate-400 font-semibold bg-slate-50 px-2 py-0.5 rounded border border-slate-150 text-[10px] whitespace-nowrap" style="white-space: nowrap;">Gratuit / Aucun</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-[10px] text-slate-550 font-medium font-sans mt-1.5 space-y-0.5 whitespace-nowrap" style="white-space: nowrap;">
                                                                @if($tenant->subscription_end_date)
                                                                    <div class="text-slate-400 font-mono text-[9px] whitespace-nowrap" style="white-space: nowrap;">Fin: {{ \Carbon\Carbon::parse($tenant->subscription_end_date)->format('d/m/Y') }}</div>
                                                                    @php $remaining = $tenant->getDaysRemaining(); @endphp
                                                                    @if($remaining !== null)
                                                                        @if($remaining <= 0)
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200 mt-1 whitespace-nowrap" style="white-space: nowrap;">⚠️ Expiré ({{ abs($remaining) }} j)</span>
                                                                        @else
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-250 mt-1 whitespace-nowrap" style="white-space: nowrap;">✅ Actif ({{ $remaining }} j)</span>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <div class="text-slate-400 font-mono text-[9px] whitespace-nowrap" style="white-space: nowrap;">Abonnement Illimité</div>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <!-- CA & Internal Commissions -->
                                                        <td class="px-5 py-4 font-sans text-xs whitespace-nowrap" style="white-space: nowrap;">
                                                            <div class="whitespace-nowrap" style="white-space: nowrap;">
                                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">CA :</span>
                                                                <span class="font-bold text-slate-800 font-mono text-[11px] whitespace-nowrap" style="white-space: nowrap;">{{ number_format($tenant->stats['primes'] ?? 0, 2) }} DH</span>
                                                            </div>
                                                            <div class="whitespace-nowrap mt-1" style="white-space: nowrap;">
                                                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Comms :</span>
                                                                <span class="font-bold text-indigo-650 font-mono text-[11px] whitespace-nowrap" style="white-space: nowrap;">{{ number_format($tenant->stats['commissions'] ?? 0, 2) }} DH</span>
                                                            </div>
                                                        </td>

                                                        <!-- Database Metrics -->
                                                        <td class="px-5 py-4">
                                                            <div class="flex items-center gap-2">
                                                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-xl text-slate-700" title="Clients">
                                                                    <span class="text-xs" style="font-size: 13px;">👥</span>
                                                                    <span class="font-bold font-mono text-slate-800 text-xs">{{ $tenant->stats['clients'] ?? 0 }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-xl text-slate-700" title="Contrats">
                                                                    <span class="text-xs" style="font-size: 13px;">📄</span>
                                                                    <span class="font-bold font-mono text-slate-800 text-xs">{{ $tenant->stats['contrats'] ?? 0 }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-xl text-slate-700" title="Employés">
                                                                    <span class="text-xs" style="font-size: 13px;">👔</span>
                                                                    <span class="font-bold font-mono text-slate-800 text-xs">{{ $tenant->stats['employes'] ?? 0 }}</span>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <!-- Plan and Subscription Status -->
                                                        <td class="px-5 py-4 text-center">
                                                            <div class="flex flex-col items-center gap-1.5">
                                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                    {{ $tenant->plan }}
                                                                </span>
                                                                
                                                                @if($tenant->status === 'suspended' || $tenant->isExpired())
                                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                                                        Suspendu
                                                                    </span>
                                                                @elseif($tenant->status === 'trial')
                                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-250">
                                                                        Essai
                                                                    </span>
                                                                @else
                                                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-250">
                                                                        Actif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <!-- Actions -->
                                                        <td class="px-5 py-4 text-right">
                                                            <div class="inline-flex items-center gap-1.5">
                                                                <!-- Impersonate -->
                                                                <a href="{{ route('platform.tenants.impersonate', $tenant->id) }}" class="bg-teal-50 hover:bg-teal-600 text-teal-700 hover:text-white border border-teal-200/60 font-bold px-2.5 py-1.5 rounded-xl transition-all text-[11px] shadow-sm flex items-center gap-1">
                                                                    <span>🔑 Entrer</span>
                                                                </a>
                                                                
                                                                <!-- Edit -->
                                                                <a href="{{ route('platform.tenants.edit', $tenant->id) }}" class="bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white border border-indigo-200/60 font-bold px-2.5 py-1.5 rounded-xl transition-all text-[11px] shadow-sm flex items-center gap-1">
                                                                    <span>⚙️ Gérer</span>
                                                                </a>

                                                                <!-- Delete -->
                                                                <form action="{{ route('platform.tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette agence et toutes ses bases de données ?');" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200/60 font-bold px-2.5 py-1.5 rounded-xl transition-all text-[11px] shadow-sm">
                                                                        🗑️ Suppr
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="px-5 py-8 text-center text-slate-400">Aucune agence déployée.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Right Logs Sidebar (Stock Alert style) -->
                                <div class="lg:col-span-1">
                                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                                        <div class="border-b border-slate-100 pb-3 flex justify-between items-center bg-slate-50/50 -m-5 p-5 rounded-t-2xl">
                                            <h3 class="font-bold text-slate-800 text-sm">Journal d'activité</h3>
                                            <span class="text-[9px] bg-indigo-50 text-indigo-700 border border-indigo-250/60 font-bold px-2 py-0.5 rounded-lg uppercase tracking-wide">CONSOLE</span>
                                        </div>

                                        <div class="space-y-3.5 max-h-[450px] overflow-y-auto pr-1 pt-2">
                                            @forelse($logs as $log)
                                                <div class="bg-slate-50 border border-slate-200/60 p-3.5 rounded-xl space-y-1.5 transition-all hover:bg-slate-100/60">
                                                    <p class="text-xs text-slate-700 font-semibold leading-relaxed">{{ $log->description }}</p>
                                                    <div class="flex justify-between items-center text-[9px] text-slate-400 font-mono font-bold">
                                                        <span>{{ $log->created_at->diffForHumans() }}</span>
                                                        <span class="bg-slate-200/80 text-slate-600 px-1 rounded">{{ $log->ip_address }}</span>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-xs text-slate-400 text-center py-4">Aucune activité enregistrée.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </main>
            </div>

        </div>
    </body>
</html>
