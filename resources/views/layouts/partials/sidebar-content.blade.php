<!-- BRAND LOGO HEADER -->
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
<nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-4">
    <!-- Dashboard -->
    @if(in_array('dashboard', $enabledPages) && !auth()->user()->hasRole('agent-commercial'))
        <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
            </svg>
            Dashboard
        </a>
    @endif

    <!-- CRM SECTION -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">CRM</div>
    
    <!-- Clients -->
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

    <!-- Communications -->
    <a href="{{ Route::has('admin.communications') ? route('admin.communications') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.communications*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        Communications
    </a>

    <!-- Produits -->
    @if(auth()->user()->hasRole('agency-admin'))
        <a href="{{ Route::has('admin.products') ? route('admin.products') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.products') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Produits
        </a>
    @endif

    <!-- INSURANCE SECTION -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Assurance</div>

    <!-- Contracts (Production Assurance) -->
    @if(in_array('automobile', $enabledPages))
        <a href="{{ Route::has('automobile.index') ? route('automobile.index') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('automobile.*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Production Assurance
        </a>
    @endif

    <!-- Dossiers & Sinistres -->
    <a href="{{ Route::has('admin.dossiers') ? route('admin.dossiers') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.dossiers*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
        </svg>
        Dossiers & Sinistres
    </a>

    <!-- Coffre-fort Documents -->
    <a href="{{ Route::has('admin.vault') ? route('admin.vault') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.vault*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Coffre-fort Documents
    </a>

    <!-- Assureurs -->
    @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
        <a href="{{ Route::has('admin.compagnies') ? route('admin.compagnies') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.compagnies') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1" />
            </svg>
            Assureurs
        </a>
    @endif

    <!-- FINANCE SECTION -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Finance</div>

    <!-- Centre de Paiements -->
    <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.payments.center*') && request('tab') !== 'cheques' && !request()->routeIs('admin.payments.workspace*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Centre de Paiements
    </a>

    <!-- Chèques -->
    <a href="{{ Route::has('admin.payments.center') ? route('admin.payments.center') . '?tab=cheques' : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.payments.center*') && request('tab') === 'cheques' ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
        </svg>
        Chèques
    </a>

    <!-- Dépenses -->
    @if(in_array('charges', $enabledPages) && auth()->user()->hasRole('agency-admin'))
        <a href="{{ Route::has('admin.charges') ? route('admin.charges') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.charges') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Dépenses & Charges
        </a>
    @endif

    <!-- Commissions Admin -->
    @if(in_array('commissions', $enabledPages) && auth()->user()->hasRole('agency-admin'))
        <a href="{{ Route::has('admin.commissions') ? route('admin.commissions') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.commissions') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Commissions Admin
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

    <!-- ORGANIZATION SECTION -->
    @if(auth()->user()->hasRole('agency-admin'))
        <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Organisation</div>

    <!-- Succursales -->
    @if(in_array('succursales', $enabledPages) && auth()->user()->hasRole('agency-admin'))
        <a href="{{ Route::has('admin.succursales') ? route('admin.succursales') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.succursales') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Succursales
        </a>
    @endif

    <!-- Collaborateurs -->
    @if(in_array('employes', $enabledPages) && auth()->user()->hasRole('agency-admin'))
        <a href="{{ Route::has('admin.employes') ? route('admin.employes') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.employes') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Collaborateurs
        </a>
    @endif
    @endif

    <!-- OPERATIONS SECTION -->
    <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Opérations</div>

    <!-- Tâches Kanban -->
    <a href="{{ Route::has('admin.tasks') ? route('admin.tasks') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.tasks') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Tâches Kanban
    </a>

    <!-- Agenda & Calendrier -->
    <a href="{{ Route::has('admin.agenda') ? route('admin.agenda') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.agenda*') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Agenda & Calendrier
    </a>

    <!-- Journal d'activité -->
    @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
        <a href="{{ Route::has('admin.activity-timeline') ? route('admin.activity-timeline') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.activity-timeline') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Journal d'activité
        </a>
    @endif

    <!-- Importation Excel -->
    @if(auth()->user()->hasRole('agency-admin') || auth()->user()->hasRole('Agency Owner') || auth()->user()->hasRole('Super Admin'))
        <a href="{{ Route::has('admin.import-manager') ? route('admin.import-manager') : '#' }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('admin.import-manager') ? 'bg-[#1E293B] text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
            </svg>
            Importation Excel
        </a>
    @endif

    <!-- CONFIGURATION SECTION -->
    @if(auth()->user()->hasRole('agency-admin'))
        <div class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Configuration</div>

        <!-- Paramètres Agence -->
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
