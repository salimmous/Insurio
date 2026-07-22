<!-- Official Unified Super Admin Header Component -->
<header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-20 flex-shrink-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        </button>

        <!-- Global Search Bar with CTRL + K Badge -->
        <div class="relative hidden sm:flex items-center w-80">
            <span class="absolute left-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
            </span>
            <input type="text" placeholder="Rechercher agence, factures, thèmes..." class="w-full bg-slate-50 border border-slate-200/80 rounded-xl pl-9 pr-14 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            <kbd class="absolute right-2.5 bg-slate-200/60 border border-slate-300/60 text-slate-500 font-mono text-[9px] font-bold px-1.5 py-0.5 rounded">⌘K</kbd>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Notification Center -->
        <button class="relative p-2 rounded-xl bg-slate-50 border border-slate-200/80 text-slate-600 hover:bg-slate-100 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-teal-500 ring-2 ring-white"></span>
        </button>

        <!-- Super Admin Status Badge -->
        <div class="hidden sm:flex items-center gap-2 bg-indigo-50 text-indigo-900 border border-indigo-200/60 rounded-full px-3 py-1 text-xs font-bold">
            <span class="h-2 w-2 rounded-full bg-indigo-600 animate-pulse"></span>
            Super Admin Console
        </div>
    </div>
</header>
