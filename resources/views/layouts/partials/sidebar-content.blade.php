<div x-data="{
    searchQuery: '',
    openSections: {
        crm: true,
        insurance: true,
        finance: true,
        operations: true,
        organization: true,
        reporting: true,
        settings: true
    },
    favorites: [],
    recents: [],
    showQuickCreate: false,
    
    init() {
        this.openSections = JSON.parse(localStorage.getItem('insurio_sidebar_open_sections')) || {
            crm: true,
            insurance: true,
            finance: true,
            operations: true,
            organization: true,
            reporting: true,
            settings: true
        };
        this.favorites = JSON.parse(localStorage.getItem('insurio_sidebar_favorites')) || [];
        this.recents = JSON.parse(localStorage.getItem('insurio_sidebar_recents')) || [];
    },
    
    toggleSection(section) {
        this.openSections[section] = !this.openSections[section];
        localStorage.setItem('insurio_sidebar_open_sections', JSON.stringify(this.openSections));
    },
    
    toggleFavorite(route, name, icon) {
        let index = this.favorites.findIndex(f => f.route === route);
        if (index > -1) {
            this.favorites.splice(index, 1);
        } else {
            this.favorites.push({ route, name, icon });
        }
        localStorage.setItem('insurio_sidebar_favorites', JSON.stringify(this.favorites));
    },
    
    isFavorite(route) {
        return this.favorites.some(f => f.route === route);
    },
    
    addRecent(route, name, icon) {
        let index = this.recents.findIndex(r => r.route === route);
        if (index > -1) {
            this.recents.splice(index, 1);
        }
        this.recents.unshift({ route, name, icon });
        if (this.recents.length > 3) {
            this.recents.pop();
        }
        localStorage.setItem('insurio_sidebar_recents', JSON.stringify(this.recents));
    },
    
    matchesSearch(label, parentSection = null) {
        if (!this.searchQuery) return true;
        let query = this.searchQuery.toLowerCase();
        let matchesLabel = label.toLowerCase().includes(query);
        if (matchesLabel) return true;
        if (parentSection) {
            return parentSection.toLowerCase().includes(query);
        }
        return false;
    }
}" class="flex flex-col h-full bg-[#0F172A] text-slate-350 select-none border-r border-[#1E293B]">
    
    <!-- BRAND LOGO HEADER -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B] bg-[#090D1A]/50">
        @if(tenant('logo_path'))
            <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-9 max-w-full object-contain mx-auto">
        @else
            <div class="flex items-center gap-2">
                <svg class="h-7 w-7 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="text-md font-bold text-white font-sans tracking-wide">{{ \App\Models\Setting::get('agency_name', tenant('name') ?? 'Insurio') }}</span>
            </div>
        @endif
    </div>

    <!-- QUICK CREATE DROPDOWN TRIGGER -->
    <div class="px-4 py-3 border-b border-[#1E293B]/60 bg-[#0F172A]">
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-extrabold uppercase tracking-widest bg-gradient-to-r from-teal-500 to-teal-650 hover:from-teal-600 hover:to-teal-700 text-white rounded-xl shadow-lg shadow-teal-500/10 transition-all hover:scale-[1.02] active:scale-[0.98]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau
            </button>
            <div x-show="open" x-transition class="absolute left-0 right-0 mt-2 bg-[#1E293B] border border-[#334155]/60 rounded-xl shadow-2xl overflow-hidden z-30">
                <a href="{{ route('admin.clients') }}?create=1" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-[#334155]/50 transition-colors">
                    <span class="text-teal-400">👤</span> Nouveau Client
                </a>
                <a href="{{ route('automobile.create') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-[#334155]/50 transition-colors">
                    <span class="text-indigo-400">🛡️</span> Nouveau Contrat
                </a>
                <a href="{{ route('admin.payments.center') }}?create=1" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-[#334155]/50 transition-colors">
                    <span class="text-emerald-400">💰</span> Nouveau Règlement
                </a>
                <a href="{{ route('admin.dossiers') }}?create=1" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-[#334155]/50 transition-colors">
                    <span class="text-amber-400">📁</span> Nouveau Dossier / Incident
                </a>
                <a href="{{ route('admin.tasks') }}?create=1" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 hover:bg-[#334155]/50 transition-colors">
                    <span class="text-rose-400">📋</span> Nouvelle Tâche
                </a>
            </div>
        </div>
    </div>

    <!-- MODULES SEARCH INPUT -->
    <div class="px-4 py-3 border-b border-[#1E293B]/60 bg-[#0B0F19]/40">
        <div class="relative">
            <input type="text" x-model="searchQuery" placeholder="Rechercher un module..." class="w-full bg-[#1E293B]/50 border border-[#334155]/40 rounded-xl px-3 py-1.5 pl-8 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-teal-500/80 focus:ring-1 focus:ring-teal-500/80 transition-all">
            <svg class="absolute left-2.5 top-2.5 h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-slate-500 hover:text-white">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- NAVIGATION LIST -->
    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4 scrollbar-thin scrollbar-thumb-slate-800">
        
        <!-- FAVORITES CATEGORY (Dynamic) -->
        <div x-show="favorites.length > 0 && searchQuery === ''" class="space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 mb-1">
                <span>⭐ Favoris</span>
            </div>
            <template x-for="fav in favorites" :key="fav.route">
                <div class="group flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-400 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                    <a :href="fav.route" class="flex items-center flex-1" @click="addRecent(fav.route, fav.name, fav.icon)">
                        <span class="mr-2.5 text-slate-500 group-hover:text-teal-400 transition-colors" x-text="fav.icon"></span>
                        <span x-text="fav.name"></span>
                    </a>
                    <button @click="toggleFavorite(fav.route, fav.name, fav.icon)" class="text-amber-500 hover:text-slate-400 transition-colors">
                        ★
                    </button>
                </div>
            </template>
        </div>

        <!-- RECENTS CATEGORY (Dynamic) -->
        <div x-show="recents.length > 0 && searchQuery === ''" class="space-y-1">
            <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 mb-1">
                <span>🕒 Récents</span>
            </div>
            <template x-for="recent in recents" :key="recent.route">
                <a :href="recent.route" class="flex items-center px-3 py-2 text-xs font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/30 hover:text-white transition-all">
                    <span class="mr-2.5 text-slate-600" x-text="recent.icon"></span>
                    <span x-text="recent.name"></span>
                </a>
            </template>
        </div>

        <!-- 🏠 DASHBOARD -->
        @if(!auth()->user()->hasRole('agent-commercial'))
        <div class="space-y-1" x-show="matchesSearch('Dashboard', 'Dashboard')">
            <a href="{{ route('dashboard') }}" @click="addRecent('{{ route('dashboard') }}', 'Dashboard', '🏠')" class="flex items-center justify-between px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#1E293B] text-white border-l-2 border-teal-500 shadow-lg shadow-teal-500/5' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                <div class="flex items-center">
                    <svg class="h-4.5 w-4.5 mr-2.5 text-slate-500 {{ request()->routeIs('dashboard') ? 'text-teal-400' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </div>
                <button @click.prevent.stop="toggleFavorite('{{ route('dashboard') }}', 'Dashboard', '🏠')" class="text-slate-600 hover:text-amber-500 transition-colors">
                    <span x-text="isFavorite('{{ route('dashboard') }}') ? '★' : '☆'"></span>
                </button>
            </a>
        </div>
        @endif

        <!-- 👥 CRM MODULE -->
        @if(auth()->user()->canAny(['clients.view', 'contracts.view']))
        <div class="space-y-1" x-show="matchesSearch('Clients Entreprises Prospects Contacts Produits', 'CRM')">
            <div @click="toggleSection('crm')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">👥</span>
                    <span>CRM</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.crm ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.crm" x-collapse class="pl-2 space-y-1 mt-1">
                @if(auth()->user()->can('clients.view'))
                <!-- Clients -->
                <a href="{{ route('admin.clients') }}" @click="addRecent('{{ route('admin.clients') }}', 'Clients', '👤')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.clients') || request()->routeIs('admin.clients.profile') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Clients', 'CRM')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.clients') ? 'text-teal-400' : '' }}">👤</span> Clients
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.clients') }}', 'Clients', '👤')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.clients') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Entreprises -->
                <a href="{{ route('admin.entreprises') }}" @click="addRecent('{{ route('admin.entreprises') }}', 'Entreprises', '🏢')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.entreprises') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Entreprises', 'CRM')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.entreprises') ? 'text-teal-400' : '' }}">🏢</span> Entreprises
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.entreprises') }}', 'Entreprises', '🏢')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.entreprises') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                <!-- Prospects (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Prospects', 'CRM')">
                    <span>⚡ Prospects <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Contacts (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Contacts', 'CRM')">
                    <span>📞 Contacts <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                @if(auth()->user()->can('contracts.view'))
                <!-- Produits -->
                <a href="{{ route('admin.products') }}" @click="addRecent('{{ route('admin.products') }}', 'Produits', '📦')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.products') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Produits', 'CRM')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.products') ? 'text-teal-400' : '' }}">📦</span> Produits
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.products') }}', 'Produits', '📦')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.products') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- 🛡 INSURANCE MODULE -->
        @if(auth()->user()->canAny(['contracts.view', 'clients.view']))
        <div class="space-y-1" x-show="matchesSearch('Contracts Policies Dossiers Sinistres Renewals Compagnies', 'INSURANCE')">
            <div @click="toggleSection('insurance')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">🛡️</span>
                    <span>INSURANCE</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.insurance ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.insurance" x-collapse class="pl-2 space-y-1 mt-1">
                @if(auth()->user()->can('contracts.view'))
                <!-- Contracts / Policies -->
                <a href="{{ route('automobile.index') }}" @click="addRecent('{{ route('automobile.index') }}', 'Contrats', '📝')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('automobile.*') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Contracts Policies Contrats', 'INSURANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('automobile.*') ? 'text-teal-400' : '' }}">📝</span> Contrats
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('automobile.index') }}', 'Contrats', '📝')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('automobile.index') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                @if(auth()->user()->can('clients.view'))
                <!-- Dossiers & Sinistres -->
                <a href="{{ route('admin.dossiers') }}" @click="addRecent('{{ route('admin.dossiers') }}', 'Dossiers & Sinistres', '📁')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.dossiers') || request()->routeIs('admin.dossiers.workspace') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Dossiers Sinistres Incidents', 'INSURANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.dossiers') ? 'text-teal-400' : '' }}">📁</span> Dossiers & Sinistres
                        @if(isset($urgentClaimsCount) && $urgentClaimsCount > 0)
                            <span class="ml-2 bg-rose-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full animate-pulse">{{ $urgentClaimsCount }} Urg</span>
                        @endif
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.dossiers') }}', 'Dossiers & Sinistres', '📁')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.dossiers') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                <!-- Renewals (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Renewals Renouvellements', 'INSURANCE')">
                    <span>🔁 Renouvellements <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                @if(auth()->user()->can('contracts.view'))
                <!-- Insurance Companies -->
                <a href="{{ route('admin.compagnies') }}" @click="addRecent('{{ route('admin.compagnies') }}', 'Compagnies d\'Assurance', '🏢')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.compagnies') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Insurance Companies Compagnies', 'INSURANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.compagnies') ? 'text-teal-400' : '' }}">🏢</span> Compagnies
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.compagnies') }}', 'Compagnies d\'Assurance', '🏢')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.compagnies') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- 💰 FINANCE MODULE -->
        @if(auth()->user()->canAny(['payments.manage', 'clients.view', 'commissions.view', 'expenses.view']))
        <div class="space-y-1" x-show="matchesSearch('Payment Center Cheque Invoices Commissions Expenses Refunds Financial Reports', 'FINANCE')">
            <div @click="toggleSection('finance')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">💰</span>
                    <span>FINANCE</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.finance ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.finance" x-collapse class="pl-2 space-y-1 mt-1">
                @if(auth()->user()->can('clients.view'))
                <!-- Payment Center -->
                <a href="{{ route('admin.payments.center') }}" @click="addRecent('{{ route('admin.payments.center') }}', 'Payment Center', '💳')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.payments.center') || request()->routeIs('admin.payments.workspace') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Payment Center Règlements', 'FINANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.payments.center') ? 'text-teal-400' : '' }}">💳</span> Règlements
                        @if(isset($pendingPaymentsCount) && $pendingPaymentsCount > 0)
                            <span class="ml-2 bg-emerald-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingPaymentsCount }} Att</span>
                        @endif
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.payments.center') }}', 'Payment Center', '💳')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.payments.center') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Cheque Center -->
                <a href="{{ route('admin.payments.center') }}?tab=cheques" @click="addRecent('{{ route('admin.payments.center') }}?tab=cheques', 'Cheque Center', '🎫')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.payments.center') && request('tab') === 'cheques' ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Cheque Center Chèques', 'FINANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.payments.center') && request('tab') === 'cheques' ? 'text-teal-400' : '' }}">🎫</span> Chèques
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.payments.center') }}?tab=cheques', 'Cheque Center', '🎫')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.payments.center') }}?tab=cheques') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                <!-- Invoices (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Invoices Factures', 'FINANCE')">
                    <span>🧾 Factures <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                @if(auth()->user()->can('commissions.view'))
                <!-- Commissions -->
                <a href="{{ route('admin.commissions') }}" @click="addRecent('{{ route('admin.commissions') }}', 'Commissions', '📈')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.commissions') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Commissions', 'FINANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.commissions') ? 'text-teal-400' : '' }}">📈</span> Commissions
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.commissions') }}', 'Commissions', '📈')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.commissions') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                @if(auth()->user()->can('expenses.view'))
                <!-- Expenses -->
                <a href="{{ route('admin.charges') }}" @click="addRecent('{{ route('admin.charges') }}', 'Charges', '💸')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.charges') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Expenses Charges Dépenses', 'FINANCE')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.charges') ? 'text-teal-400' : '' }}">💸</span> Charges / Dépenses
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.charges') }}', 'Charges', '💸')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.charges') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
                @endif

                <!-- Refunds (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Refunds Remboursements', 'FINANCE')">
                    <span>💵 Remboursements <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Financial Reports (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Financial Reports Rapports Financiers', 'FINANCE')">
                    <span>📊 Rapports Fin. <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>
            </div>
        </div>
        @endif

        <!-- 👨💼 OPERATIONS MODULE -->
        @if(auth()->user()->can('clients.view'))
        <div class="space-y-1" x-show="matchesSearch('Kanban Tasks Calendar Activity Journal Follow Ups', 'OPERATIONS')">
            <div @click="toggleSection('operations')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">👨‍💼</span>
                    <span>OPERATIONS</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.operations ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.operations" x-collapse class="pl-2 space-y-1 mt-1">
                <!-- Kanban / Tasks -->
                <a href="{{ route('admin.tasks') }}" @click="addRecent('{{ route('admin.tasks') }}', 'Tâches Kanban', '📋')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.tasks') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Kanban Tasks Tâches', 'OPERATIONS')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.tasks') ? 'text-teal-400' : '' }}">📋</span> Tâches Kanban
                        @if(isset($pendingTasksCount) && $pendingTasksCount > 0)
                            <span class="ml-2 bg-rose-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingTasksCount }}</span>
                        @endif
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.tasks') }}', 'Tâches Kanban', '📋')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.tasks') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Calendar (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Calendar Calendrier', 'OPERATIONS')">
                    <span>📅 Calendrier <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Activity Journal -->
                <a href="{{ route('admin.activity-timeline') }}" @click="addRecent('{{ route('admin.activity-timeline') }}', 'Journal d\'activité', '⏳')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.activity-timeline') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Activity Journal Activités', 'OPERATIONS')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.activity-timeline') ? 'text-teal-400' : '' }}">⏳</span> Journal d'Activité
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.activity-timeline') }}', 'Journal d\'activité', '⏳')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.activity-timeline') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Follow Ups (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Follow Ups Relances', 'OPERATIONS')">
                    <span>🔔 Relances Amiables <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>
            </div>
        </div>
        @endif

        <!-- 🏢 ORGANIZATION MODULE -->
        @if(auth()->user()->can('expenses.view'))
        <div class="space-y-1" x-show="matchesSearch('Branches Employees Teams Departments', 'ORGANIZATION')">
            <div @click="toggleSection('organization')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">🏢</span>
                    <span>ORGANIZATION</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.organization ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.organization" x-collapse class="pl-2 space-y-1 mt-1">
                <!-- Branches / Succursales -->
                <a href="{{ route('admin.succursales') }}" @click="addRecent('{{ route('admin.succursales') }}', 'Succursales', '📍')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.succursales') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Branches Succursales', 'ORGANIZATION')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.succursales') ? 'text-teal-400' : '' }}">📍</span> Succursales
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.succursales') }}', 'Succursales', '📍')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.succursales') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Employees / Collaborateurs -->
                <a href="{{ route('admin.employes') }}" @click="addRecent('{{ route('admin.employes') }}', 'Personnel', '💼')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.employes') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Employees Employés Personnel', 'ORGANIZATION')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.employes') ? 'text-teal-400' : '' }}">💼</span> Personnel
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.employes') }}', 'Personnel', '💼')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.employes') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Teams (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Teams Équipes', 'ORGANIZATION')">
                    <span>👥 Équipes <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Departments (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Departments Départements', 'ORGANIZATION')">
                    <span>🏢 Départements <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>
            </div>
        </div>
        @endif

        <!-- 📊 REPORTING MODULE -->
        @if(auth()->user()->can('clients.create'))
        <div class="space-y-1" x-show="matchesSearch('Analytics Reports Statistics Exports Excel Import', 'REPORTING')">
            <div @click="toggleSection('reporting')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">📊</span>
                    <span>REPORTING</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.reporting ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.reporting" x-collapse class="pl-2 space-y-1 mt-1">
                <!-- Analytics (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Analytics Analyses', 'REPORTING')">
                    <span>📈 Analyses / Graph <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Reports (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Reports Rapports', 'REPORTING')">
                    <span>📋 Rapports Custom <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Excel Import -->
                <a href="{{ route('admin.import-manager') }}" @click="addRecent('{{ route('admin.import-manager') }}', 'Excel Import', '📥')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.import-manager') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Excel Import Importation', 'REPORTING')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.import-manager') ? 'text-teal-400' : '' }}">📥</span> Excel Import
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.import-manager') }}', 'Excel Import', '📥')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.import-manager') }}') ? '★' : '☆'"></span>
                    </button>
                </a>
            </div>
        </div>
        @endif

        <!-- ⚙ SETTINGS MODULE -->
        @if(auth()->user()->hasRole('agency-admin'))
        <div class="space-y-1" x-show="matchesSearch('Configuration Roles Permissions Integrations Notifications Templates Automation Control', 'SETTINGS')">
            <div @click="toggleSection('settings')" class="flex items-center justify-between text-[10px] font-bold text-slate-500 uppercase tracking-widest px-2 py-1.5 cursor-pointer hover:text-slate-350 transition-colors">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 text-xs">⚙️</span>
                    <span>SETTINGS</span>
                </div>
                <svg class="h-3 w-3 transform transition-transform duration-200" :class="openSections.settings ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            
            <div x-show="openSections.settings" x-collapse class="pl-2 space-y-1 mt-1">
                <!-- Configuration -->
                <a href="{{ route('settings') }}" @click="addRecent('{{ route('settings') }}', 'Configuration', '🔧')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('settings') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Configuration Paramètres', 'SETTINGS')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('settings') ? 'text-teal-400' : '' }}">🔧</span> Paramètres
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('settings') }}', 'Configuration', '🔧')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('settings') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Automation Control -->
                <a href="{{ route('admin.automation') }}" @click="addRecent('{{ route('admin.automation') }}', 'Automates', '🤖')" class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl transition-all {{ request()->routeIs('admin.automation') ? 'bg-[#1E293B]/70 text-white' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}" x-show="matchesSearch('Automation Automates Règles', 'SETTINGS')">
                    <span class="flex items-center">
                        <span class="mr-2 text-slate-500 {{ request()->routeIs('admin.automation') ? 'text-teal-400' : '' }}">🤖</span> Automates / CRON
                    </span>
                    <button @click.prevent.stop="toggleFavorite('{{ route('admin.automation') }}', 'Automates', '🤖')" class="text-slate-600 hover:text-amber-500 transition-colors">
                        <span x-text="isFavorite('{{ route('admin.automation') }}') ? '★' : '☆'"></span>
                    </button>
                </a>

                <!-- Roles & Permissions (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Roles Permissions Rôles', 'SETTINGS')">
                    <span>🔑 Rôles & Accès <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Integrations (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Integrations API Webhooks', 'SETTINGS')">
                    <span>🔌 Intégrations API <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>

                <!-- Templates (Future) -->
                <div class="flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 cursor-not-allowed opacity-50" x-show="matchesSearch('Templates Modèles Courriers', 'SETTINGS')">
                    <span>📄 Modèles Courrier <span class="text-[8px] bg-slate-800 text-slate-450 px-1 py-0.5 rounded ml-1 font-bold">Bientôt</span></span>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- USER PROFILE BLOCK & LOGOUT -->
    <div class="p-4 border-t border-[#1E293B] bg-[#090D1A]/50">
        <div class="flex items-center gap-3 mb-3">
            <div class="h-9 w-9 rounded-full bg-teal-650 flex items-center justify-center font-bold text-white text-sm shadow-md">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <div class="font-bold text-xs text-white truncate">{{ auth()->user()->name }}</div>
                <div class="text-[9px] text-slate-455 font-medium uppercase tracking-wider mt-0.5">
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
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold rounded-xl text-rose-455 hover:bg-rose-950/20 transition-all border border-rose-950/10">
                <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Déconnexion
            </button>
        </form>
    </div>

</div>
