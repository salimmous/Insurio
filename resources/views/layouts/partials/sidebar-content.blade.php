<!-- 1. FIXED HEADER (Agency Card & Collapse Toggle) -->
<div class="h-[72px] flex items-center justify-between px-5 border-b border-[#1E293B] shrink-0 box-border">
    <div class="flex items-center gap-3.5 overflow-hidden">
        @if(tenant('logo_path'))
            <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-9 max-w-full object-contain shrink-0">
        @else
            <div class="h-9 w-9 rounded-2xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center font-black shrink-0 shadow-xs">
                <svg class="h-5 w-5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
            </div>
            <span class="text-[16px] font-extrabold text-white tracking-tight truncate" x-show="!sidebarCollapsed">
                {{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}
            </span>
        @endif
    </div>

    <!-- Mobile Close Drawer Button -->
    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1.5 rounded-xl hover:bg-slate-800 transition">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- 2. NAVIGATION AREA (24px Section Gaps, 8px Item Gaps, 48px Height, 15px Text, 18px Padding, 14px Icon Gap) -->
@php
    $enabledPages = json_decode(\App\Models\Setting::get('enabled_pages', '[]'), true) ?: ['dashboard', 'automobile', 'succursales', 'employes', 'commissions', 'charges'];
@endphp
<nav class="flex-1 px-4 py-4 space-y-6 overflow-y-auto sidebar-scrollbar box-border">

    <!-- Dashboard -->
    @if(in_array('dashboard', $enabledPages) && !auth()->user()->hasRole('agent-commercial'))
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('dashboard') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: LayoutDashboard (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h6v8H4zM14 4h6v4h-6zM4 16h6v4H4zM14 12h6v8h-6z" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Dashboard</span>

            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Dashboard
            </div>
        </a>
    @endif

    <!-- SECTION: CRM -->
    <div class="space-y-2">
        <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
            CRM
        </div>

        <!-- Clients -->
        <a href="{{ Route::has('admin.clients') ? route('admin.clients') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.clients*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: Users (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.clients*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Clients</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Clients
            </div>
        </a>

        <!-- Entreprises -->
        <a href="{{ Route::has('admin.entreprises') ? route('admin.entreprises') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.entreprises*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: Building2 (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.entreprises*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                <path d="M10 6h4M10 10h4M10 14h4M10 18h4" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Entreprises</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Entreprises
            </div>
        </a>

        <!-- Communications -->
        <a href="{{ Route::has('admin.communications') ? route('admin.communications') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.communications*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: MessagesSquare (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.communications*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4c0-1.1.9-2 2-2h8a2 2 0 0 1 2 2z" />
                <path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Communications</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Communications
            </div>
        </a>

        <!-- Produits -->
        @if(auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.products*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: Package (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.products*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
                    <path d="m12 22 10-5.83M12 12l8.73-5M12 12v10M12 12-8.73-5" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Produits</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Produits
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: ASSURANCE -->
    <div class="space-y-2">
        <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
            Assurance
        </div>

        <!-- Production Assurance -->
        @if(in_array('automobile', $enabledPages))
            <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('automobile.*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: FileBadge (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('automobile.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22h6a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <circle cx="5" cy="14" r="3" />
                    <path d="m7 16.5 2 4.5-4-1-4 1 2-4.5" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Production Assurance</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Production Assurance
                </div>
            </a>
        @endif

        <!-- Dossiers & Sinistres -->
        <a href="{{ Route::has('admin.dossiers') ? route('admin.dossiers') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.dossiers*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: FolderOpen (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.dossiers*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 14 1.5-2.9A2 2 0 0 1 9.3 10H20a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 2h5a2 2 0 0 1 2 2v2" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Dossiers & Sinistres</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Dossiers & Sinistres
            </div>
        </a>

        <!-- Coffre-fort Documents -->
        <a href="{{ Route::has('admin.vault') ? route('admin.vault') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.vault*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: Archive (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.vault*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="5" x="2" y="3" rx="1" />
                <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8" />
                <path d="M10 12h4" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Coffre-fort Documents</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Coffre-fort Documents
            </div>
        </a>

        <!-- Assureurs -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner'))
            <a href="{{ Route::has('admin.compagnies') ? route('admin.compagnies') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.compagnies*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: Landmark (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.compagnies*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" x2="21" y1="22" y2="22" />
                    <line x1="6" x2="6" y1="18" y2="11" />
                    <line x1="10" x2="10" y1="18" y2="11" />
                    <line x1="14" x2="14" y1="18" y2="11" />
                    <line x1="18" x2="18" y1="18" y2="11" />
                    <polygon points="12 2 20 7 4 7 12 2" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Assureurs</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Assureurs
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: FINANCE -->
    <div class="space-y-2">
        <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
            Finance
        </div>

        <!-- Centre de Paiements -->
        <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.payments.center*') && request('tab') !== 'cheques' && !request()->routeIs('admin.payments.workspace*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: WalletCards (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.payments.center*') && request('tab') !== 'cheques' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="12" x="3" y="8" rx="2" />
                <path d="M7 8V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-3" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Centre de Paiements</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Centre de Paiements
            </div>
        </a>

        <!-- Chèques -->
        <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') . '?tab=cheques' : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.payments.center*') && request('tab') === 'cheques' ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: Receipt (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.payments.center*') && request('tab') === 'cheques' ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                <path d="M12 6v12" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Chèques</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Chèques
            </div>
        </a>

        <!-- Dépenses -->
        @if(in_array('charges', $enabledPages) && auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.charges') ? route('admin.charges') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.charges*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: Banknote (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.charges*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="12" x="2" y="6" rx="2" />
                    <circle cx="12" cy="12" r="2" />
                    <path d="M6 12h.01M18 12h.01" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Dépenses & Charges</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Dépenses & Charges
                </div>
            </a>
        @endif

        <!-- Commissions Admin -->
        @if(in_array('commissions', $enabledPages) && auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('admin.commissions') ? route('admin.commissions') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.commissions*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: BadgeDollarSign (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.commissions*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.78 4 4 0 0 1 0-6.74Z" />
                    <path d="M12 8v8M9.5 9.5a2.5 2.5 0 0 1 5 0c0 1.5-1.5 2-2.5 2s-2.5.5-2.5 2a2.5 2.5 0 0 0 5 0" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Commissions Admin</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Commissions Admin
                </div>
            </a>
        @endif

        <!-- Agent Commissions -->
        @if(auth()->user()->hasRole('agent-commercial'))
            <a href="{{ Route::has('agent.commissions') ? route('agent.commissions') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('agent.commissions*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: BadgeDollarSign (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('agent.commissions*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.78 4 4 0 0 1 0-6.74Z" />
                    <path d="M12 8v8M9.5 9.5a2.5 2.5 0 0 1 5 0c0 1.5-1.5 2-2.5 2s-2.5.5-2.5 2a2.5 2.5 0 0 0 5 0" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Mes Commissions</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Mes Commissions
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: ORGANISATION -->
    @if(auth()->user()->hasRole('agency-admin'))
        <div class="space-y-2">
            <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
                Organisation
            </div>

            <!-- Succursales -->
            @if(in_array('succursales', $enabledPages))
                <a href="{{ Route::has('admin.succursales') ? route('admin.succursales') : '#' }}" 
                   class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.succursales*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                    <!-- Lucide: Building (21px) -->
                    <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.succursales*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2" />
                        <path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01" />
                    </svg>
                    <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Succursales</span>
                    <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                        Succursales
                    </div>
                </a>
            @endif

            <!-- Collaborateurs -->
            @if(in_array('employes', $enabledPages))
                <a href="{{ Route::has('admin.employes') ? route('admin.employes') : '#' }}" 
                   class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.employes*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                    <!-- Lucide: UserRound (21px) -->
                    <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.employes*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                    <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Collaborateurs</span>
                    <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                        Collaborateurs
                    </div>
                </a>
            @endif
        </div>
    @endif

    <!-- SECTION: OPÉRATIONS -->
    <div class="space-y-2">
        <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
            Opérations
        </div>

        <!-- Tâches Kanban -->
        <a href="{{ Route::has('admin.tasks') ? route('admin.tasks') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.tasks*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: KanbanSquare (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.tasks*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2" />
                <path d="M8 7v7M12 7v4M16 7v9" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Tâches Kanban</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Tâches Kanban
            </div>
        </a>

        <!-- Agenda & Calendrier -->
        <a href="{{ Route::has('admin.agenda') ? route('admin.agenda') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.agenda*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: CalendarDays (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.agenda*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                <line x1="16" x2="16" y1="2" y2="6" />
                <line x1="8" x2="8" y1="2" y2="6" />
                <line x1="3" x2="21" y1="10" y2="10" />
                <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Agenda & Calendrier</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Agenda & Calendrier
            </div>
        </a>

        <!-- Journal d'activité -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner'))
            <a href="{{ Route::has('admin.activity-timeline') ? route('admin.activity-timeline') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.activity-timeline*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: History (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.activity-timeline*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                    <path d="M12 7v5l4 2" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Journal d'activité</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Journal d'activité
                </div>
            </a>
        @endif

        <!-- Importation Excel -->
        @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner'))
            <a href="{{ Route::has('admin.import-manager') ? route('admin.import-manager') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.import-manager*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: FileSpreadsheet (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.import-manager*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                    <path d="M8 13h8M8 17h8M12 9v10" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Importation Excel</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Importation Excel
                </div>
            </a>
        @endif
    </div>

    <!-- SECTION: CONFIGURATION -->
    <div class="space-y-2">
        <div class="px-[18px] text-[12px] font-mono font-bold text-slate-400 uppercase tracking-[2px] mt-6 mb-3" x-show="!sidebarCollapsed">
            Configuration
        </div>

        <!-- Paramètres Agence -->
        @if(auth()->user()->hasRole('agency-admin'))
            <a href="{{ Route::has('settings') ? route('settings') : '#' }}" 
               class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('settings*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
               :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
                <!-- Lucide: Settings (21px) -->
                <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('settings*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Paramètres Agence</span>
                <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                    Paramètres Agence
                </div>
            </a>
        @endif

        <!-- Dashboard Sécurité -->
        <a href="{{ Route::has('admin.security-dashboard') ? route('admin.security-dashboard') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.security-dashboard*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.security-dashboard*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Dashboard Sécurité</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Dashboard Sécurité
            </div>
        </a>

        <!-- Centre Sécurité -->
        <a href="{{ Route::has('admin.security') ? route('admin.security') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.security') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: ShieldCheck (21px) -->
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.security') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                <path d="m9 12 2 2 4-4" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Centre Sécurité</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Centre Sécurité
            </div>
        </a>

        <!-- Audit & Conformité (Security Audit Center) -->
        <a href="{{ Route::has('admin.security-audit') ? route('admin.security-audit') : '#' }}" 
           class="h-[48px] min-h-[48px] flex items-center text-[15px] font-semibold rounded-xl transition-all duration-200 relative group px-[18px] {{ request()->routeIs('admin.security-audit*') ? 'bg-[#1E293B] text-white border-l-4 border-teal-400 shadow-md font-bold' : 'text-slate-300 hover:bg-[#1E293B]/70 hover:text-white' }}"
           :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <svg width="21" height="21" class="h-[21px] w-[21px] shrink-0 transition-colors {{ request()->routeIs('admin.security-audit*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Audit & Conformité</span>
            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Audit & Conformité
            </div>
        </a>
    </div>

</nav>

<!-- 3. PINNED BOTTOM USER PROFILE FOOTER (Generous Whitespace & Padding) -->
<div class="shrink-0 border-t border-[#1E293B] p-4 space-y-3 box-border bg-[#0B1120]/60">
    <!-- User Profile Card -->
    <div class="p-3.5 rounded-2xl bg-[#1E293B]/70 border border-[#334155]/60 flex items-center gap-3.5 relative group shadow-sm">
        <div class="h-9 w-9 rounded-2xl bg-teal-600 flex items-center justify-center font-black text-white text-xs shrink-0 shadow-md">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden" x-show="!sidebarCollapsed">
            <div class="font-bold text-[13px] text-white truncate leading-tight">{{ auth()->user()->name }}</div>
            <div class="text-[10px] text-slate-400 font-mono font-bold uppercase tracking-wider mt-0.5">
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

        <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
            {{ auth()->user()->name }}
        </div>
    </div>

    <!-- Logout Button -->
    <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
        @csrf
        <button type="submit" 
                class="w-full h-[44px] flex items-center text-sm font-semibold rounded-xl text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 transition-all duration-200 px-[18px] relative group"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-[18px]'">
            <!-- Lucide: LogOut (21px) -->
            <svg class="h-[21px] w-[21px] shrink-0 text-rose-400 group-hover:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" x2="9" y1="12" y2="12" />
            </svg>
            <span class="ml-[14px] truncate" x-show="!sidebarCollapsed">Déconnexion</span>

            <div x-show="sidebarCollapsed" class="absolute left-full ml-3 px-3 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-xl shadow-2xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 border border-slate-700">
                Déconnexion
            </div>
        </button>
    </form>
</div>
