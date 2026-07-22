<!-- Official Unified Super Admin Header Component -->
<header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-20 flex-shrink-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Search Bar -->
        <div class="relative hidden sm:block w-72">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs">🔍</span>
            <input type="text" placeholder="Rechercher agence, facture, domaine..." class="w-full bg-slate-50 border border-slate-200/80 rounded-xl pl-8 pr-4 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notification Center Badge -->
        <button class="relative p-2 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-600 hover:bg-slate-100 transition">
            <span class="text-xs">🔔</span>
            <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-teal-500 ring-2 ring-white"></span>
        </button>

        <!-- Super Admin Badge -->
        <div class="hidden sm:flex items-center gap-2 bg-indigo-50 text-indigo-900 border border-indigo-200/60 rounded-full px-3 py-1 text-xs font-bold">
            <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
            Super Admin Console
        </div>
    </div>
</header>
