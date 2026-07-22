<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Console Super Admin - Insurio Central')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

        <!-- Dynamic Custom Scrollbar Styling -->
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .sidebar-scrollbar::-webkit-scrollbar { width: 4px; }
            .sidebar-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .sidebar-scrollbar::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.4); border-radius: 9999px; }
            .sidebar-scrollbar:hover::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, 0.8); }
            .sidebar-scrollbar { scrollbar-width: thin; scrollbar-color: rgba(51, 65, 85, 0.4) transparent; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F6F8] text-slate-800" 
          x-data="{ 
              sidebarOpen: false, 
              sidebarCollapsed: localStorage.getItem('insurio_sidebar_collapsed') === 'true' 
          }" 
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('insurio_sidebar_collapsed', val))"
          @keydown.window.escape="sidebarOpen = false">

        <div class="flex h-screen overflow-hidden">
            <!-- DESKTOP SIDEBAR (280px Expanded / 72px Collapsed) -->
            <aside class="hidden lg:flex lg:flex-col bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300 transition-all duration-200 ease-in-out relative z-30"
                   :class="sidebarCollapsed ? 'w-[72px]' : 'w-[280px]'">
                
                <!-- HEADER (Logo & Title) -->
                <div class="h-16 flex items-center justify-between px-4 border-b border-[#1E293B] shrink-0">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="h-9 w-9 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center font-bold shrink-0 shadow-lg">
                            <svg class="h-5 w-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </div>
                        <span class="text-base font-extrabold text-white tracking-wide truncate" x-show="!sidebarCollapsed">
                            Insurio Central
                        </span>
                    </div>

                    <!-- Collapse Button -->
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-[#1E293B] transition-colors hidden lg:block">
                        <svg class="h-4 w-4 transform transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                <!-- User Profile Block -->
                <div class="p-3 mx-3 my-3 bg-[#1E293B]/60 rounded-xl border border-[#334155]/40 flex items-center gap-3 shrink-0">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-teal-500 to-indigo-600 flex items-center justify-center font-bold text-white text-xs shadow-md shrink-0">
                        SA
                    </div>
                    <div class="overflow-hidden" x-show="!sidebarCollapsed">
                        <div class="font-extrabold text-xs text-white truncate">{{ Auth::guard('platform')->user()->name ?? 'Super Admin' }}</div>
                        <div class="text-[10px] text-teal-400 font-bold uppercase tracking-wider">Console Central</div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-3 py-2 space-y-4 overflow-y-auto sidebar-scrollbar">

                    <!-- SECTION 1: PILOTAGE -->
                    <div class="space-y-1">
                        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
                            PILOTAGE SAAS
                        </div>

                        <!-- Console Centrale -->
                        <a href="{{ route('platform.dashboard') }}" 
                           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ Route::is('platform.dashboard') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                            <svg class="h-5 w-5 shrink-0 transition-colors {{ Route::is('platform.dashboard') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Console Centrale</span>
                        </a>

                        <!-- Cabinet & Agences -->
                        <a href="{{ route('platform.module', 'agencies') }}" 
                           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ (isset($moduleName) && $moduleName === 'agencies') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                            <svg class="h-5 w-5 shrink-0 transition-colors {{ (isset($moduleName) && $moduleName === 'agencies') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4" />
                            </svg>
                            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Cabinet & Agences</span>
                        </a>
                    </div>

                    <!-- SECTION 2: WHITE LABEL THEMES -->
                    <div class="space-y-1">
                        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
                            THEME ENGINE
                        </div>

                        <!-- Thèmes Website -->
                        <a href="{{ route('platform.themes') }}" 
                           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ Route::is('platform.themes') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                            <svg class="h-5 w-5 shrink-0 transition-colors {{ Route::is('platform.themes') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Thèmes Website White-Label</span>
                        </a>
                    </div>

                    <!-- SECTION 3: FACTURATION & INFRA -->
                    <div class="space-y-1">
                        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
                            FACTURATION & FINANCES
                        </div>

                        <!-- Charges -->
                        <a href="{{ route('platform.expenses.index') }}" 
                           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ Route::is('platform.expenses.index') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                            <svg class="h-5 w-5 shrink-0 transition-colors {{ Route::is('platform.expenses.index') ? 'text-teal-400' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Comptabilité & Charges</span>
                        </a>
                    </div>

                </nav>

                <!-- Logout Block -->
                <div class="p-3 border-t border-[#1E293B] bg-[#090D16] shrink-0">
                    <form method="POST" action="{{ route('platform.logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center text-xs font-semibold rounded-xl text-rose-400 hover:bg-rose-950/20 transition-all py-2.5"
                                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                            <svg class="h-5 w-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MOBILE NAV DRAWER -->
            <div x-show="sidebarOpen" x-cloak x-transition.opacity class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-40 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-[280px] bg-[#0F172A] border-r border-[#1E293B] z-50 transform transition-transform duration-200 lg:hidden flex flex-col"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="h-16 flex items-center justify-between px-4 border-b border-[#1E293B]">
                    <span class="text-base font-extrabold text-white">Insurio Central</span>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <nav class="p-3 space-y-2 text-xs font-bold text-slate-300">
                    <a href="{{ route('platform.dashboard') }}" class="block p-2.5 rounded-xl bg-[#1E293B]">Console Centrale</a>
                    <a href="{{ route('platform.themes') }}" class="block p-2.5 rounded-xl hover:bg-[#1E293B]">Thèmes Website White-Label</a>
                    <a href="{{ route('platform.expenses.index') }}" class="block p-2.5 rounded-xl hover:bg-[#1E293B]">Comptabilité & Charges</a>
                </nav>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden min-w-0">
                <!-- Top Header bar -->
                <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-10 shrink-0">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2 text-slate-500 text-xs font-bold">
                            <span class="text-slate-900 font-extrabold">Insurio Central</span>
                            <span>/</span>
                            <span class="text-teal-600">Super Admin Console</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Console Centrale Super Admin
                        </span>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F5F6F8]">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                            @yield('content')
                            {{ $slot ?? '' }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
