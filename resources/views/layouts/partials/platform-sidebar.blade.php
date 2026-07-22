<!-- Official Unified Enterprise Super Admin Sidebar (Stripe / Linear / Vercel style) -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300 select-none">
    
    <!-- Logo Block -->
    <div class="h-16 flex items-center px-5 border-b border-[#1E293B]">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-teal-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-teal-500/20 font-bold text-sm">
                I
            </div>
            <div>
                <span class="text-sm font-black text-white tracking-tight block">Insurio Central</span>
                <span class="text-[9px] text-teal-400 font-extrabold uppercase tracking-widest block -mt-0.5">Enterprise Platform</span>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="p-3.5 mx-3.5 my-3.5 bg-[#1E293B]/60 rounded-xl border border-[#334155]/40 flex items-center gap-3">
        <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white text-xs shadow-inner flex-shrink-0">
            SA
        </div>
        <div class="overflow-hidden flex-1">
            <div class="font-extrabold text-xs text-white truncate">ELISSI SUPER ADMIN</div>
            <div class="text-[9px] text-slate-400 font-bold uppercase tracking-wider block">Platform Owner</div>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[9px] text-emerald-400 font-extrabold tracking-wide">Platform Online</span>
            </div>
        </div>
    </div>

    <!-- Navigation List grouped by Business Sections -->
    <nav class="flex-1 px-3 space-y-4 overflow-y-auto pb-6 text-xs scrollbar-none">
        
        <!-- DASHBOARD -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">DASHBOARD</span>
            <a href="{{ route('platform.dashboard') }}" class="group flex items-center justify-between px-3 py-2 font-semibold rounded-xl transition-all relative {{ Route::is('platform.dashboard') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    @if(Route::is('platform.dashboard'))<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] stroke-[1.75] transition-colors {{ Route::is('platform.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    <span>Console Centrale</span>
                </div>
            </a>
        </div>

        <!-- TENANT MANAGEMENT -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">TENANT MANAGEMENT</span>
            <div class="space-y-0.5">
                <a href="{{ route('platform.tenants.create') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ Route::is('platform.tenants.create') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(Route::is('platform.tenants.create'))<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ Route::is('platform.tenants.create') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    <span>Nouvelle Agence</span>
                </a>

                <a href="{{ route('platform.module', 'agencies') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'agencies') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'agencies')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'agencies') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2 22h20"/><path d="M16 2v20"/><path d="M8 6v16"/><path d="M12 10v12"/><path d="M12 2v4"/><path d="M4 14v8"/><path d="M20 10v12"/></svg>
                    <span>Cabinets & Agences</span>
                </a>

                <a href="{{ route('platform.themes') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ Route::is('platform.themes') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(Route::is('platform.themes'))<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ Route::is('platform.themes') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.92 0 1.7-.75 1.7-1.67 0-.42-.16-.82-.45-1.12-.29-.3-.45-.7-.45-1.12 0-.92.75-1.67 1.67-1.67H17c2.76 0 5-2.24 5-5 0-4.42-4.48-8-10-8Z"/></svg>
                    <span>Engine Thèmes Web</span>
                </a>
            </div>
        </div>

        <!-- BILLING -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">BILLING</span>
            <div class="space-y-0.5">
                <a href="{{ route('platform.module', 'subscriptions') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'subscriptions') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'subscriptions')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'subscriptions') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <span>Abonnements</span>
                </a>

                <a href="{{ route('platform.module', 'plans') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'plans') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'plans')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'plans') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.78 4.78 4 4 0 0 1-6.74 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="M12 8v8"/></svg>
                    <span>Plans Tarifaires</span>
                </a>

                <a href="{{ route('platform.module', 'invoices') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'invoices') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'invoices')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'invoices') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a1 1 0 0 0 1 1h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    <span>Factures Agences</span>
                </a>

                <a href="{{ route('platform.module', 'payments') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'payments') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'payments')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'payments') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="12" x="3" y="6" rx="2"/><path d="M3 10h18"/><path d="M7 15h.01"/></svg>
                    <span>Paiements Reçus</span>
                </a>
            </div>
        </div>

        <!-- INFRASTRUCTURE -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">INFRASTRUCTURE</span>
            <div class="space-y-0.5">
                <a href="{{ route('platform.module', 'domains') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'domains') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'domains')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'domains') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    <span>Domaines & DNS</span>
                </a>

                <a href="{{ route('platform.expenses.index') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ Route::is('platform.expenses.index') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(Route::is('platform.expenses.index'))<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ Route::is('platform.expenses.index') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 6v12"/></svg>
                    <span>Charges Plateforme</span>
                </a>

                <a href="{{ route('platform.module', 'storage') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'storage') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'storage')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'storage') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="22" x2="2" y1="12" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    <span>Stockage</span>
                </a>

                <a href="{{ route('platform.module', 'backups') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'backups') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'backups')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'backups') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>
                    <span>Sauvegardes</span>
                </a>

                <a href="{{ route('platform.module', 'monitoring') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'monitoring') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'monitoring')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'monitoring') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/></svg>
                    <span>Monitoring & Health</span>
                </a>

                <a href="{{ route('platform.module', 'queues') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'queues') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'queues')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'queues') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="8" height="8" x="3" y="3" rx="2"/><path d="M7 11v4a2 2 0 0 0 2 2h4"/><rect width="8" height="8" x="13" y="13" rx="2"/></svg>
                    <span>Queues & Jobs</span>
                </a>
            </div>
        </div>

        <!-- SUPPORT -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">SUPPORT</span>
            <div class="space-y-0.5">
                <a href="{{ route('platform.module', 'tickets') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'tickets') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'tickets')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'tickets') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 11h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5Zm0 0a9 9 0 1 1 18 0m0 0h-3a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2v-5Z"/></svg>
                    <span>Tickets Support</span>
                </a>

                <a href="{{ route('platform.module', 'activity') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'activity') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'activity')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'activity') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/></svg>
                    <span>Logs Audit</span>
                </a>
            </div>
        </div>

        <!-- PLATFORM -->
        <div>
            <span class="text-[9px] font-extrabold uppercase text-slate-500 tracking-widest block px-3 mb-1">PLATFORM</span>
            <div class="space-y-0.5">
                <a href="{{ route('platform.module', 'marketing') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'marketing') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'marketing')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'marketing') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                    <span>Marketing & Affiliés</span>
                </a>

                <a href="{{ route('platform.module', 'feature-flags') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'feature-flags') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'feature-flags')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'feature-flags') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                    <span>Feature Flags</span>
                </a>

                <a href="{{ route('platform.module', 'api') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'api') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'api')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'api') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/></svg>
                    <span>API & Clés Secrètes</span>
                </a>

                <a href="{{ route('platform.module', 'webhooks') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'webhooks') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'webhooks')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'webhooks') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 3v12"/><path d="M18 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M6 21a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M18 6a6 6 0 0 1-6 6H6"/></svg>
                    <span>Webhooks</span>
                </a>

                <a href="{{ route('platform.module', 'templates') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'templates') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'templates')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'templates') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Modèles WhatsApp / SMS</span>
                </a>

                <a href="{{ route('platform.module', 'settings') }}" class="group flex items-center px-3 py-2 font-semibold rounded-xl transition-all relative {{ (isset($moduleName) && $moduleName === 'settings') ? 'bg-[#1E293B] text-white shadow-sm' : 'text-slate-400 hover:bg-[#1E293B]/40 hover:text-white' }}">
                    @if(isset($moduleName) && $moduleName === 'settings')<span class="absolute left-0 top-1.5 bottom-1.5 w-1 bg-teal-400 rounded-r-md"></span>@endif
                    <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] transition-colors {{ (isset($moduleName) && $moduleName === 'settings') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="7" r="3"/><circle cx="7" cy="17" r="3"/></svg>
                    <span>Paramètres Plateforme</span>
                </a>
            </div>
        </div>

    </nav>

    <!-- Logout Block -->
    <div class="p-3.5 border-t border-[#1E293B]/60 mt-auto bg-[#090D16]">
        <form method="POST" action="{{ route('platform.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-bold rounded-xl text-rose-400 hover:bg-rose-950/30 transition-all group">
                <svg class="w-[18px] h-[18px] mr-3 stroke-[1.75] text-rose-400 group-hover:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
