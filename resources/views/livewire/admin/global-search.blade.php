<div class="relative w-full" x-data="{ focused: false }" @click.outside="$wire.close()">
    {{-- Search input --}}
    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 w-full transition-all"
         :class="focused ? 'border-teal-400 ring-2 ring-teal-100 bg-white' : ''">
        <svg class="h-4 w-4 text-slate-400 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="query"
            @focus="focused = true"
            @blur="focused = false"
            placeholder="Rechercher client, contrat, police..."
            class="bg-transparent border-none outline-none text-xs w-full text-slate-700 placeholder-slate-400 p-0 focus:ring-0"
        >
        @if($query)
            <button wire:click="close" class="ml-1 text-slate-400 hover:text-slate-600">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        @endif
    </div>

    {{-- Dropdown results --}}
    @if($isOpen && count($results) > 0)
        <div class="absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-2xl border border-slate-200 z-50 overflow-hidden"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="p-2 space-y-0.5 max-h-80 overflow-y-auto">
                @foreach($results as $result)
                    <a href="{{ $result['url'] }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                            {{ $result['type'] === 'client' ? 'bg-blue-50 text-blue-600' : 'bg-teal-50 text-teal-600' }}">
                            @if($result['type'] === 'client')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-semibold text-slate-800 group-hover:text-teal-700 truncate">{{ $result['label'] }}</div>
                            <div class="text-[10px] text-slate-400 truncate">{{ $result['sub'] }}</div>
                        </div>
                        <span class="text-[9px] font-medium uppercase tracking-wider px-1.5 py-0.5 rounded-full
                            {{ $result['type'] === 'client' ? 'bg-blue-100 text-blue-600' : 'bg-teal-100 text-teal-600' }}">
                            {{ $result['type'] === 'client' ? 'Client' : 'Contrat' }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="border-t border-slate-100 px-3 py-2 text-center">
                <span class="text-[10px] text-slate-400">{{ count($results) }} résultat(s) — Tapez pour affiner</span>
            </div>
        </div>
    @elseif($query && strlen($query) >= 2 && count($results) === 0)
        <div class="absolute top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-200 z-50 p-4 text-center">
            <svg class="h-8 w-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-xs text-slate-500">Aucun résultat pour <strong>{{ $query }}</strong></p>
        </div>
    @endif
</div>
