<div class="p-6 space-y-6 font-sans">
    @if (session()->has('message'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Theme Marketplace</h1>
                <span class="px-2.5 py-0.5 bg-indigo-50 border border-indigo-200 text-indigo-700 text-[10px] font-black rounded-full uppercase">10 Themes Ready</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Catalogue officiel des thèmes White Label prêts pour la production. Prévisualisez, évaluez et affectez en 1 clic.</p>
        </div>

        <button wire:click="$set('showEditModal', true)" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span>Nouveau Thème Sur-Mesure</span>
        </button>
    </div>

    <!-- Theme Store Grid (Framer / Shopify Store style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($themes as $theme)
        <?php
            $colors = $theme->colors ?? [];
            $configComp = $theme->components_config ?? [];
            $primary = $colors['primary'] ?? '#1E40AF';
            $secondary = $colors['secondary'] ?? '#3B82F6';
            $bg = $colors['bg'] ?? '#0F172A';
            $cardBg = $colors['card_bg'] ?? '#1E293B';
            $accent = $colors['accent'] ?? '#38BDF8';
            $isDark = $configComp['dark'] ?? true;
        ?>
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
            
            <!-- Card Image Preview Thumbnail (Interactive Device Frame Preview) -->
            <div class="p-4 relative border-b border-slate-100 overflow-hidden" style="background-color: {{ $bg }}">
                <!-- Viewport Mode Badges -->
                <div class="flex items-center justify-between mb-3 z-10 relative">
                    <span class="px-2.5 py-0.5 bg-slate-900/80 backdrop-blur-md text-white text-[9px] font-extrabold rounded-full border border-slate-700">
                        v{{ $theme->version }}
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="px-2 py-0.5 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[8.5px] font-black rounded-full uppercase">
                            100% Responsive
                        </span>
                        @if($theme->is_locked)
                            <span class="px-2 py-0.5 bg-rose-500/20 border border-rose-500/30 text-rose-400 text-[8.5px] font-black rounded-full">
                                🔒 Locked
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Simulated Website Hero Wireframe Viewport -->
                <div class="rounded-2xl p-4 space-y-3 shadow-2xl transition-transform duration-300 group-hover:scale-[1.02]" style="background-color: {{ $cardBg }}; border: 1px solid {{ $primary }}40">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $primary }}"></span>
                            <span class="text-[10px] font-black text-white">Agence Assurance</span>
                        </div>
                        <span class="text-[8px] font-bold px-2 py-0.5 rounded text-white" style="background-color: {{ $primary }}">Devis</span>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <div class="h-3 w-3/4 rounded font-black text-white text-[10px] flex items-center" style="color: {{ $colors['text'] ?? '#fff' }}">
                            {{ $theme->name }}
                        </div>
                        <div class="h-2 w-full rounded bg-slate-700/40"></div>
                        <div class="h-2 w-2/3 rounded bg-slate-700/40"></div>
                    </div>

                    <div class="pt-2 flex gap-2">
                        <span class="text-[8px] font-bold px-2.5 py-1 rounded text-white" style="background-color: {{ $primary }}">CTA Principal</span>
                        <span class="text-[8px] font-bold px-2.5 py-1 rounded text-slate-400 border border-slate-700">En savoir plus</span>
                    </div>
                </div>

                <!-- Hover Overlay Trigger for Quick Preview -->
                <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <button wire:click="openPreviewModal({{ $theme->id }})" class="bg-white text-slate-950 font-extrabold text-xs px-4 py-2 rounded-xl shadow-lg hover:bg-slate-100 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Live Preview</span>
                    </button>
                </div>
            </div>

            <!-- Theme Meta Information Specs -->
            <div class="p-5 space-y-4">
                <div>
                    <h3 class="font-extrabold text-base text-slate-900 tracking-tight">{{ $theme->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $theme->description }}</p>
                </div>

                <!-- Specs Pill Grid -->
                <div class="grid grid-cols-2 gap-2 text-[10px] text-slate-600 font-bold bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-1.5">
                        <span class="text-indigo-600">📦</span> 12+ Composants
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-indigo-600">📄</span> 7 Pages Prêtes
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-indigo-600">🎨</span> Lucide Icons
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-indigo-600">🔤</span> Plus Jakarta Sans
                    </div>
                </div>

                <!-- Color Palette Swatches -->
                <div class="flex items-center justify-between pt-1">
                    <span class="text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">Palette</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded-full border border-slate-200 shadow-xs" style="background-color: {{ $primary }}" title="Primary"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200 shadow-xs" style="background-color: {{ $secondary }}" title="Secondary"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200 shadow-xs" style="background-color: {{ $bg }}" title="Background"></span>
                        <span class="w-4 h-4 rounded-full border border-slate-200 shadow-xs" style="background-color: {{ $accent }}" title="Accent"></span>
                    </div>
                </div>
            </div>

            <!-- Marketplace Action Bar -->
            <div class="p-5 pt-0 space-y-2">
                <button wire:click="openAssignModal({{ $theme->id }})" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    <span>Assign To Agency</span>
                </button>

                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="openDetailsModal({{ $theme->id }})" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] py-2 rounded-xl transition text-center">
                        Details Specs
                    </button>
                    <button wire:click="editTheme({{ $theme->id }})" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-[11px] py-2 rounded-xl transition text-center">
                        Customize Tokens
                    </button>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    <!-- 1. ASSIGN TO AGENCY SEARCH MODAL -->
    @if($showAssignModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 space-y-6 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest">Affectation Thème</span>
                    <h2 class="text-lg font-black text-slate-900 mt-0.5">Appliquer "{{ $targetTheme->name }}"</h2>
                </div>
                <button wire:click="$set('showAssignModal', false)" class="text-slate-400 hover:text-slate-700 text-xl font-bold">✕</button>
            </div>

            <div class="space-y-4">
                <!-- Search Agency Input -->
                <div class="relative">
                    <span class="absolute left-3.5 top-3 text-slate-400 text-xs">🔍</span>
                    <input type="text" wire:model.live="searchAgency" placeholder="Rechercher agence par nom ou sous-domaine..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-800 focus:ring-teal-500 font-semibold">
                </div>

                <!-- Agency List Radio Cards -->
                <div class="max-h-60 overflow-y-auto space-y-2 pr-1 text-xs">
                    @forelse($filteredTenants as $tenant)
                    <label class="flex items-center justify-between p-3.5 rounded-2xl border cursor-pointer transition {{ $selectedTenantId === $tenant->id ? 'border-teal-500 bg-teal-50/50 text-slate-900 font-bold' : 'border-slate-200 hover:bg-slate-50 text-slate-700' }}">
                        <div class="flex items-center gap-3">
                            <input type="radio" wire:model="selectedTenantId" value="{{ $tenant->id }}" class="text-teal-600 focus:ring-teal-500">
                            <div>
                                <span class="block font-black text-slate-900">{{ $tenant->name ?? $tenant->id }}</span>
                                <span class="block text-[10px] text-slate-400 font-mono">{{ $tenant->id }}.sc7mosa1422.universe.wf</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-slate-100 rounded-full text-slate-600">Active</span>
                    </label>
                    @empty
                    <div class="text-center py-6 text-slate-400 text-xs">
                        Aucune agence trouvée pour "{{ $searchAgency }}".
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button wire:click="$set('showAssignModal', false)" class="px-5 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-900">Annuler</button>
                <button wire:click="confirmAssignToAgency" class="bg-teal-500 hover:bg-teal-400 text-slate-950 font-black text-xs px-6 py-2.5 rounded-xl shadow-lg transition">
                    🚀 Confirmer & Publier Live
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- 2. LIVE INTERACTIVE DEVICE PREVIEW MODAL -->
    @if($showPreviewModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex flex-col p-4">
        <!-- Preview Device Toolbar -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 mb-4 flex items-center justify-between text-white max-w-5xl mx-auto w-full">
            <div class="flex items-center gap-3">
                <span class="font-extrabold text-sm text-teal-400">{{ $targetTheme->name }}</span>
                <span class="text-xs text-slate-500 font-mono">v{{ $targetTheme->version }}</span>
            </div>

            <!-- Device Selector Buttons -->
            <div class="flex items-center gap-2 bg-slate-950 p-1 rounded-xl border border-slate-800">
                <button wire:click="$set('previewDevice', 'desktop')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $previewDevice === 'desktop' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
                    💻 Desktop (100%)
                </button>
                <button wire:click="$set('previewDevice', 'tablet')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $previewDevice === 'tablet' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
                    📱 Tablet (768px)
                </button>
                <button wire:click="$set('previewDevice', 'mobile')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ $previewDevice === 'mobile' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white' }}">
                    📲 Mobile (375px)
                </button>
            </div>

            <button wire:click="$set('showPreviewModal', false)" class="bg-slate-800 hover:bg-slate-700 text-white px-4 py-1.5 rounded-xl text-xs font-bold">
                Fermer Preview ✕
            </button>
        </div>

        <!-- Viewport Container Frame -->
        <div class="flex-1 flex justify-center items-center overflow-hidden">
            <div class="h-full transition-all duration-300 shadow-2xl rounded-2xl overflow-hidden border border-slate-700"
                 style="width: {{ $previewDevice === 'mobile' ? '375px' : ($previewDevice === 'tablet' ? '768px' : '100%') }}">
                <iframe src="/" class="w-full h-full border-0"></iframe>
            </div>
        </div>
    </div>
    @endif

    <!-- 3. THEME DETAILS & SPECIFICATIONS MODAL -->
    @if($showDetailsModal && $targetTheme)
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-2xl w-full p-6 space-y-6 shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Spécifications Thème</span>
                    <h2 class="text-xl font-black text-slate-900 mt-0.5">{{ $targetTheme->name }}</h2>
                </div>
                <button wire:click="$set('showDetailsModal', false)" class="text-slate-400 hover:text-slate-700 text-xl font-bold">✕</button>
            </div>

            <div class="space-y-4 text-xs">
                <p class="text-slate-600 leading-relaxed">{{ $targetTheme->description }}</p>

                <!-- Spec Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Auteur</span>
                        <span class="font-bold text-slate-900 block">{{ $targetTheme->author ?? 'Insurio Design System' }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Version</span>
                        <span class="font-bold text-slate-900 block">v{{ $targetTheme->version }}</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Bibliothèque d'icônes</span>
                        <span class="font-bold text-slate-900 block">Lucide Outline Icons</span>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase block">Typographie</span>
                        <span class="font-bold text-slate-900 block">Plus Jakarta Sans</span>
                    </div>
                </div>

                <div class="p-4 bg-slate-900 text-white rounded-2xl space-y-2">
                    <span class="text-[10px] font-bold text-teal-400 uppercase block">Score de Performance & SEO</span>
                    <div class="flex justify-between items-center text-xs">
                        <span>Core Web Vitals: <strong class="text-emerald-400">98/100</strong></span>
                        <span>SEO Indexing: <strong class="text-emerald-400">Conforme</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button wire:click="$set('showDetailsModal', false)" class="bg-slate-900 text-white font-bold text-xs px-6 py-2.5 rounded-xl">Fermer</button>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. EDIT TOKENS MODAL -->
    @if($showEditModal)
    <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h2 class="text-lg font-bold text-slate-900">Éditeur de Tokens & Couleurs Thème</h2>

            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Nom du Thème</label>
                    <input type="text" wire:model="name" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold text-slate-600 mb-1">Description</label>
                    <textarea wire:model="description" class="w-full border border-slate-200 rounded-xl px-3 py-2"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Couleur Primaire</label>
                        <input type="color" wire:model="primary_color" class="w-full h-10 border border-slate-200 rounded-xl p-1 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Couleur Secondaire</label>
                        <input type="color" wire:model="secondary_color" class="w-full h-10 border border-slate-200 rounded-xl p-1 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Fond (Background)</label>
                        <input type="color" wire:model="bg_color" class="w-full h-10 border border-slate-200 rounded-xl p-1 cursor-pointer">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Accent</label>
                        <input type="color" wire:model="accent_color" class="w-full h-10 border border-slate-200 rounded-xl p-1 cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button wire:click="$set('showEditModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900">Annuler</button>
                <button wire:click="saveTheme" class="bg-indigo-600 text-white font-bold text-xs px-5 py-2 rounded-xl">Enregistrer</button>
            </div>
        </div>
    </div>
    @endif
</div>
