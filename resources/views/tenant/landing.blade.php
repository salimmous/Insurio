<?php
    if (isset($previewTheme) && $previewTheme) {
        $theme = $previewTheme;
        $agencyName = 'Agence Exemple - ' . $theme->name;
    } else {
        $config = \App\Models\TenantWebsiteConfig::first();
        $theme = null;
        if ($config && !empty($config->theme_id)) {
            $theme = \App\Models\WebsiteTheme::find($config->theme_id);
        }
        if (!$theme) {
            $theme = \App\Models\WebsiteTheme::first();
        }
        $agencyName = tenant('name') ?? 'Insurio Agency';
    }

    $colors = $theme ? ($theme->colors ?? []) : [];
    $configComp = $theme ? ($theme->components_config ?? []) : [];

    $primaryColor = $colors['primary'] ?? '#1E40AF';
    $secondaryColor = $colors['secondary'] ?? '#3B82F6';
    $bgColor = $colors['bg'] ?? '#0F172A';
    $cardBgColor = $colors['card_bg'] ?? '#1E293B';
    $accentColor = $colors['accent'] ?? '#38BDF8';
    $textColor = $colors['text'] ?? '#F8FAFC';
    $isDark = $configComp['dark'] ?? true;
    $radius = $configComp['radius'] ?? 'rounded-2xl';
    $slug = $theme->slug ?? 'corporate-blue';
?>
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false, claimModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Assurances & Conseils au Maroc</title>
    <meta name="description" content="Plateforme d'assurance agréée et conseil en courtage au Maroc. Automobile, Habitation, Santé, Entreprise.">
    <meta name="keywords" content="assurance maroc, assurance auto casablanca, de visu, wafa, axa, rma, sanlam">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Noto+Kufi+Arabic:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Noto Kufi Arabic', sans-serif; background-color: {{ $bgColor }}; color: {{ $textColor }}; }
    </style>
