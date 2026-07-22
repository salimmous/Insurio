<div class="p-6 space-y-6 font-sans">
    @if (session()->has('message'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-xs font-bold">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Landlord Website Theme Engine</h1>
            <p class="text-xs text-slate-500">Gestionnaire centralisé des thèmes White Label & Verrouillage du Design System.</p>
        </div>

        <button wire:click="$set('showModal', true)" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg transition">
            + Créer un nouveau Thème
        </button>
    </div>

    <!-- Quick Assignment Panel -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4 text-white">
        <h2 class="text-xs font-extrabold uppercase tracking-wider text-teal-400">Affectation Immédiate Thème → Agence</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Agence Cible</label>
                <select wire:model="selectedTenantId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200">
                    <option value="">Sélectionner une agence...</option>
                    @foreach($tenants as $t)
                        <option value="{{ $t->id }}">{{ $t->name ?? $t->id }} ({{ $t->id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Thème à Appliquer</label>
                <select wire:model="selectedThemeId" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200">
                    @foreach($themes as $th)
                        <option value="{{ $th->id }}">{{ $th->name }} (v{{ $th->version }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="assignThemeToTenant" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs py-2.5 rounded-xl transition shadow-md">
                    🚀 Appliquer & Publier Live
                </button>
            </div>
        </div>
    </div>

    <!-- Theme Gallery -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($themes as $theme)
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 font-bold text-[10px] rounded-lg">v{{ $theme->version }}</span>
                    @if($theme->is_locked)
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 border border-rose-200 font-extrabold text-[9px] rounded-full flex items-center gap-1">
                            🔒 Verrouillé
                        </span>
                    @else
                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-200 font-extrabold text-[9px] rounded-full">
                            🔓 Éditable
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="font-extrabold text-base text-slate-900">{{ $theme->name }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $theme->description }}</p>
                </div>

                <!-- Color Palette Preview -->
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-6 h-6 rounded-full shadow-inner border border-slate-200" style="background-color: {{ $theme->colors['primary'] ?? '#000' }}" title="Primary"></span>
                    <span class="w-6 h-6 rounded-full shadow-inner border border-slate-200" style="background-color: {{ $theme->colors['secondary'] ?? '#000' }}" title="Secondary"></span>
                    <span class="w-6 h-6 rounded-full shadow-inner border border-slate-200" style="background-color: {{ $theme->colors['bg'] ?? '#000' }}" title="Background"></span>
                    <span class="w-6 h-6 rounded-full shadow-inner border border-slate-200" style="background-color: {{ $theme->colors['accent'] ?? '#000' }}" title="Accent"></span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                <button wire:click="toggleLock({{ $theme->id }})" class="text-[11px] font-bold text-slate-600 hover:text-slate-900">
                    {{ $theme->is_locked ? 'Déverrouiller' : 'Verrouiller' }}
                </button>
                <button wire:click="editTheme({{ $theme->id }})" class="bg-slate-900 text-white font-bold text-xs px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">
                    Éditer Token
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Edit Modal -->
    @if($showModal)
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
                <button wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900">Annuler</button>
                <button wire:click="saveTheme" class="bg-indigo-600 text-white font-bold text-xs px-5 py-2 rounded-xl">Enregistrer</button>
            </div>
        </div>
    </div>
    @endif
</div>
