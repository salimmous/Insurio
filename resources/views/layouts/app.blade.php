<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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

        <!-- Dynamic White-Label Theme & Custom Scrollbar Styling -->
        <style>
            :root {
                --color-accent: {{ tenant('couleur_primaire') ?: '#0EA5A0' }};
                --color-accent-hover: {{ tenant('couleur_secondaire') ?: '#0D9488' }};
            }
            /* Sleek thin transparent scrollbar for sidebar */
            .sidebar-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .sidebar-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(51, 65, 85, 0.4);
                border-radius: 9999px;
            }
            .sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
                background: rgba(100, 116, 139, 0.8);
            }
            .sidebar-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: rgba(51, 65, 85, 0.4) transparent;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased bg-[#0B0F19] text-slate-100" 
          x-data="{ 
              sidebarOpen: false, 
              sidebarCollapsed: localStorage.getItem('insurio_sidebar_collapsed') === 'true' 
          }" 
          x-init="$watch('sidebarCollapsed', val => localStorage.setItem('insurio_sidebar_collapsed', val))"
          @keydown.window.escape="sidebarOpen = false">
        
        @if(session('impersonated_by_landlord'))
            <div class="bg-indigo-600 text-white text-xs font-semibold py-2 px-6 flex items-center justify-between shadow-md relative z-50">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span>
                    <span>Vous êtes connecté en tant que <strong>{{ auth()->user()->name }}</strong> via la console Support Technique (Impersonation).</span>
                </div>
                <a href="http://{{ config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf' }}/super-admin/dashboard" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-md text-[10px] transition-all font-bold">
                    Retour Console Support
                </a>
            </div>
        @endif

        <!-- GLOBAL ENTERPRISE SAAS SHELL (Stripe / Linear / Vercel Inspired) -->
        <div class="flex h-screen w-screen overflow-hidden p-3 lg:p-4 gap-6">

            <!-- 1. DESKTOP FLOATING SIDEBAR (Visually Separated with 24px Gap) -->
            <aside class="hidden lg:flex lg:flex-col bg-[#0F172A] border border-slate-800/80 rounded-[20px] flex-shrink-0 text-slate-300 transition-all duration-200 ease-in-out relative z-30 shadow-2xl overflow-hidden"
                   :class="sidebarCollapsed ? 'w-[72px]' : 'w-[260px]'">
                @include('layouts.partials.sidebar-content')
            </aside>

            <!-- 2. MOBILE NAV BACKDROP OVERLAY -->
            <div x-show="sidebarOpen" 
                 x-cloak
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-[#0F172A]/80 backdrop-blur-sm z-40 lg:hidden" 
                 @click="sidebarOpen = false">
            </div>

            <!-- 3. MOBILE OFF-CANVAS DRAWER -->
            <aside class="fixed top-0 bottom-0 left-0 w-[280px] bg-[#0F172A] border-r border-[#1E293B] z-50 transform transition-transform duration-200 ease-in-out lg:hidden flex flex-col shadow-2xl"  
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('layouts.partials.sidebar-content')
            </aside>

            <!-- 4. MAIN CONTENT ROUNDED CONTAINER (Container radius: 20px, 24px Content Padding, Subtle Shadow) -->
            <div class="flex-1 flex flex-col min-w-0 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-[20px] shadow-2xl shadow-slate-950/20 overflow-hidden relative">
                
                <!-- Integrated Top Header Bar -->
                <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between px-6 shrink-0 z-10">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Hamburger Toggle -->
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 dark:hover:text-white lg:hidden p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Desktop Sidebar Collapse Toggle Button -->
                        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                                class="hidden lg:flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                :title="sidebarCollapsed ? 'Agrandir le menu' : 'Réduire le menu'">
                            <svg class="h-5 w-5 transform transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Global Search -->
                        <div class="hidden md:flex items-center w-80">
                            <livewire:admin.global-search />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Agency Name Status Badge -->
                        <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60 rounded-full px-3.5 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ tenant('name') ?? 'Agence Connectée' }}
                        </div>

                        <!-- Notification Bell -->
                        <livewire:admin.notification-center />
                    </div>
                </header>

                <!-- Independent Scrollable Main Content Area (24px Padding = p-6) -->
                <main class="flex-1 overflow-y-auto bg-[#F8FAFC] dark:bg-slate-950 p-6">
                    @if(function_exists('tenant') && tenant() && tenant()->getDaysRemaining() !== null && tenant()->getDaysRemaining() >= 0 && tenant()->getDaysRemaining() <= 7)
                        <div class="mb-6 bg-amber-50 dark:bg-amber-950/50 border border-amber-200 dark:border-amber-900 rounded-2xl px-6 py-3 flex items-center justify-between text-amber-800 dark:text-amber-200 text-xs font-medium shadow-xs">
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
        </div>

        <!-- Global Modals & Command Palette -->
        <livewire:global-command-palette />
        <livewire:admin.super-admin-support-panel />
    </body>
</html>
