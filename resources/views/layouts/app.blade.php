<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Favicon -->
        <link class="js-favicon" rel="icon" type="image/x-icon" href="{{ tenant('favicon_path') ? asset('storage/' . tenant('favicon_path')) : asset('favicon.ico') }}">

        <!-- Dynamic White-Label Theme Styling -->
        <style>
            :root {
                --color-accent: {{ tenant('couleur_primaire') ?: '#0EA5A0' }};
                --color-accent-hover: {{ tenant('couleur_secondaire') ?: '#0D9488' }};
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F6F8] text-slate-800" x-data="{ sidebarOpen: false }">
        @if(session('impersonated_by_landlord'))
            <div class="bg-indigo-650 text-white text-xs font-semibold py-2 px-6 flex items-center justify-between shadow-sm relative z-50">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    <span>Vous êtes connecté en tant que <strong>{{ auth()->user()->name }}</strong> via la console Super Admin (Impersonation).</span>
                </div>
                <a href="http://{{ config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf' }}/super-admin/dashboard" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-md text-[10px] transition-all font-bold">
                    Retour console centrale
                </a>
            </div>
        @endif
        <div class="flex h-screen overflow-hidden">

            <!-- LEFT SIDEBAR: Premium Dark Slate Theme (like ShopifyManager) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
                <!-- Logo Block -->
                <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
                    @if(tenant('logo_path'))
                        <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-10 max-w-full object-contain mx-auto">
                    @else
                        <div class="flex items-center gap-2">
                            <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="text-lg font-bold text-white font-sans tracking-wide">{{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Profile Block -->
                <div class="p-4 mx-4 my-4 bg-[#1E293B]/50 rounded-xl border border-[#334155]/40 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-teal-650 flex items-center justify-center font-bold text-white text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">
                            @if(auth()->user()->hasRole('agency-admin'))
                                Administrateur
                            @elseif(auth()->user()->hasRole('responsable-succursale'))
                                Responsable
                            @elseif(auth()->user()->hasRole('agent-commercial'))
                                Commercial
                            @else
                                Collaborateur
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Navigation List -->
                @php
                    $enabledPages = json_decode(\App\Models\Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
                @endphp
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    @if(in_array('dashboard', $enabledPages) && !auth()->user()->hasRole('agent-commercial'))
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                            </svg>
                            Dashboard
                        </a>
                    @endif

                    <!-- Production Register -->
                    @if(in_array('automobile', $enabledPages))
                        <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('automobile.*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Production Assurance
                        </a>
                    @endif

                    <!-- Produits d'Assurance -->
                    @if(auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.products') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Produits
                        </a>
                    @endif

                    <!-- Clients (Particuliers) -->
                    <a href="{{ Route::has('admin.clients') ? route('admin.clients') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.clients') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Clients
                    </a>

                    <!-- Entreprises -->
                    <a href="{{ Route::has('admin.entreprises') ? route('admin.entreprises') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.entreprises') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Entreprises
                    </a>

                    <!-- Tâches Kanban -->
                    <a href="{{ Route::has('admin.tasks') ? route('admin.tasks') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.tasks') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Tâches Kanban
                    </a>

                    <!-- Succursales -->
                    @if(in_array('succursales', $enabledPages) && auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.succursales') ? route('admin.succursales') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.succursales') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Succursales
                        </a>
                    @endif

                    <!-- Employés -->
                    @if(in_array('employes', $enabledPages) && auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.employes') ? route('admin.employes') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.employes') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Collaborateurs
                        </a>
                    @endif

                    <!-- Validation Commissions -->
                    @if(in_array('commissions', $enabledPages) && auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.commissions') ? route('admin.commissions') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.commissions') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Commissions Admin
                        </a>
                    @endif

                    <!-- Charges / Dépenses -->
                    @if(in_array('charges', $enabledPages) && auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.charges') ? route('admin.charges') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.charges') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Dépenses & Charges
                        </a>
                    @endif

                    <!-- Agent Commissions -->
                    @if(auth()->user()->hasRole('agent-commercial'))
                        <a href="{{ Route::has('agent.commissions') ? route('agent.commissions') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('agent.commissions') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Mes Commissions
                        </a>
                    @endif

                    <!-- Paramètres Agence -->
                    @if(auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('settings') ? route('settings') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('settings') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Configuration
                        </a>
                    @endif
                </nav>

                <!-- Logout Block (At bottom) -->
                <div class="p-4 border-t border-[#1E293B] mt-auto">
                    <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-semibold rounded-xl text-rose-450 hover:bg-rose-950/20 transition-all">
                            <svg class="h-5 w-5 mr-3 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MOBILE NAV OVERLAY & HAMBURGER DRAWER -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0F172A]/70 z-40 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-64 bg-[#0F172A] border-r border-[#1E293B] z-50 transform -translate-x-full transition-transform duration-300 lg:hidden"  
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B]">
                    @if(tenant('logo_path'))
                        <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-10 max-w-full object-contain mx-auto">
                    @else
                        <div class="flex items-center gap-2">
                            <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                            <span class="text-lg font-bold text-white font-sans tracking-wide">{{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}</span>
                        </div>
                    @endif
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 mx-4 my-4 bg-[#1E293B]/50 rounded-xl border border-[#334155]/40 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-teal-600 flex items-center justify-center font-bold text-white text-xs">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-xs text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-[9px] text-slate-400 font-medium uppercase tracking-wider">Cabinet Member</div>
                    </div>
                </div>

                <nav class="px-4 space-y-1">
                    <!-- Dashboard -->
                    @if(!auth()->user()->hasRole('agent-commercial'))
                        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Dashboard</a>
                    @endif
                    <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Production Assurance</a>
                    @if(auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Produits</a>
                    @endif
                    <a href="{{ Route::has('admin.clients') ? route('admin.clients') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Clients</a>
                    <a href="{{ Route::has('admin.entreprises') ? route('admin.entreprises') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Entreprises</a>
                    @if(auth()->user()->hasRole('agency-admin'))
                        <a href="{{ Route::has('admin.succursales') ? route('admin.succursales') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Succursales</a>
                        <a href="{{ Route::has('admin.employes') ? route('admin.employes') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Collaborateurs</a>
                        <a href="{{ Route::has('admin.commissions') ? route('admin.commissions') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Commissions Admin</a>
                        <a href="{{ Route::has('settings') ? route('settings') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Configuration</a>
                    @endif
                </nav>
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
                        <!-- Search Box (Shopify style) -->
                        <div class="hidden md:flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 w-80">
                            <svg class="h-4 w-4 text-slate-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" placeholder="Rechercher contrat, client, matricule..." class="bg-transparent border-none outline-none text-xs w-full text-slate-700 placeholder-slate-400 p-0 focus:ring-0">
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Sync / Status badge -->
                        <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ tenant('name') ?? 'Agence Connectée' }}
                        </div>

                        <!-- Notification bell -->
                        <div class="relative">
                            <button class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F5F6F8]">
                    @if(function_exists('tenant') && tenant() && tenant()->getDaysRemaining() !== null && tenant()->getDaysRemaining() >= 0 && tenant()->getDaysRemaining() <= 7)
                        <div class="bg-amber-50 border-b border-amber-200 px-6 py-3 flex items-center justify-between text-amber-800 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Votre période d'abonnement expire dans <strong>{{ tenant()->getDaysRemaining() }} jour(s)</strong>. Veuillez contacter le support technique avant le {{ \Carbon\Carbon::parse(tenant()->subscription_end_date)->format('d/m/Y') }} pour éviter la suspension de votre accès.</span>
                            </div>
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
            
            <div class="hidden">
                <livewire:layout.navigation />
            </div>
        </div>
    </body>
</html>
