<!-- 1. FIXED HEADER (Logo & Collapse Toggle) -->
<div class="h-16 flex items-center justify-between px-4 border-b border-[#1E293B] shrink-0">
    <div class="flex items-center gap-3 overflow-hidden">
        @if(tenant('logo_path'))
            <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-9 max-w-full object-contain shrink-0">
        @else
            <div class="h-9 w-9 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center font-bold shrink-0">
                <svg class="h-5 w-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="text-base font-bold text-white tracking-wide truncate" x-show="!sidebarCollapsed">
                {{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}
            </span>
        @endif
    </div>

    <!-- Mobile Close Drawer Button -->
    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- 2. SCROLLABLE NAVIGATION AREA -->
@php
    $enabledPages = json_decode(\App\Models\Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
@endphp
<nav class="flex-1 px-3 py-3 space-y-5 overflow-y-auto sidebar-scrollbar">

    <!-- Dashboard -->
    @if(in_array('dashboard', $enabledPages) && !auth()->user()->hasRole('agent-commercial'))
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('dashboard') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Dashboard</span>

            <!-- Tooltip for Collapsed Mode -->
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Dashboard
            </div>
        </a>
    @endif

    <!-- SECTION: CRM -->
    <div class="space-y-1">
        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
            CRM
        </div>

        <!-- Clients -->
        <a href="{{ Route::has('admin.clients') ? route('admin.clients') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.clients*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.clients*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Clients</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Clients
            </div>
        </a>

        <!-- Entreprises -->
        <a href="{{ Route::has('admin.entreprises') ? route('admin.entreprises') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.entreprises*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.entreprises*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Entreprises</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Entreprises
            </div>
        </a>

        <!-- Communications -->
        <a href="{{ Route::has('admin.communications') ? route('admin.communications') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.communications*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.communications*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Communications</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Communications
            </div>
        </a>

        <!-- Produits -->
        @if(auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.products*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.products*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Produits</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Produits
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: ASSURANCE (INSURANCE) -->
    <div class="space-y-1">
        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
            Assurance
        </div>

        <!-- Production Assurance -->
        @if(in_array('automobile', $enabledPages))
            <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('automobile.*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('automobile.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Production Assurance</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Production Assurance
                </div>
            </a>
        @endif

        <!-- Dossiers & Sinistres -->
        <a href="{{ Route::has('admin.dossiers') ? route('admin.dossiers') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.dossiers*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.dossiers*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Dossiers & Sinistres</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Dossiers & Sinistres
            </div>
        </a>

        <!-- Coffre-fort Documents -->
        <a href="{{ Route::has('admin.vault') ? route('admin.vault') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.vault*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.vault*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Coffre-fort Documents</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Coffre-fort Documents
            </div>
        </a>

        <!-- Assureurs -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
            <a href="{{ Route::has('admin.compagnies') ? route('admin.compagnies') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.compagnies*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.compagnies*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Assureurs</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Assureurs
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: FINANCE -->
    <div class="space-y-1">
        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
            Finance
        </div>

        <!-- Centre de Paiements -->
        <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.payments.center*') && request('tab') !== 'cheques' && !request()->routeIs('admin.payments.workspace*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.payments.center*') && request('tab') !== 'cheques' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Centre de Paiements</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Centre de Paiements
            </div>
        </a>

        <!-- Chèques -->
        <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') . '?tab=cheques' : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.payments.center*') && request('tab') === 'cheques' ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.payments.center*') && request('tab') === 'cheques' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Chèques</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Chèques
            </div>
        </a>

        <!-- Dépenses -->
        @if(in_array('charges', $enabledPages) && auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.charges') ? route('admin.charges') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.charges*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.charges*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Dépenses & Charges</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Dépenses & Charges
                </div>
            </a>
        @endif

        <!-- Commissions Admin -->
        @if(in_array('commissions', $enabledPages) && auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.commissions') ? route('admin.commissions') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.commissions*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.commissions*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Commissions Admin</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Commissions Admin
                </div>
            </a>
        @endif

        <!-- Agent Commissions -->
        @if(auth()->user()->hasRole('agent-commercial'))
            <a href="{{ Route::has('agent.commissions') ? route('agent.commissions') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('agent.commissions*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('agent.commissions*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Mes Commissions</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Mes Commissions
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: ORGANISATION -->
    @if(auth()->user()->hasRole('agency-admin'))
        <div class="space-y-1">
            <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
                Organisation
            </div>

            <!-- Succursales -->
            @if(in_array('succursales', $enabledPages))
                <a href="{{ Route::has('admin.succursales') ? route('admin.succursales') : '#' }}" 
                   class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.succursales*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.succursales*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-3 truncate" x-show="!sidebarCollapsed">Succursales</span>
                    <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                        Succursales
                    </div>
                </a>
            @endif

            <!-- Collaborateurs -->
            @if(in_array('employes', $enabledPages))
                <a href="{{ Route::has('admin.employes') ? route('admin.employes') : '#' }}" 
                   class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.employes*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                    <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.employes*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="ml-3 truncate" x-show="!sidebarCollapsed">Collaborateurs</span>
                    <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                        Collaborateurs
                    </div>
                </a>
            @endif
        </div>
    @endif

    <!-- SECTION: OPÉRATIONS -->
    <div class="space-y-1">
        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
            Opérations
        </div>

        <!-- Tâches Kanban -->
        <a href="{{ Route::has('admin.tasks') ? route('admin.tasks') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.tasks*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.tasks*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Tâches Kanban</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Tâches Kanban
            </div>
        </a>

        <!-- Agenda & Calendrier -->
        <a href="{{ Route::has('admin.agenda') ? route('admin.agenda') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.agenda*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.agenda*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Agenda & Calendrier</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Agenda & Calendrier
            </div>
        </a>

        <!-- Journal d'activité -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
            <a href="{{ Route::has('admin.activity-timeline') ? route('admin.activity-timeline') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.activity-timeline*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.activity-timeline*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Journal d'activité</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Journal d'activité
                </div>
            </a>
        @endif

        <!-- Importation Excel -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
            <a href="{{ Route::has('admin.import-manager') ? route('admin.import-manager') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.import-manager*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.import-manager*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Importation Excel</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Importation Excel
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: CONFIGURATION -->
    <div class="space-y-1">
        <div class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider" x-show="!sidebarCollapsed">
            Configuration
        </div>

        <!-- Paramètres Agence -->
        @if(auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('settings') ? route('settings') : '#' }}" 
               class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('settings*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
                <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('settings*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3 truncate" x-show="!sidebarCollapsed">Paramètres Agence</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Paramètres Agence
                </div>
            </a>
        @endif

        <!-- Centre Sécurité -->
        <a href="{{ Route::has('admin.security') ? route('admin.security') : '#' }}" 
           class="flex items-center text-sm font-medium rounded-xl transition-all duration-200 relative group py-2.5 {{ request()->routeIs('admin.security*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-sm font-semibold' : 'text-slate-400 hover:bg-[#1E293B]/60 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'">
            <svg class="h-5 w-5 shrink-0 transition-colors {{ request()->routeIs('admin.security*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Centre Sécurité</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Centre Sécurité
            </div>
        </a>
    </div>

</nav>

<!-- 3. FIXED BOTTOM AREA (User Profile & Logout) -->
<div class="shrink-0 border-t border-[#1E293B] p-3 space-y-2">
    <!-- User Profile Card -->
    <div class="p-2.5 rounded-xl bg-[#1E293B]/50 border border-[#334155]/40 flex items-center gap-3 relative group">
        <div class="h-8 w-8 rounded-full bg-teal-600 flex items-center justify-center font-bold text-white text-xs shrink-0 shadow-sm">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div class="overflow-hidden" x-show="!sidebarCollapsed">
            <div class="font-bold text-xs text-white truncate">{{ auth()->user()->name }}</div>
            <div class="text-[9px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">
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

        <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
            {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Logout Button -->
    <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
        @csrf
        <button type="submit" 
                class="w-full flex items-center text-xs font-semibold rounded-xl text-rose-400 hover:bg-rose-950/30 hover:text-rose-300 transition-all duration-200 py-2 relative group"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'">
            <svg class="h-5 w-5 shrink-0 text-rose-400 group-hover:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="ml-3 truncate" x-show="!sidebarCollapsed">Déconnexion</span>

            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Déconnexion
            </div>
        </button>
    </form>
</div>
