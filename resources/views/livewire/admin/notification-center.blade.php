<div class="relative" @click.outside="$wire.close()">
    {{-- Bell Button --}}
    <button wire:click="toggle"
            class="relative p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all"
            style="position: relative;">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(count($notifications) > 0)
            <span class="absolute h-4 w-4 text-[9px] font-bold bg-rose-500 text-white rounded-full flex items-center justify-center animate-pulse"
                  style="position: absolute; top: -2px; right: -2px; display: flex; align-items: center; justify-content: center;">
                {{ min(count($notifications), 9) }}{{ count($notifications) > 9 ? '+' : '' }}
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    @if($isOpen)
        <div class="absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            {{-- Header --}}
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-slate-800">Notifications</span>
                    @if(count($notifications) > 0)
                        <span class="text-xs bg-rose-100 text-rose-600 font-semibold px-2 py-0.5 rounded-full">
                            {{ count($notifications) }}
                        </span>
                    @endif
                </div>
                <button wire:click="close" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Notification List --}}
            <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                @forelse($notifications as $notif)
                    <a href="{{ $notif['url'] }}"
                       class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors group">
                        {{-- Icon --}}
                        <div class="flex-shrink-0 mt-0.5 w-8 h-8 rounded-xl flex items-center justify-center
                            {{ $notif['type'] === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }}">
                            @if($notif['icon'] === 'clock')
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 group-hover:text-teal-700">{{ $notif['title'] }}</p>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $notif['message'] }}</p>
                        </div>

                        {{-- Time --}}
                        <span class="text-[10px] text-slate-400 flex-shrink-0 mt-0.5">{{ $notif['time'] }}</span>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center">
                        <svg class="h-10 w-10 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-xs text-slate-400 font-medium">Aucune notification</p>
                        <p class="text-[10px] text-slate-300 mt-1">Tout est à jour ✓</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer --}}
            @if(count($notifications) > 0)
                <div class="border-t border-slate-100 px-4 py-2.5 text-center">
                    <a href="{{ route('dashboard') }}" class="text-xs text-teal-600 font-semibold hover:text-teal-800">
                        Voir le tableau de bord →
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
