<div class="p-8 max-w-[1600px] mx-auto space-y-6 font-sans">
    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-bold flex items-center justify-between shadow-xs">
            <span>{{ session('message') }}</span>
            <button class="text-emerald-500 hover:text-emerald-900 font-bold" @click="$el.parentElement.remove()">✕</button>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">CMS Editeur du Site Web de l'Agence</h1>
            <p class="text-xs text-slate-500 mt-1">Personnalisez l'identité, les textes, contacts et le référencement SEO de votre agence en temps réel sans coder.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="saveContent" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-md transition">
                💾 Sauvegarder & Publier
            </button>
        </div>
    </div>

    <!-- Main 2-Column Split: Config Controls (Left) vs Live Interactive Preview (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Panel: Settings Tabs & Inputs -->
        <div class="lg:col-span-5 space-y-6 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            
            <!-- Navigation Tabs -->
            <div class="flex border-b border-slate-200 text-xs font-bold gap-4 overflow-x-auto pb-2">
                <button wire:click="$set('activeTab', 'general')" class="pb-2 transition {{ $activeTab === 'general' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-slate-500 hover:text-slate-900' }}">
                    Marque & Nom
                </button>
                <button wire:click="$set('activeTab', 'hero')" class="pb-2 transition {{ $activeTab === 'hero' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-slate-500 hover:text-slate-900' }}">
                    Hero & Textes
                </button>
                <button wire:click="$set('activeTab', 'contact')" class="pb-2 transition {{ $activeTab === 'contact' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-slate-500 hover:text-slate-900' }}">
                    Contact & Carte
                </button>
                <button wire:click="$set('activeTab', 'social')" class="pb-2 transition {{ $activeTab === 'social' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-slate-500 hover:text-slate-900' }}">
                    Réseaux
                </button>
                <button wire:click="$set('activeTab', 'seo')" class="pb-2 transition {{ $activeTab === 'seo' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-slate-500 hover:text-slate-900' }}">
                    SEO & Analytics
                </button>
            </div>

            <!-- Tab 1: General Brand -->
            @if($activeTab === 'general')
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom de l'Agence</label>
                    <input type="text" wire:model.live="agency_name" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL du Logo (PNG / SVG)</label>
                    <input type="text" wire:model.live="logo_url" placeholder="https://..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">URL Favicon</label>
                    <input type="text" wire:model.live="favicon_url" placeholder="https://..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
            </div>

            <!-- Tab 2: Hero & Textes -->
            @elseif($activeTab === 'hero')
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Titre Hero (Français 🇫🇷)</label>
                    <input type="text" wire:model.live="hero_title" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sous-titre Hero (Français 🇫🇷)</label>
                    <textarea wire:model.live="hero_subtitle" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold"></textarea>
                </div>
                <div class="pt-2 border-t border-slate-100">
                    <label class="block font-bold text-slate-700 mb-1">Titre Hero (Arabe 🇲🇦)</label>
                    <input type="text" wire:model.live="hero_title_ar" dir="rtl" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Sous-titre Hero (Arabe 🇲🇦)</label>
                    <textarea wire:model.live="hero_subtitle_ar" dir="rtl" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bouton Principal</label>
                        <input type="text" wire:model.live="cta_primary_text" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bouton Secondaire</label>
                        <input type="text" wire:model.live="cta_secondary_text" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                    </div>
                </div>
            </div>

            <!-- Tab 3: Contact & Location -->
            @elseif($activeTab === 'contact')
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone Fixe</label>
                    <input type="text" wire:model.live="phone" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">WhatsApp GSM Direct</label>
                    <input type="text" wire:model.live="whatsapp" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Email Officiel</label>
                    <input type="email" wire:model.live="email" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Adresse Physique</label>
                    <textarea wire:model.live="address" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Heures d'Ouverture</label>
                    <input type="text" wire:model.live="opening_hours" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
            </div>

            <!-- Tab 4: Social Media -->
            @elseif($activeTab === 'social')
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Page Facebook</label>
                    <input type="text" wire:model.live="facebook" placeholder="https://facebook.com/..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Compte Instagram</label>
                    <input type="text" wire:model.live="instagram" placeholder="https://instagram.com/..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Profil LinkedIn</label>
                    <input type="text" wire:model.live="linkedin" placeholder="https://linkedin.com/..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Compte TikTok</label>
                    <input type="text" wire:model.live="tiktok" placeholder="https://tiktok.com/..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
            </div>

            <!-- Tab 5: SEO & Analytics -->
            @elseif($activeTab === 'seo')
            <div class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Meta Méta Titre (Google)</label>
                    <input type="text" wire:model.live="meta_title" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Méta Description</label>
                    <textarea wire:model.live="meta_description" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold"></textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">ID Google Analytics (G-XXXXXXX)</label>
                    <input type="text" wire:model.live="google_analytics" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold">
                </div>
                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-700">Activer Bandeau Cookies</span>
                    <input type="checkbox" wire:model.live="cookie_banner_enabled" class="w-4 h-4 rounded text-indigo-600">
                </div>
            </div>
            @endif

        </div>

        <!-- Right Panel: Real-Time Live Website Preview Frame -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-4 shadow-xs flex flex-col h-[750px]">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-slate-500 ml-2">Prévisualisation En Direct (Live Preview)</span>
                </div>
                <span class="text-[10px] font-mono font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full">
                    Thème Actif: {{ $activeTheme->name ?? 'Corporate Blue' }}
                </span>
            </div>

            <div class="flex-1 w-full h-full rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                <iframe src="/" class="w-full h-full border-0"></iframe>
            </div>
        </div>

    </div>
</div>