</head>
<body class="min-h-screen selection:bg-teal-500 selection:text-slate-950 overflow-x-hidden">

    <!-- STRUCTURAL THEME 01: CORPORATE BLUE (RMA STYLE) -->
    @if($slug === 'corporate-blue')
        <div class="bg-slate-950 text-slate-400 text-xs py-2 px-6 border-b border-slate-800 flex justify-between items-center">
            <span>📞 Assistance 24/7 Maroc: +212 5 22 00 00 00</span>
            <div class="flex items-center gap-4">
                <button @click="lang = 'fr'" class="hover:text-white">FR 🇫🇷</button>
                <button @click="lang = 'ar'" class="hover:text-white">العربية 🇲🇦</button>
            </div>
        </div>
        <header class="bg-[#0F172A] border-b border-slate-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-700 flex items-center justify-center font-black text-white shadow-lg">R</div>
                    <span class="font-black text-xl text-white">{{ $agencyName }}</span>
                </div>
                <div class="flex items-center gap-6 text-xs font-bold">
                    <a href="#services" class="text-slate-300 hover:text-white">Nos Offres</a>
                    <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl font-black">Demander Devis</button>
                </div>
            </div>
        </header>

    <!-- STRUCTURAL THEME 02: EXECUTIVE DARK (AIG STYLE) -->
    @elseif($slug === 'executive-dark')
        <header class="p-4 fixed top-0 left-0 right-0 z-50">
            <div class="max-w-6xl mx-auto bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-full px-6 h-16 flex items-center justify-between">
                <span class="font-black text-base text-white tracking-widest uppercase">Executive • {{ $agencyName }}</span>
                <div class="flex items-center gap-4 text-xs font-bold">
                    <button @click="lang = (lang === 'fr' ? 'ar' : 'fr')" class="text-teal-400 font-mono">🌐 FR/AR</button>
                    <button @click="quoteModal = true" class="bg-teal-500 text-slate-950 px-4 py-2 rounded-full font-black">Simuler</button>
                </div>
            </div>
        </header>

    <!-- STRUCTURAL THEME 03: MINIMAL WHITE (APPLE STYLE) -->
    @elseif($slug === 'minimal-white')
        <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between text-slate-900">
                <span class="font-black text-lg tracking-tight">{{ $agencyName }}</span>
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <button @click="lang = 'fr'" :class="lang === 'fr' ? 'font-bold underline' : ''">FR</button>
                    <button @click="lang = 'ar'" :class="lang === 'ar' ? 'font-bold underline' : ''">العربية</button>
                    <button @click="quoteModal = true" class="bg-slate-900 text-white px-4 py-2 rounded-full font-bold">Obtenir Devis</button>
                </div>
            </div>
        </header>

    <!-- STRUCTURAL THEME 04: ROYAL GOLD (VIP BANKING STYLE) -->
    @elseif($slug === 'royal-gold')
        <header class="bg-zinc-950 border-b border-amber-500/30 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 to-yellow-500 flex items-center justify-center font-black text-zinc-950 shadow-lg">VIP</div>
                    <span class="font-black text-xl text-amber-400 tracking-wider">{{ $agencyName }}</span>
                </div>
                <button @click="quoteModal = true" class="bg-gradient-to-r from-amber-500 to-yellow-500 text-zinc-950 font-black text-xs px-6 py-3 rounded-xl shadow-lg">Espace VIP Devis</button>
            </div>
        </header>

    <!-- STRUCTURAL THEME DEFAULT & OTHER THEMES -->
    @else
        <header class="border-b sticky top-0 z-50 backdrop-blur-md transition-all {{ $isDark ? 'border-slate-800/80 bg-slate-950/80' : 'border-slate-200 bg-white/90 shadow-sm' }}">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $radius }} flex items-center justify-center font-black text-white shadow-lg" style="background-color: {{ $primaryColor }}">
                        {{ substr($agencyName, 0, 1) }}
                    </div>
                    <div>
                        <span class="font-extrabold text-lg {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ $agencyName }}</span>
                        <span class="block text-[9px] font-bold uppercase tracking-widest -mt-1" style="color: {{ $accentColor }}">Insurio Platform • {{ $theme->name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1 bg-slate-900/60 p-1 rounded-xl border border-slate-800 text-xs">
                        <button @click="lang = 'fr'" :class="lang === 'fr' ? 'bg-indigo-600 text-white' : 'text-slate-400'" class="px-2 py-1 rounded-lg font-bold">🇫🇷 FR</button>
                        <button @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-indigo-600 text-white' : 'text-slate-400'" class="px-2 py-1 rounded-lg font-bold">🇲🇦 العربية</button>
                    </div>
                    <button @click="quoteModal = true" class="text-white font-extrabold text-xs px-5 py-2.5 {{ $radius }} shadow-lg" style="background-color: {{ $primaryColor }}">Devis Instantané 📝</button>
                </div>
            </div>
        </header>
    @endif

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 {{ $radius }} border text-xs font-extrabold" style="background-color: {{ $primaryColor }}15; border-color: {{ $primaryColor }}40; color: {{ $accentColor }}">
                <span x-show="lang === 'fr'">🛡️ Agence d'Assurances Agréée au Maroc</span>
                <span x-show="lang === 'ar'" style="display:none;">🛡️ وكالة تأمين واستشارات معتمدة بالمغرب</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                <span x-show="lang === 'fr'">Protégez vos proches & votre avenir avec <span style="color: {{ $accentColor }}">{{ $agencyName }}</span></span>
                <span x-show="lang === 'ar'" style="display:none;">احمِ عائلتك ومستقبلك مع <span style="color: {{ $accentColor }}">{{ $agencyName }}</span></span>
            </h1>
            
            <p class="{{ $isDark ? 'text-slate-400' : 'text-slate-600' }} text-sm md:text-base max-w-xl leading-relaxed">
                Solutions sur-mesure pour automobile, habitation, santé, prévoyance et risques entreprises.
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <button @click="quoteModal = true" class="{{ $radius }} text-white font-extrabold text-xs uppercase tracking-wider px-8 py-3.5 text-center transition-all shadow-lg" style="background-color: {{ $primaryColor }}">
                    <span x-show="lang === 'fr'">Obtenir Devis Gratuit</span>
                    <span x-show="lang === 'ar'" style="display:none;">احصل على تسعيرة مجانية</span>
                </button>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="p-8 {{ $radius }} relative space-y-6 border shadow-2xl transition-all" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}50">
                <h3 class="text-xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Simulation En Ligne Instantanée</h3>
                <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">Étude de votre dossier sous 2h par nos experts courtiers.</p>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="border-t py-20 {{ $isDark ? 'border-slate-900/80' : 'border-slate-200 bg-slate-50' }}" style="background-color: {{ $bgColor }}">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <h2 class="text-3xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Nos Offres d'Assurances</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🚗</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Automobile</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Tous Risques, Tierce collision et assistance 0 km.</p>
                </div>
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🏠</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Multirisque Habitation</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Protection dégât des eaux, vol et incendie.</p>
                </div>
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🏥</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Santé & Prévoyance</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Remboursement des soins médicaux et d'hospitalisation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="border-t py-12 {{ $isDark ? 'border-slate-900 bg-slate-950 text-slate-500' : 'border-slate-200 bg-white text-slate-600' }}">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-xs">
            <div>
                <span class="font-extrabold text-sm block {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ $agencyName }}</span>
                <span class="mt-1 block">Casablanca, Maroc • contact@agence-assurance.ma</span>
            </div>
            <div class="text-right">
                <span class="block">© {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.</span>
                <span class="text-[10px] text-teal-500 font-bold block mt-0.5">Propulsé par Insurio Real Theme Engine (v2.5)</span>
            </div>
        </div>
    </footer>

    <!-- Interactive Quote Request Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-black text-base">Demande de Devis Instantané</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande a été transmise à l\'agence !'); quoteModal = false" class="space-y-3 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom Complet</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone GSM</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                </div>
                <button type="submit" class="w-full bg-teal-500 text-slate-950 font-black py-3 rounded-xl transition shadow-md">
                    Envoyer ma demande 🚀
                </button>
            </form>
        </div>
    </div>

</body>
</html>
