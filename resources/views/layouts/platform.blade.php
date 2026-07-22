<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Console Super Admin - Insurio')</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- LEFT SIDEBAR: Premium Stripe/Shopify Dark Slate Theme -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
                <!-- Logo Block -->
                <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2".5 d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-lg font-bold text-white font-sans tracking-wide">Insurio Central</span>
                    </div>
                </div>

                <!-- User Profile Block -->
                <div class="p-4 mx-4 my-4 bg-[#1E293B]/50 rounded-xl border border-[#334155]/40 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-teal-650 flex items-center justify-center font-bold text-white text-sm">
                        SA
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs text-white truncate">{{ Auth::guard('platform')->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Super Admin</div>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto pb-4 scrollbar-none">
                    <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-3 pb-1">PILOTAGE</span>
                    
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ Route::is('platform.dashboard') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">📊</span> Console Centrale
                    </a>

                    <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ Route::is('platform.tenants.create') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">➕</span> Nouvelle Agence
                    </a>

                    <a href="{{ route('platform.themes') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ Route::is('platform.themes') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🎨</span> Engine Thèmes Web
                    </a>

                    <a href="{{ route('platform.module', 'agencies') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'agencies') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🏢</span> Cabinet & Agences
                    </a>

                    <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-3 pb-1">FACTURATION & PLANS</span>

                    <a href="{{ route('platform.module', 'subscriptions') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'subscriptions') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🔁</span> Abonnements
                    </a>

                    <a href="{{ route('platform.module', 'plans') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'plans') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🎟️</span> Plans Tarifaires
                    </a>

                    <a href="{{ route('platform.module', 'invoices') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'invoices') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🧾</span> Factures Agences
                    </a>

                    <a href="{{ route('platform.module', 'payments') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'payments') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">💳</span> Paiements Reçus
                    </a>

                    <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-3 pb-1">RÉSEAU & INFRA</span>

                    <a href="{{ route('platform.module', 'domains') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'domains') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🌐</span> Domaines & DNS
                    </a>

                    <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ Route::is('platform.expenses.index') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">💰</span> Charges Plateforme
                    </a>

                    <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-3 pb-1">SUPPORT & LOGS</span>

                    <a href="{{ route('platform.module', 'tickets') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'tickets') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🎫</span> Tickets de Support
                    </a>

                    <a href="{{ route('platform.module', 'activity') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'activity') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">📜</span> Logs Plateforme
                    </a>

                    <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-3 pb-1">MARKETING & CONFIGS</span>

                    <a href="{{ route('platform.module', 'marketing') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'marketing') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">📣</span> Marketing & Affiliés
                    </a>

                    <a href="{{ route('platform.module', 'feature-flags') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'feature-flags') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🚩</span> Feature Flags
                    </a>

                    <a href="{{ route('platform.module', 'backups') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'backups') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">💾</span> Sauvegardes
                    </a>

                    <a href="{{ route('platform.module', 'webhooks') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'webhooks') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🪝</span> Webhooks API
                    </a>

                    <a href="{{ route('platform.module', 'templates') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'templates') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">✉️</span> Modèles Whatsapp/SMS
                    </a>

                    <a href="{{ route('platform.module', 'monitoring') }}" class="flex items-center px-3 py-2 text-xs font-semibold rounded-lg transition-all {{ (isset($moduleName) && $moduleName === 'monitoring') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/30 hover:text-white' }}">
                        <span class="mr-3">🚦</span> Monitoring & Health
                    </a>
                </nav>

                <!-- Logout Block -->
                <div class="p-4 border-t border-[#1E293B]/40 mt-auto bg-[#090D16]">
                    <form method="POST" action="{{ route('platform.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-semibold rounded-lg text-rose-400 hover:bg-rose-950/20 transition-all">
                            <span class="mr-3">🔌</span> Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MOBILE NAV OVERLAY & DRAWER -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0B0F19]/80 z-45 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-64 bg-[#0B0F19] border-r border-[#1E293B]/60 z-50 transform -translate-x-full transition-transform duration-300 lg:hidden" 
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B]/40">
                    <div class="flex items-center gap-2">
                        <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                        </svg>
                        <span class="text-base font-extrabold text-white tracking-tight">Insurio Central</span>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="px-4 py-4 space-y-1">
                    <a href="{{ route('platform.dashboard') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">📊 Console Centrale</a>
                    <a href="{{ route('platform.module', 'agencies') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">🏢 Cabinet & Agences</a>
                    <a href="{{ route('platform.module', 'subscriptions') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">🔁 Abonnements</a>
                    <a href="{{ route('platform.module', 'plans') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">🎟️ Plans Tarifaires</a>
                    <a href="{{ route('platform.module', 'invoices') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">🧾 Factures Agences</a>
                    <a href="{{ route('platform.module', 'payments') }}" class="block px-3 py-2 text-xs font-semibold rounded-lg text-slate-350 hover:bg-[#1E293B]">💳 Paiements Reçus</a>
                </nav>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header bar -->
                <header class="h-16 bg-white border-b border-slate-250/70 flex items-center justify-between px-6 z-10">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <!-- Command Palette Trigger hint -->
                        <div class="hidden md:flex items-center text-slate-400 text-xs font-semibold gap-1 bg-slate-100/70 border border-slate-200/80 px-3 py-1.5 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors">
                            <span>🔍 Recherche rapide</span>
                            <span class="bg-white border border-slate-200 rounded px-1 text-[10px] font-mono shadow-sm">Ctrl+K</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-1.5 bg-[#ECFDF5] text-[#065F46] border border-[#A7F3D0]/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Console Centrale Super Admin
                        </div>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F8FAFC]">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                            @yield('content')
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <livewire:global-command-palette />
    </body>
</html>
