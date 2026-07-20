<div x-data="{ open: @entangle('isOpen') }" 
     @keydown.window.prevent.ctrl.k="$wire.toggle()" 
     @keydown.window.prevent.cmd.k="$wire.toggle()"
     x-show="open" 
     class="fixed inset-0 overflow-y-auto p-4 pt-[15vh] z-50" 
     style="display: none;">
    
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="open = false"></div>

    <!-- Modal Dialog -->
    <div class="relative max-w-lg mx-auto bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
        <!-- Search Input -->
        <div class="flex items-center px-4 py-3 border-b border-slate-200">
            <span class="text-slate-400 text-sm">🔍</span>
            <input type="text" 
                   wire:model.live="search"
                   x-ref="searchInput"
                   placeholder="Rechercher un client, contrat, ou menu... (Esc pour fermer)" 
                   class="w-full bg-transparent border-0 outline-none text-xs text-slate-800 px-3 py-1.5 focus:ring-0">
            <span class="bg-slate-100 border border-slate-200 rounded px-1 text-[9px] font-mono text-slate-450 shadow-sm">ESC</span>
        </div>

        <!-- Search Results -->
        <div class="max-h-[350px] overflow-y-auto p-4 space-y-4">
            @if(empty($clients) && empty($contracts) && empty($pages))
                <div class="text-center text-xs text-slate-450 py-6">
                    Tapez au moins 2 caractères pour rechercher...
                </div>
            @endif

            <!-- Pages Results -->
            @if(!empty($pages))
                <div class="space-y-1.5">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Pages & Raccourcis</span>
                    @foreach($pages as $page)
                        <a href="{{ $page['url'] }}" class="flex items-center px-3 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/40 rounded-xl transition-all text-xs font-semibold text-slate-700">
                            {{ $page['name'] }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Clients Results -->
            @if(!empty($clients))
                <div class="space-y-1.5">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Clients CRM</span>
                    @foreach($clients as $c)
                        <a href="{{ route('admin.clients.profile', $c['id']) }}" class="flex items-center justify-between px-3 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/40 rounded-xl transition-all text-xs font-semibold text-slate-700">
                            <span>👤 {{ $c['first_name'] }} {{ $c['last_name'] }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $c['phone'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Contracts Results -->
            @if(!empty($contracts))
                <div class="space-y-1.5">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Contrats d'Assurances</span>
                    @foreach($contracts as $con)
                        <a href="{{ route('automobile.index') }}?search={{ $con['contract_number'] }}" class="flex items-center justify-between px-3 py-2 bg-slate-50 hover:bg-indigo-50 border border-slate-200/40 rounded-xl transition-all text-xs font-semibold text-slate-700">
                            <span class="font-mono">📄 #{{ $con['contract_number'] }}</span>
                            <span class="font-mono font-bold text-teal-650">{{ number_format($con['premium_amount'], 2) }} DH</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
