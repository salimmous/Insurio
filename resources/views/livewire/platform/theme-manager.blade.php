<div class="p-8 max-w-[1600px] mx-auto space-y-8 font-sans bg-[#F8FAFC]">
    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('message') }}</span>
            </div>
            <button class="text-emerald-500 hover:text-emerald-900 text-xs font-bold" @click="$el.parentElement.remove()">✕</button>
        </div>
    @endif

    <!-- Clean Minimalist Header (Shopify / Framer Theme Store style) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-[#E5E7EB] pb-6">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Theme Marketplace Engine V3</h1>
                <span class="px-3 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-bold rounded-full">
                    15 Premium Themes
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Marketplace officielle de thèmes d'assurance White Label prêts pour la production. Prévisualisations interactives 100% réelles.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="$set('showEditModal', true)" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-xs transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Create Custom Theme</span>
            </button>
        </div>
    </div>

    <!-- Theme Store 3-Column Grid (15 Distinct Themes) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($themes as $theme)
        @php
            $colors = is_array($theme->colors) ? $theme->colors : json_decode($theme->colors ?? '[]', true);
            $configComp = is_array($theme->components_config) ? $theme->components_config : json_decode($theme->components_config ?? '[]', true);
            $primary = $colors['primary'] ?? '#1E40AF';
            $secondary = $colors['secondary'] ?? '#3B82F6';
            $bg = $colors['bg'] ?? '#FFFFFF';
            $accent = $colors['accent'] ?? '#2563EB';
        @endphp
        <div class="bg-white border border-[#E5E7EB] rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
            
            <!-- REAL LIVE PREVIEW IFRAME CONTAINER -->
            <div class="h-64 relative border-b border-[#E5E7EB] bg-slate-50 overflow-hidden" x-data="{ scrolling: false }" @mouseenter="scrolling = true" @mouseleave="scrolling = false">
                
                <!-- Badges -->
                <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-20 pointer-events-none">
                    <span class="px-2.5 py-1 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-bold rounded-lg border border-slate-200 shadow-xs">
                        v{{ $theme->version }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] font-bold rounded-full">
                            100% Live Website
                        </span>
                    </div>
                </div>

                <!-- Scaled Live iFrame Preview Rendering Actual Website -->
                <div class="w-[200%] h-[300%] transform scale-50 origin-top-left pointer-events-none transition-transform duration-1000 ease-in-out" :class="scrolling ? '-translate-y-1/3' : 'translate-y-0'">
                    <iframe src="{{ route('platform.theme.preview', $theme->slug) }}" class="w-full h-full border-0"></iframe>
                </div>

                <!-- Hover Overlay Trigger for Interactive Live Preview -->
                <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 z-30">
                    <button wire:click="openPreviewModal({{ $theme->id }})" class="bg-white text-slate-900 font-bold text-xs px-5 py-2.5 rounded-xl shadow-md hover:bg-slate-50 transition flex items-center gap-2 border border-slate-200">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Live Demo</span>
                    </button>
                </div>
            </div>

            <!-- Theme Meta Specs -->
            <div class="p-6 space-y-4">
                <div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-base text-slate-900 tracking-tight">{{ $theme->name }}</h3>
                        <span class="text-[10px] text-slate-400 font-medium">Insurio Studio</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed font-medium">{{ $theme->description }}</p>
                </div>

                <!-- Specs Grid -->
                <div class="grid grid-cols-2 gap-2 text-[10px] text-slate-600 font-semibold bg-slate-50/70 p-3 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span>12+ Components</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <span>7 Pages Included</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        <span>FR 🇫🇷 / AR 🇲🇦 RTL</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/></svg>
                        <span>SEO 98/100</span>
                    </div>
                </div>

                <!-- Palette -->
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Palette</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded-full border border-slate-200" style="background-color: {{ $primary }}" title="Primary"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200" style="background-color: {{ $secondary }}" title="Secondary"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200" style="background-color: {{ $bg }}" title="Background"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200" style="background-color: {{ $accent }}" title="Accent"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 pt-0 space-y-2">
                <button wire:click="openAssignModal({{ $theme->id }})" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3 rounded-xl transition shadow-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    <span>Assign To Agency</span>
                </button>

                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="openDetailsModal({{ $theme->id }})" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-[11px] py-2 rounded-xl border border-slate-200 transition text-center">
                        Details Specs
                    </button>
                    <button wire:click="editTheme({{ $theme->id }})" class="w-full bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-[11px] py-2 rounded-xl border border-slate-200 transition text-center">
                        Customize Tokens
                    </button>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- ASSIGNMENT MODAL -->
    @if($showAssignModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-lg w-full p-6 space-y-6 shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Workflow Affectation • Étape {{ $assignStep }}/2</span>
                    <h2 class="text-lg font-bold text-slate-900 mt-0.5">Appliquer "{{ $targetTheme->name }}"</h2>
                </div>
                <button wire:click="$set('showAssignModal', false)" class="text-slate-400 hover:text-slate-700 text-lg font-bold">✕</button>
            </div>

            @if($assignStep === 1)
            <div class="space-y-4">
                <div class="relative">
                    <span class="absolute left-3.5 top-3 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                    </span>
                    <input type="text" wire:model.live="searchAgency" placeholder="Rechercher agence par nom ou sous-domaine..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-800 focus:ring-2 focus:ring-indigo-500/20 font-medium">
                </div>

                <div class="max-h-64 overflow-y-auto space-y-2 pr-1 text-xs">
                    @forelse($filteredTenants as $tenant)
                    <div wire:click="selectAgencyForAssign('{{ $tenant->id }}')" class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition border-slate-200 hover:border-indigo-500 hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                {{ substr($tenant->name ?? $tenant->id, 0, 1) }}
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900">{{ $tenant->name ?? $tenant->id }}</span>
                                <span class="block text-[10px] text-slate-400 font-mono">{{ $tenant->id }}.sc7mosa1422.universe.wf</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-indigo-600">Sélectionner →</span>
                    </div>
                    @empty
                    <div class="text-center py-6 text-slate-400 text-xs font-medium">
                        Aucune agence trouvée pour "{{ $searchAgency }}".
                    </div>
                    @endforelse
                </div>
            </div>

            @elseif($assignStep === 2 && $selectedTenant)
            <div class="space-y-4 text-xs">
                <div class="p-4 bg-slate-50 rounded-xl space-y-1.5 border border-slate-200">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Agence Sélectionnée</span>
                        <button wire:click="$set('assignStep', 1)" class="text-[10px] font-bold text-indigo-600 hover:underline">Changer d'agence</button>
                    </div>
                    <div class="text-base font-bold text-slate-900">{{ $selectedTenant->name ?? $selectedTenant->id }}</div>
                    <div class="text-xs text-slate-500 font-mono">{{ $selectedTenant->id }}.sc7mosa1422.universe.wf</div>
                </div>

                <div class="p-4 bg-indigo-50/60 text-indigo-950 rounded-xl border border-indigo-100 font-medium space-y-1">
                    <div class="font-bold text-sm text-indigo-900">Vérification de Publication Live</div>
                    <p class="text-xs text-indigo-800">Le thème <strong>{{ $targetTheme->name }}</strong> sera appliqué live immédiatement sur le sous-domaine agence.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button wire:click="$set('assignStep', 1)" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900">Retour</button>
                    <button wire:click="confirmAssignToAgency" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-xs transition">
                        Confirm & Publish Live
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- LIVE PREVIEW MODAL -->
    @if($showPreviewModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex flex-col p-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-4 flex items-center justify-between text-slate-900 max-w-5xl mx-auto w-full shadow-lg">
            <div class="flex items-center gap-3">
                <span class="font-bold text-sm text-slate-900">{{ $targetTheme->name }}</span>
                <span class="text-xs text-slate-400 font-mono">v{{ $targetTheme->version }}</span>
            </div>

            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl border border-slate-200">
                <button wire:click="$set('previewDevice', 'desktop')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $previewDevice === 'desktop' ? 'bg-white text-indigo-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Desktop (100%)
                </button>
                <button wire:click="$set('previewDevice', 'tablet')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $previewDevice === 'tablet' ? 'bg-white text-indigo-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Tablet (768px)
                </button>
                <button wire:click="$set('previewDevice', 'mobile')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition {{ $previewDevice === 'mobile' ? 'bg-white text-indigo-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Mobile (375px)
                </button>
            </div>

            <button wire:click="$set('showPreviewModal', false)" class="bg-slate-100 hover:bg-slate-200 text-slate-800 px-4 py-1.5 rounded-xl text-xs font-bold transition">
                Close Preview ✕
            </button>
        </div>

        <div class="flex-1 flex justify-center items-center overflow-hidden">
            <div class="h-full transition-all duration-300 shadow-xl rounded-2xl overflow-hidden border border-slate-300 bg-white"
                 style="width: {{ $previewDevice === 'mobile' ? '375px' : ($previewDevice === 'tablet' ? '768px' : '100%') }}">
                <iframe src="{{ route('platform.theme.preview', $targetTheme->slug) }}" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>
    @endif

    <!-- DETAILS MODAL -->
    @if($showDetailsModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-2xl w-full p-6 space-y-6 shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Specifications</span>
                    <h2 class="text-xl font-bold text-slate-900 mt-0.5">{{ $targetTheme->name }}</h2>
                </div>
                <button wire:click="$set('showDetailsModal', false)" class="text-slate-400 hover:text-slate-700 text-lg font-bold">✕</button>
            </div>

            <div class="space-y-4 text-xs font-medium">
                <p class="text-slate-600 leading-relaxed">{{ $targetTheme->description }}</p>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Author</span>
                        <span class="font-bold text-slate-900 block">{{ $targetTheme->author ?? 'Insurio Studio' }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Version</span>
                        <span class="font-bold text-slate-900 block">v{{ $targetTheme->version }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Icons Library</span>
                        <span class="font-bold text-slate-900 block">Lucide Outline Icons</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Typography</span>
                        <span class="font-bold text-slate-900 block">Plus Jakarta Sans</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button wire:click="$set('showDetailsModal', false)" class="bg-slate-900 text-white font-bold text-xs px-6 py-2.5 rounded-xl">Close</button>
            </div>
        </div>
    </div>
    @endif
</div>
