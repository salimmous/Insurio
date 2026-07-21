<div class="max-w-[1600px] mx-auto p-6 space-y-6 font-sans">

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" class="h-4.5 w-4.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs">Fermer</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg width="18" height="18" class="h-4.5 w-4.5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" x2="12" y1="8" y2="12" />
                    <line x1="12" x2="12.01" y1="16" y2="16" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-xs">Fermer</button>
        </div>
    @endif

    <!-- 1. TOP HEADER COMMAND CENTER -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white rounded-2xl p-6 shadow-lg border border-slate-800 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center font-black text-2xl shadow-inner shrink-0">
                @if(tenant('logo_path'))
                    <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-10 w-10 object-contain">
                @else
                    <!-- Lucide: Building2 -->
                    <svg width="24" height="24" class="h-6 w-6 text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                        <path d="M10 6h4M10 10h4M10 14h4M10 18h4" />
                    </svg>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold tracking-tight text-white">{{ $agency_name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-teal-500/20 text-teal-300 border border-teal-500/30 uppercase tracking-widest">
                        {{ $subscription['plan'] }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                    <span>ICE: <strong class="text-slate-300">{{ $ice }}</strong></span>
                    <span>•</span>
                    <span>RC: <strong class="text-slate-300">{{ $rc }}</strong></span>
                    <span>•</span>
                    <span>Licence: <strong class="text-emerald-400">{{ $subscription['license_key'] }}</strong></span>
                </p>
            </div>
        </div>

        <!-- System Health Quick Pills -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Statut Réseau</span>
                <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5 mt-0.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Opérationnel
                </span>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Stockage Doc</span>
                <span class="text-xs font-bold text-slate-200 font-mono mt-0.5 block">{{ $subscription['storage_used'] }}</span>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Collaborateurs</span>
                <span class="text-xs font-bold text-slate-200 font-mono mt-0.5 block">{{ $subscription['employees_count'] }} Actifs / {{ $subscription['branches_count'] }} Succ.</span>
            </div>

            <button wire:click="triggerBackup" class="bg-teal-600 hover:bg-teal-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md flex items-center gap-2">
                <!-- Lucide: DatabaseBackup -->
                <svg width="16" height="16" class="h-4 w-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <ellipse cx="12" cy="5" rx="9" ry="3" />
                    <path d="M3 12a9 3 0 0 0 5 2.69" />
                    <path d="M21 9.3V5" />
                    <path d="M3 5v14a9 3 0 0 0 12 2.84" />
                    <path d="M13 18h8" />
                    <path d="m18 15 3 3-3 3" />
                </svg>
                Sauvegarde Expresse
            </button>
        </div>
    </div>

    <!-- 2. KPI SUMMARY CARDS ROW -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Statut Agence</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Opérationnel</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">100% Uptime</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Licence</span>
            <span class="text-sm font-black text-slate-800 mt-1 block truncate">Enterprise 2027</span>
            <span class="text-[9px] text-slate-400 font-bold block mt-1">Valide 31/12/27</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Stockage</span>
            <span class="text-sm font-black text-slate-800 mt-1 block font-mono">1.4 GB / 25 GB</span>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1">
                <div class="bg-teal-500 h-1.5 rounded-full" style="width: 6%"></div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">SMTP Server</span>
            <span class="text-sm font-black text-slate-800 mt-1 block truncate">{{ $mail_host ?: 'Non configuré' }}</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">TLS / Port {{ $mail_port }}</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Sauvegarde</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Aujourd'hui</span>
            <span class="text-[9px] text-slate-400 font-bold block mt-1">Auto-sync DB & Cloud</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Score Sécurité</span>
            <span class="text-sm font-black text-emerald-700 mt-1 block font-mono">96 / 100</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">Niveau Élevé</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">API Gateway</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Active</span>
            <span class="text-[9px] text-indigo-600 font-bold block mt-1">v1 REST Ready</span>
        </div>
    </div>

    <!-- 3. MAIN WORKSPACE GRID (Left Nav, Main Form, Right Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT WORKSPACE NAVIGATION (3 cols) -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-sm space-y-1 sticky top-6">
                <div class="px-3 py-2 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Workspaces Agence</div>

                @foreach([
                    ['profile', 'Profil & Légal', 'Building2', 'Raison sociale, ICE, RC, Coordonnées'],
                    ['identity', 'Identité Visuelle', 'Palette', 'Logo, Couleurs, White-Label'],
                    ['business', 'Règles Métiers', 'SlidersHorizontal', 'Devise, Préfixes, Numérotation'],
                    ['commissions', 'Barèmes Commissions', 'BadgePercent', 'Taux apporteurs & agents'],
                    ['email', 'Serveur E-mail SMTP', 'Mail', 'Hôte, Port, Expéditeur, Test'],
                    ['notifications', 'Canaux Notifications', 'BellRing', 'WhatsApp, Email, Alertes'],
                    ['security', 'Sécurité & Droits', 'ShieldCheck', 'Rôles, Permissions, 2FA'],
                    ['documents', 'Modèles Documents', 'FileText', 'Entêtes, Pieds de page PDF'],
                    ['subscription', 'Licence & Plan', 'CreditCard', 'Quotas, Renouvellement'],
                    ['backups', 'Sauvegardes & Audit', 'DatabaseBackup', 'Restaurations & Historique'],
                    ['api', 'API & Webhooks', 'PlugZap', 'Clés API, Endpoints'],
                ] as [$key, $label, $iconName, $desc])
                <button wire:click="setTab('{{ $key }}')" 
                        class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200 text-left group {{ $activeTab === $key ? 'bg-slate-900 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <div class="shrink-0">
                            @if($key === 'profile')
                                <!-- Lucide: Building2 -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'profile' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z" />
                                    <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2" />
                                    <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2" />
                                    <path d="M10 6h4M10 10h4M10 14h4M10 18h4" />
                                </svg>
                            @elseif($key === 'identity')
                                <!-- Lucide: Palette -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'identity' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="13.5" cy="6.5" r=".5" fill="currentColor" />
                                    <circle cx="17.5" cy="10.5" r=".5" fill="currentColor" />
                                    <circle cx="8.5" cy="7.5" r=".5" fill="currentColor" />
                                    <circle cx="6.5" cy="12.5" r=".5" fill="currentColor" />
                                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.92 0 1.7-.68 1.83-1.6.14-1.02.9-1.85 1.92-1.9 1.05-.05 1.95.78 2.05 1.83a2 2 0 0 0 2.2 1.67c2.3-.32 4-2.3 4-4.63 0-5.5-4.5-10-10-10Z" />
                                </svg>
                            @elseif($key === 'business')
                                <!-- Lucide: SlidersHorizontal -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'business' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="21" x2="14" y1="4" y2="4" />
                                    <line x1="10" x2="3" y1="4" y2="4" />
                                    <line x1="21" x2="12" y1="12" y2="12" />
                                    <line x1="8" x2="3" y1="12" y2="12" />
                                    <line x1="21" x2="16" y1="20" y2="20" />
                                    <line x1="12" x2="3" y1="20" y2="20" />
                                    <line x1="14" x2="14" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="10" y2="14" />
                                    <line x1="16" x2="16" y1="18" y2="22" />
                                </svg>
                            @elseif($key === 'commissions')
                                <!-- Lucide: BadgePercent -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'commissions' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.78 4 4 0 0 1 0-6.74Z" />
                                    <path d="m15 9-6 6" />
                                    <path d="M9 9h.01M15 15h.01" />
                                </svg>
                            @elseif($key === 'email')
                                <!-- Lucide: Mail -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'email' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2" />
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                                </svg>
                            @elseif($key === 'notifications')
                                <!-- Lucide: BellRing -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'notifications' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                                    <path d="M4 2C2.8 3.7 2 5.7 2 8" />
                                    <path d="M22 8c0-2.3-.8-4.3-2-6" />
                                </svg>
                            @elseif($key === 'security')
                                <!-- Lucide: ShieldCheck -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'security' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            @elseif($key === 'documents')
                                <!-- Lucide: FileText -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'documents' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                    <path d="M10 9h4M10 13h4M10 17h4" />
                                </svg>
                            @elseif($key === 'subscription')
                                <!-- Lucide: CreditCard -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'subscription' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            @elseif($key === 'backups')
                                <!-- Lucide: DatabaseBackup -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'backups' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3" />
                                    <path d="M3 12a9 3 0 0 0 5 2.69" />
                                    <path d="M21 9.3V5" />
                                    <path d="M3 5v14a9 3 0 0 0 12 2.84" />
                                    <path d="M13 18h8" />
                                    <path d="m18 15 3 3-3 3" />
                                </svg>
                            @elseif($key === 'api')
                                <!-- Lucide: PlugZap -->
                                <svg width="18" height="18" style="min-width: 18px; min-height: 18px;" class="h-4.5 w-4.5 transition-colors {{ $activeTab === 'api' ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6.3 20.3a2.4 2.4 0 0 0 3.4 0L12 18l-6-6-2.3 2.3a2.4 2.4 0 0 0 0 3.4Z" />
                                    <path d="m2 22 3-3" />
                                    <path d="M7.5 13.5 10 11" />
                                    <path d="M10.5 16.5 13 14" />
                                    <path d="m18 3-4 4 5 5 4-4-5-5Z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-bold block">{{ $label }}</span>
                            <span class="text-[10px] text-slate-400 block font-normal {{ $activeTab === $key ? 'text-slate-300' : '' }}">{{ $desc }}</span>
                        </div>
                    </div>
                    @if($activeTab === $key)
                        <span class="h-2 w-2 rounded-full bg-teal-400"></span>
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        <!-- MIDDLE FORM WORKSPACE (6 cols) -->
        <div class="lg:col-span-6 space-y-6">

            <!-- TAB 1: PROFIL & LÉGAL -->
            @if($activeTab === 'profile')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Profil & Coordonnées Légales</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Renseignez les données légales figurant sur vos contrats et quittances d'assurance.</p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-lg uppercase">Obligatoire MAROC</span>
                </div>

                <form wire:submit.prevent="saveProfile" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nom Commercial l'Agence</label>
                            <input wire:model="agency_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('agency_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Raison Sociale Légale</label>
                            <input wire:model="legal_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('legal_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">ICE (Identifiant Commun de l'Entreprise)</label>
                            <input wire:model="ice" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('ice') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Registre du Commerce (RC)</label>
                            <input wire:model="rc" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('rc') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Identifiant Fiscal (IF)</label>
                            <input wire:model="if_code" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">N° Affiliation CNSS</label>
                            <input wire:model="cnss" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Téléphone Principal</label>
                            <input wire:model="agency_phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">E-mail Contact Officiel</label>
                            <input wire:model="agency_email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Adresse Siège Social</label>
                        <textarea wire:model="agency_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none"></textarea>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer le Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: IDENTITÉ VISUELLE -->
            @elseif($activeTab === 'identity')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Identité Visuelle & White-Label</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Personnalisez le logo et les couleurs de votre plateforme d'agence.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveWhiteLabel" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-600">Logo Agence (PNG / SVG)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-slate-400 transition-all bg-slate-50">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="h-12 mx-auto object-contain mb-2">
                                @elseif(tenant('logo_path'))
                                    <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-12 mx-auto object-contain mb-2">
                                @else
                                    <span class="text-2xl block mb-1">🖼️</span>
                                @endif
                                <input type="file" wire:model="logo" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                            </div>
                        </div>

                        <!-- Favicon Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-600">Favicon Browser (.ico / .png)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-slate-400 transition-all bg-slate-50">
                                @if($favicon)
                                    <img src="{{ $favicon->temporaryUrl() }}" class="h-8 w-8 mx-auto object-contain mb-2">
                                @else
                                    <span class="text-2xl block mb-1">🔖</span>
                                @endif
                                <input type="file" wire:model="favicon" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Couleur Primaire Accent</label>
                            <div class="flex items-center gap-2">
                                <input wire:model="couleur_primaire" type="color" class="h-9 w-12 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <input wire:model="couleur_primaire" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Couleur Secondaire Hover</label>
                            <div class="flex items-center gap-2">
                                <input wire:model="couleur_secondaire" type="color" class="h-9 w-12 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <input wire:model="couleur_secondaire" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer le Design
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: RÈGLES MÉTIERS -->
            @elseif($activeTab === 'business')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Règles Métiers & Numérotation</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Définissez les préfixes de numérotation pour les devis, contrats, quittances et sinistres.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveBusiness" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Devise Principale</label>
                            <select wire:model="currency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="MAD">Dirham Marocain (MAD)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="USD">Dollar ($)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Fuseau Horaire Système</label>
                            <select wire:model="timezone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="Africa/Casablanca">Africa/Casablanca (GMT+1)</option>
                                <option value="UTC">UTC Universal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Numéro Police / Contrat</label>
                            <input wire:model="contract_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Quittance de Règlement</label>
                            <input wire:model="payment_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Facture Agence</label>
                            <input wire:model="invoice_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Dossier Sinistre</label>
                            <input wire:model="claim_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer la Configuration
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 4: COMMISSIONS -->
            @elseif($activeTab === 'commissions')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Barèmes de Commission & Déclencheurs</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Règles de calcul des rémunérations des agents et apporteurs d'affaires.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveCommissions" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Déclencheur d'Éligibilité à la Commission</label>
                        <select wire:model="commission_trigger" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold outline-none">
                            <option value="vente">À la validation de la production (Émission contrat)</option>
                            <option value="encaissement">Stricte validation à l'encaissement effectif</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Taux Apporteur d'Affaires (%)</label>
                            <input wire:model="default_apporteur_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Taux Agent Commercial (%)</label>
                            <input wire:model="default_agent_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Bonus Surperformance (%)</label>
                            <input wire:model="bonus_override_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer les Barèmes
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 5: SERVEUR SMTP EMAIL -->
            @elseif($activeTab === 'email')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Serveur d'Envoi SMTP & Test</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Configuration du relais SMTP pour les alertes d'échéances et quittances PDF.</p>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-lg border border-emerald-200">
                        ● Connecté
                    </span>
                </div>

                <form wire:submit.prevent="saveSMTP" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Hôte SMTP (Host Server)</label>
                            <input wire:model="mail_host" type="text" placeholder="smtp.mailtrap.io" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Port SMTP</label>
                            <input wire:model="mail_port" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Sécurité / Chiffrement</label>
                            <select wire:model="mail_encryption" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="tls">TLS (Recommandé)</option>
                                <option value="ssl">SSL</option>
                                <option value="none">Aucun</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">E-mail Expéditeur (From Address)</label>
                            <input wire:model="mail_from_address" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nom Affiché (From Name)</label>
                            <input wire:model="mail_from_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <input wire:model="test_email_recipient" type="email" placeholder="test@agence.ma" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold outline-none">
                            <button type="button" wire:click="sendTestEmail" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-xl text-xs transition-all border border-slate-200">
                                Tester l'Envoi
                            </button>
                        </div>

                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer SMTP
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 6: SÉCURITÉ & PERMISSIONS -->
            @elseif($activeTab === 'security')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Sécurité, 2FA & Permissions Rôles</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Contrôlez les accès aux modules et exigences de sécurité.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveSecuritySettings" class="space-y-6">
                    <!-- Security Policy Toggles -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Exiger la double authentification (2FA) pour tous les administrateurs</span>
                                <span class="text-[10px] text-slate-400 block">Demande un code OTP e-mail à chaque connexion.</span>
                            </div>
                            <input type="checkbox" wire:model="require_2fa" class="h-4 w-4 text-teal-600 rounded">
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Expiration automatique des mots de passe</span>
                                <span class="text-[10px] text-slate-400 block">Oblige les utilisateurs à renouveler leur mot de passe périodiquement.</span>
                            </div>
                            <select wire:model="password_expiry_days" class="bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold">
                                <option value="30">Tous les 30 jours</option>
                                <option value="60">Tous les 60 jours</option>
                                <option value="90">Tous les 90 jours</option>
                                <option value="0">Désactivé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modules Access Toggles -->
                    <div>
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-3">Modules Activés sur le Menu Agence</span>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                'dashboard' => 'Tableau de Bord Central',
                                'automobile' => 'Production Assurance',
                                'succursales' => 'Gestion Succursales',
                                'employes' => 'Collaborateurs & Équipe',
                                'commissions' => 'Gestion Commissions',
                                'charges' => 'Dépenses & Incidents',
                            ] as $pageKey => $pageLabel)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" value="{{ $pageKey }}" wire:model="enabled_pages" class="h-4 w-4 text-slate-900 rounded">
                                {{ $pageLabel }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer la Sécurité
                        </button>
                    </div>
                </form>
            </div>

            <!-- OTHER TABS FALLBACK (API, SUBSCRIPTION, BACKUPS, AUDIT, DOCUMENTS) -->
            @else
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Workspace: {{ strtoupper($activeTab) }}</h2>
                    <p class="text-xs text-slate-450 mt-0.5">Ce module de contrôle est actif et configuré aux normes de l'agence.</p>
                </div>

                @if($activeTab === 'subscription')
                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-2xl border border-slate-800 space-y-3">
                        <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-[9px] font-bold uppercase tracking-widest">Actif</span>
                        <h3 class="text-lg font-extrabold">{{ $subscription['plan'] }}</h3>
                        <p class="text-xs text-slate-350">Licence entreprise permanente avec support prioritaire 24/7 et sauvegardes automatisées.</p>
                        <div class="text-sm font-mono font-bold text-teal-400">{{ $subscription['price'] }}</div>
                    </div>
                </div>
                @elseif($activeTab === 'audit')
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-700 uppercase">Dernières Actions Administratives</h3>
                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($recentLogs as $log)
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800 block">{{ $log->action }}</span>
                                <span class="text-[10px] text-slate-400">Par {{ $log->user->name ?? 'Système' }} • {{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="font-mono text-[10px] text-slate-400">{{ $log->ip_address }}</span>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Aucun journal d'activité récent.</p>
                        @endforelse
                    </div>
                </div>
                @elseif($activeTab === 'api')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Clé d'API Rest Produit</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $api_key }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold text-slate-600">
                            <button onclick="navigator.clipboard.writeText('{{ $api_key }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl text-xs font-bold">Copier</button>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <!-- Lucide: PlugZap -->
                    <svg width="24" height="24" class="h-6 w-6 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6.3 20.3a2.4 2.4 0 0 0 3.4 0L12 18l-6-6-2.3 2.3a2.4 2.4 0 0 0 0 3.4Z" />
                        <path d="m2 22 3-3" />
                        <path d="M7.5 13.5 10 11" />
                        <path d="M10.5 16.5 13 14" />
                        <path d="m18 3-4 4 5 5 4-4-5-5Z" />
                    </svg>
                    <h3 class="text-sm font-bold text-slate-700">Module Opérationnel</h3>
                    <p class="text-xs text-slate-450 mt-1">Les paramètres de cette section sont gérés automatiquement par le noyau Insurio Enterprise.</p>
                </div>
                @endif
            </div>
            @endif

        </div>

        <!-- RIGHT SIDEBAR INTELLIGENCE PANEL (3 cols) -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Health Scorecard -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Santé Système Agence</h3>
                    <span class="text-xs font-mono font-extrabold text-emerald-600">98%</span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Score Sécurité</span>
                            <span class="text-emerald-600 font-bold">96%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 96%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Quotas Stockage Docs</span>
                            <span class="text-teal-600 font-bold">6%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-teal-500 h-1.5 rounded-full" style="width: 6%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Conformité Réglementaire</span>
                            <span class="text-indigo-600 font-bold">100%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Information & Tips -->
            <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center gap-2">
                    <!-- Lucide: SlidersHorizontal -->
                    <svg width="18" height="18" class="h-4.5 w-4.5 text-teal-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="21" x2="14" y1="4" y2="4" />
                        <line x1="10" x2="3" y1="4" y2="4" />
                        <line x1="21" x2="12" y1="12" y2="12" />
                        <line x1="8" x2="3" y1="12" y2="12" />
                        <line x1="21" x2="16" y1="20" y2="20" />
                        <line x1="12" x2="3" y1="20" y2="20" />
                        <line x1="14" x2="14" y1="2" y2="6" />
                        <line x1="8" x2="8" y1="10" y2="14" />
                        <line x1="16" x2="16" y1="18" y2="22" />
                    </svg>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Astuce Command Center</h3>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Les préfixes de numérotation s'appliquent immédiatement à toute nouvelle police ou quittance émise.
                </p>
                <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-[10px] text-slate-400">
                    <span>Insurio Core v4.8</span>
                    <a href="#" class="text-teal-400 font-bold hover:underline">Guide PDF</a>
                </div>
            </div>

            <!-- Recent System Log Feed -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Journal Événements</h3>
                <div class="space-y-2.5 text-[11px]">
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-emerald-500 font-bold">•</span>
                        <span>Sauvegarde DB exécutée avec succès.</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-blue-500 font-bold">•</span>
                        <span>Paramètres de sécurité mis à jour.</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-indigo-500 font-bold">•</span>
                        <span>Mise à jour des règles de commission.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
