<!-- Official Unified Super Admin Platform Sidebar Component (Stripe / GitHub Enterprise style) -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
    <!-- Logo Block -->
    <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-teal-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-teal-500/20 font-bold text-sm">
                I
            </div>
            <span class="text-base font-extrabold text-white tracking-tight">Insurio Central</span>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="p-3.5 mx-4 my-4 bg-[#1E293B]/60 rounded-xl border border-[#334155]/40 flex items-center gap-3">
        <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white text-xs shadow-inner">
            SA
        </div>
        <div class="overflow-hidden">
            <div class="font-bold text-xs text-white truncate">{{ Auth::guard('platform')->user()->name ?? 'Super Admin' }}</div>
            <div class="text-[9px] text-teal-400 font-extrabold uppercase tracking-widest mt-0.5">Landlord Admin</div>
        </div>
    </div>

    <!-- Centralized Navigation Menu -->
    <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto pb-6 scrollbar-none text-xs">
        
        <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-2 pb-1">PILOTAGE & AGENCES</span>
        
        <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ Route::is('platform.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">📊</span> Console Centrale
        </a>

        <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ Route::is('platform.tenants.create') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">➕</span> Nouvelle Agence
        </a>

        <a href="{{ route('platform.themes') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ Route::is('platform.themes') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🎨</span> Engine Thèmes Web
        </a>

        <a href="{{ route('platform.module', 'agencies') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'agencies') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🏢</span> Cabinet & Agences
        </a>

        <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-4 pb-1">FACTURATION & PLANS</span>

        <a href="{{ route('platform.module', 'subscriptions') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'subscriptions') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🔁</span> Abonnements
        </a>

        <a href="{{ route('platform.module', 'plans') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'plans') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🎟️</span> Plans Tarifaires
        </a>

        <a href="{{ route('platform.module', 'invoices') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'invoices') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🧾</span> Factures Agences
        </a>

        <a href="{{ route('platform.module', 'payments') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'payments') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">💳</span> Paiements Reçus
        </a>

        <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-4 pb-1">RÉSEAU & INFRASTRUCTURE</span>

        <a href="{{ route('platform.module', 'domains') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'domains') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🌐</span> Domaines & DNS
        </a>

        <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ Route::is('platform.expenses.index') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">💰</span> Charges Plateforme
        </a>

        <a href="{{ route('platform.module', 'storage') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'storage') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">📦</span> Stockage & Storage
        </a>

        <a href="{{ route('platform.module', 'backups') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'backups') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">💾</span> Sauvegardes
        </a>

        <a href="{{ route('platform.module', 'monitoring') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'monitoring') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🚦</span> Monitoring & Health
        </a>

        <a href="{{ route('platform.module', 'queues') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'queues') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">⚡</span> Queues & Jobs
        </a>

        <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-4 pb-1">SUPPORT & LOGS</span>

        <a href="{{ route('platform.module', 'tickets') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'tickets') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🎫</span> Tickets Support
        </a>

        <a href="{{ route('platform.module', 'activity') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'activity') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">📜</span> Logs Audit
        </a>

        <span class="text-[9px] font-bold uppercase text-slate-500 tracking-widest block px-3 pt-4 pb-1">MARKETING & CONFIGURATIONS</span>

        <a href="{{ route('platform.module', 'marketing') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'marketing') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">📣</span> Marketing & Affiliés
        </a>

        <a href="{{ route('platform.module', 'feature-flags') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'feature-flags') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🚩</span> Feature Flags
        </a>

        <a href="{{ route('platform.module', 'api') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'api') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🔑</span> API & Clés Secrètes
        </a>

        <a href="{{ route('platform.module', 'webhooks') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'webhooks') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">🪝</span> Webhooks
        </a>

        <a href="{{ route('platform.module', 'templates') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'templates') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">✉️</span> Modèles Whatsapp/SMS
        </a>

        <a href="{{ route('platform.module', 'settings') }}" class="flex items-center px-3 py-2 font-semibold rounded-xl transition-all {{ (isset($moduleName) && $moduleName === 'settings') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-[#1E293B]/50 hover:text-white' }}">
            <span class="mr-2.5">⚙️</span> Paramètres Plateforme
        </a>
    </nav>

    <!-- Logout Block -->
    <div class="p-4 border-t border-[#1E293B]/60 mt-auto bg-[#090D16]">
        <form method="POST" action="{{ route('platform.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-bold rounded-xl text-rose-400 hover:bg-rose-950/30 transition-all">
                <span class="mr-2.5">🔌</span> Déconnexion
            </button>
        </form>
    </div>
</aside>
