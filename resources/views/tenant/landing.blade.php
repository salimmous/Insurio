<?php
    $config = \App\Models\TenantWebsiteConfig::first();
    $theme = null;
    if ($config && !empty($config->theme_id)) {
        $theme = \App\Models\WebsiteTheme::find($config->theme_id);
    }
    if (!$theme) {
        $theme = \App\Models\WebsiteTheme::first();
    }

    $colors = $theme ? ($theme->colors ?? []) : [];
    $configComp = $theme ? ($theme->components_config ?? []) : [];
    $content = $config ? ($config->content ?? []) : [];
    $seo = $config ? ($config->seo ?? []) : [];

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
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['meta_title'] ?? tenant('name') . ' | Assurances & Conseils' }}</title>
    <meta name="description" content="{{ $seo['meta_description'] ?? 'Agence d\'assurance professionnelle' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'assurance, auto, casablanca, maroc' }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: {{ $bgColor }}; color: {{ $textColor }}; }
    </style>
</head>
<body class="min-h-screen selection:bg-teal-500 selection:text-slate-950 overflow-x-hidden">

    <!-- Background Decoration based on Theme Engine -->
    @if($slug === 'future-insurance')
        <!-- Cyber Tech Grid -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none"></div>
    @elseif($slug === 'royal-gold')
        <!-- Gold Luxury Vignette -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none opacity-20 bg-radial from-amber-500/20 via-transparent to-transparent"></div>
    @elseif($slug === 'classic-morocco')
        <!-- Moroccan Warm Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] pointer-events-none opacity-25 bg-gradient-to-b from-orange-600/30 to-transparent"></div>
    @else
        <!-- Standard Soft Ambient Orbs -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none overflow-hidden opacity-30">
            <div class="absolute top-[-10%] left-[-20%] w-[600px] h-[600px] rounded-full blur-[120px]" style="background-color: {{ $primaryColor }}"></div>
            <div class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] rounded-full blur-[100px]" style="background-color: {{ $secondaryColor }}"></div>
        </div>
    @endif

    <!-- Header Navigation -->
    <header class="border-b sticky top-0 z-50 backdrop-blur-md transition-all {{ $isDark ? 'border-slate-800/80 bg-slate-950/80' : 'border-slate-200 bg-white/90 shadow-sm' }}">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 {{ $radius }} flex items-center justify-center font-black text-white shadow-lg" style="background-color: {{ $primaryColor }}">
                    {{ substr(tenant('name'), 0, 1) }}
                </div>
                <div>
                    <span class="font-extrabold text-xl {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                        {{ tenant('name') }}
                    </span>
                    <span class="block text-[10px] font-bold uppercase tracking-widest -mt-1" style="color: {{ $accentColor }}">Assurance & Courtage</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="#services" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">Nos Services</a>
                <a href="#about" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">À Propos</a>
                <a href="#contact" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">Contact</a>
                
                <a href="{{ route('login') }}" class="text-white font-extrabold text-xs px-5 py-2.5 {{ $radius }} transition-all shadow-lg flex items-center gap-2" style="background-color: {{ $primaryColor }}">
                    Espace Agent 🔑
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-20 pb-32 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 {{ $radius }} border text-xs font-extrabold" style="background-color: {{ $primaryColor }}15; border-color: {{ $primaryColor }}40; color: {{ $accentColor }}">
                <span>{{ $content['badge_text'] ?? '🛡️ Assurances professionnelles au Maroc' }}</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-black leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                {{ $content['hero_title'] ?? 'Protégez ce qui compte le plus avec ' . tenant('name') }}
            </h1>
            
            <p class="{{ $isDark ? 'text-slate-400' : 'text-slate-600' }} text-base md:text-lg max-w-xl leading-relaxed">
                {{ $content['hero_subtitle'] ?? 'Une agence moderne, réactive et à votre écoute pour vous proposer les meilleures solutions d\'assurance.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="#contact" class="{{ $radius }} border text-xs uppercase font-extrabold tracking-wider px-8 py-4 text-center transition-all shadow-sm {{ $isDark ? 'bg-slate-900 border-slate-800 text-white hover:bg-slate-800' : 'bg-slate-100 border-slate-200 text-slate-900 hover:bg-slate-200' }}">
                    Demander Un Devis
                </a>
                <a href="{{ route('login') }}" class="text-white font-extrabold text-xs uppercase tracking-wider px-8 py-4 {{ $radius }} text-center transition-all shadow-lg" style="background-color: {{ $primaryColor }}">
                    Accéder Espace Client 🔑
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="p-8 {{ $radius }} relative space-y-6 border shadow-2xl transition-all" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}50">
                <div class="w-12 h-12 {{ $radius }} flex items-center justify-center font-black text-xl shadow-inner" style="background-color: {{ $primaryColor }}30; color: {{ $accentColor }}">
                    ✓
                </div>
                <h3 class="text-xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Simulation & Devis Instantané</h3>
                <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">
                    Nos experts étudient votre dossier sous 2 heures et vous garantissent les meilleures couvertures tarifaires du marché.
                </p>
                <div class="border-t border-slate-800/40 pt-4 flex justify-between items-center text-xs font-bold">
                    <span class="{{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">Contact Direct Agence</span>
                    <span class="font-mono" style="color: {{ $accentColor }}">{{ $content['phone'] ?? '+212 5 22 00 00 00' }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="border-t py-24 {{ $isDark ? 'border-slate-900/80' : 'border-slate-200 bg-slate-50' }}" style="background-color: {{ $bgColor }}">
        <div class="max-w-7xl mx-auto px-6 space-y-16">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <span class="text-xs font-black uppercase tracking-widest" style="color: {{ $accentColor }}">OFFRES ÉLIGIBLES</span>
                <h2 class="text-3xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Nos Solutions d'Assurance</h2>
                <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Des formules complètes pour couvrir tous vos besoins personnels et professionnels.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 {{ $radius }} border transition-all space-y-4 shadow-sm hover:shadow-xl" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 {{ $radius }} flex items-center justify-center text-2xl" style="background-color: {{ $primaryColor }}20">🚗</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Automobile</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">Garanties Tous Risques, Tierce, Défense & Recours, Bris de Glace et Assistance 24h/7j.</p>
                </div>
                <div class="p-8 {{ $radius }} border transition-all space-y-4 shadow-sm hover:shadow-xl" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 {{ $radius }} flex items-center justify-center text-2xl" style="background-color: {{ $primaryColor }}20">🏠</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Multirisque Habitation</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">Protection complète de vos biens immobiliers et responsabilité civile familiale.</p>
                </div>
                <div class="p-8 {{ $radius }} border transition-all space-y-4 shadow-sm hover:shadow-xl" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 {{ $radius }} flex items-center justify-center text-2xl" style="background-color: {{ $primaryColor }}20">🏢</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Risques Entreprises & Flottes</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">Flottes automobiles, Accident du travail, Multirisque industrielle et pertes d'exploitation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="border-t py-12 {{ $isDark ? 'border-slate-900 bg-slate-950 text-slate-500' : 'border-slate-200 bg-white text-slate-600' }}">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-xs">
            <div>
                <span class="font-extrabold text-sm block {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ tenant('name') }}</span>
                <span class="mt-1 block">{{ $content['address'] ?? 'Maroc' }} • {{ $content['email'] ?? 'contact@agence.ma' }}</span>
            </div>
            <div class="text-right">
                <span class="block">© {{ date('Y') }} {{ tenant('name') }}. Tous droits réservés.</span>
                <span class="text-[10px] text-teal-500 font-bold block mt-0.5">Propulsé par Insurio White Label Theme Engine (v1.0)</span>
            </div>
        </div>
    </footer>

</body>
</html>
