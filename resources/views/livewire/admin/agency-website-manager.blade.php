<div class="p-6 space-y-6 font-sans">
    @if (session()->has('message'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 p-4 rounded-xl text-xs font-bold">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-black text-slate-900">Gestionnaire du Site Web Agence</h1>
                <span class="px-2.5 py-1 bg-slate-900 text-teal-400 font-extrabold text-[10px] rounded-full uppercase border border-slate-800">
                    Thème Actif: {{ $activeTheme->name ?? 'Corporate' }} (🔒 Verrouillé par Super Admin)
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Personnalisez votre contenu, vos coordonnées et votre SEO sans altérer la charte graphique.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="/" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs px-4 py-2.5 rounded-xl transition">
                🌐 Voir le Site Live
            </a>
            <button wire:click="saveContent" class="bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-lg transition">
                💾 Sauvegarder Modif.
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 gap-6 text-xs font-bold">
        <button wire:click="$set('activeTab', 'content')" class="pb-3 border-b-2 {{ $activeTab === 'content' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
            📝 Textes & Accroches
        </button>
        <button wire:click="$set('activeTab', 'contact')" class="pb-3 border-b-2 {{ $activeTab === 'contact' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
            📞 Coordonnées & Horaires
        </button>
        <button wire:click="$set('activeTab', 'seo')" class="pb-3 border-b-2 {{ $activeTab === 'seo' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
            🔍 SEO & Réseaux Sociaux
        </button>
        <button wire:click="$set('activeTab', 'domain')" class="pb-3 border-b-2 {{ $activeTab === 'domain' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-400 hover:text-slate-700' }}">
            🌐 Domaine Personnalisé
        </button>
    </div>

    <!-- Tab Contents -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Side -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-4">
            @if($activeTab === 'content')
                <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">Page d'Accueil (Hero Section)</h2>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Badge d'En-tête</label>
                        <input type="text" wire:model="badge_text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Titre Principal (Hero Title)</label>
                        <input type="text" wire:model="hero_title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-bold">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Sous-Titre Explicatif</label>
                        <textarea wire:model="hero_subtitle" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800"></textarea>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">À Propos de l'Agence</h2>
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Titre Section À Propos</label>
                            <input type="text" wire:model="about_title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Texte Présentation</label>
                            <textarea wire:model="about_text" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800"></textarea>
                        </div>
                    </div>
                </div>

            @elseif($activeTab === 'contact')
                <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">Coordonnées de l'Agence</h2>
                
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Téléphone Agence</label>
                        <input type="text" wire:model="phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Numéro WhatsApp Direct</label>
                        <input type="text" wire:model="whatsapp" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Email Officiel</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Horaires d'Ouverture</label>
                        <input type="text" wire:model="opening_hours" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>

                    <div class="col-span-2">
                        <label class="block font-bold text-slate-600 mb-1">Adresse Physique</label>
                        <input type="text" wire:model="address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>
                </div>

            @elseif($activeTab === 'seo')
                <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">Référencement Naturel (SEO) & Pixels</h2>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Meta Title (Balise Titre)</label>
                        <input type="text" wire:model="meta_title" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Meta Description</label>
                        <textarea wire:model="meta_description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Mots Clés (Keywords)</label>
                        <input type="text" wire:model="keywords" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Lien Facebook</label>
                            <input type="text" wire:model="facebook" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-600 mb-1">Lien Instagram</label>
                            <input type="text" wire:model="instagram" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                        </div>
                    </div>
                </div>

            @elseif($activeTab === 'domain')
                <h2 class="text-xs font-black uppercase text-slate-800 tracking-wider">Nom de Domaine Personnalisé</h2>
                
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Domaine Personnalisé (ex: agence-assurance.ma)</label>
                        <input type="text" wire:model="custom_domain" placeholder="ex: assurance-casablanca.ma" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 font-mono">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-slate-600 space-y-2">
                        <span class="font-bold text-slate-900 block">Instructions CNAME / DNS:</span>
                        <p class="text-[11px]">Pointez l'enregistrement A de votre registrar (Maroc Telecom / NTC / Gandi) vers IP: <code class="font-mono text-teal-600 font-bold">185.220.100.15</code></p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Live Preview Phone Frame -->
        <div class="bg-slate-950 rounded-2xl p-6 border border-slate-800 space-y-4 text-white shadow-2xl flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <span class="text-[10px] font-extrabold uppercase text-teal-400 tracking-widest">Aperçu Dynamique (Live)</span>
                    <span class="text-[9px] text-slate-500 font-mono">Theme: {{ $activeTheme->slug ?? 'default' }}</span>
                </div>

                <!-- Simulated Phone Card -->
                <div class="rounded-2xl p-4 space-y-3 border" style="background-color: {{ $activeTheme->colors['bg'] ?? '#0F172A' }}; border-color: {{ $activeTheme->colors['primary'] ?? '#1E40AF' }}">
                    <span class="px-2 py-0.5 text-[8px] font-bold rounded-full text-slate-950" style="background-color: {{ $activeTheme->colors['accent'] ?? '#38BDF8' }}">
                        {{ $badge_text ?: 'Assurance' }}
                    </span>

                    <h3 class="text-sm font-black text-white leading-tight">
                        {{ $hero_title ?: 'Titre d\'accroche' }}
                    </h3>

                    <p class="text-[10px] text-slate-400 line-clamp-2">
                        {{ $hero_subtitle ?: 'Description sous-titre' }}
                    </p>

                    <div class="pt-2 flex gap-2">
                        <span class="text-[9px] font-bold px-3 py-1 rounded-lg text-white" style="background-color: {{ $activeTheme->colors['primary'] ?? '#1E40AF' }}">
                            Devis Gratuit
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center text-[10px] text-slate-500 pt-4 border-t border-slate-800">
                Garantie 100% conformité charte graphique Super Admin.
            </div>
        </div>
    </div>
</div>
