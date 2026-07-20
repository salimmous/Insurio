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
        <div class="flex h-screen overflow-hidden">            <!-- LEFT SIDEBAR: Premium Dark Slate Theme (like ShopifyManager) -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-350">
                @include('layouts.partials.sidebar-content')
            </aside>

            <!-- MOBILE NAV OVERLAY & HAMBURGER DRAWER -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0F172A]/70 z-40 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-64 bg-[#0F172A] border-r border-[#1E293B] z-50 transform -translate-x-full transition-transform duration-300 lg:hidden"  
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('layouts.partials.sidebar-content')
                <button @click="sidebarOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white z-50">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
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
                        <!-- Search Box — Phase 3 Global Search -->
                        <div class="hidden md:flex items-center w-80">
                            <livewire:admin.global-search />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Sync / Status badge -->
                        <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ tenant('name') ?? 'Agence Connectée' }}
                        </div>

                        <!-- Notification Bell — Phase 3 Notification Center -->
                        <livewire:admin.notification-center />
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
        <livewire:global-command-palette />
    </body>
</html>
