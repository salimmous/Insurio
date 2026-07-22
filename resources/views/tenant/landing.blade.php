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
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Assurances & Conseils</title>
    <meta name="description" content="Agence d'assurance de référence au Maroc">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: {{ $bgColor }}; color: {{ $textColor }}; }
    </style>
</head>
<body class="min-h-screen selection:bg-teal-500 selection:text-slate-950 overflow-x-hidden">

    <!-- Theme Specific Ambient Overlay Backgrounds -->
    @if($slug === 'future-insurance')
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff08_1px,transparent_1px),linear-gradient(to_bottom,#ffffff08_1px,transparent_1px)] bg-[size:3rem_3rem] pointer-events-none"></div>
    @elseif($slug === 'royal-gold')
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none opacity-30 bg-radial from-amber-500/20 via-transparent to-transparent"></div>
    @elseif($slug === 'classic-morocco')
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] pointer-events-none opacity-25 bg-gradient-to-b from-orange-600/30 to-transparent"></div>
    @elseif($slug === 'glass-enterprise')
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none opacity-40 bg-gradient-to-tr from-indigo-600/30 via-purple-600/20 to-transparent blur-3xl"></div>
    @else
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none overflow-hidden opacity-25">
            <div class="absolute top-[-10%] left-[-20%] w-[600px] h-[600px] rounded-full blur-[120px]" style="background-color: {{ $primaryColor }}"></div>
            <div class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] rounded-full blur-[100px]" style="background-color: {{ $secondaryColor }}"></div>
        </div>
    @endif

    <!-- Header Navigation -->
    <header class="border-b sticky top-0 z-50 backdrop-blur-md transition-all {{ $isDark ? 'border-slate-800/80 bg-slate-950/80' : 'border-slate-200 bg-white/90 shadow-sm' }}">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 {{ $radius }} flex items-center justify-center font-black text-white shadow-lg" style="background-color: {{ $primaryColor }}">
                    {{ substr($agencyName, 0, 1) }}
                </div>
                <div>
                    <span class="font-extrabold text-lg {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                        {{ $agencyName }}
                    </span>
                    <span class="block text-[9px] font-bold uppercase tracking-widest -mt-1" style="color: {{ $accentColor }}">Insurio Platform • {{ $theme->name }}</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <a href="#services" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">Nos Services</a>
                <a href="#contact" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">Contact</a>
                
                <a href="{{ route('login') }}" class="text-white font-extrabold text-xs px-5 py-2.5 {{ $radius }} transition-all shadow-lg flex items-center gap-2" style="background-color: {{ $primaryColor }}">
                    Espace Agent 🔑
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 {{ $radius }} border text-xs font-extrabold" style="background-color: {{ $primaryColor }}15; border-color: {{ $primaryColor }}40; color: {{ $accentColor }}">
                <span>🛡️ Assurances & Courtage Habilité au Maroc</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                Protégez vos proches & votre patrimoine avec <span style="color: {{ $accentColor }}">{{ $agencyName }}</span>
            </h1>
            
            <p class="{{ $isDark ? 'text-slate-400' : 'text-slate-600' }} text-sm md:text-base max-w-xl leading-relaxed">
                Une agence partenaire agréée, réactive et à votre service. Solutions sur-mesure pour automobile, habitation, santé et entreprises.
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="#contact" class="{{ $radius }} border text-xs uppercase font-extrabold tracking-wider px-8 py-3.5 text-center transition-all shadow-sm {{ $isDark ? 'bg-slate-900 border-slate-800 text-white hover:bg-slate-800' : 'bg-slate-100 border-slate-200 text-slate-900 hover:bg-slate-200' }}">
                    Demander Un Devis
                </a>
                <a href="{{ route('login') }}" class="text-white font-extrabold text-xs uppercase tracking-wider px-8 py-3.5 {{ $radius }} text-center transition-all shadow-lg" style="background-color: {{ $primaryColor }}">
                    Espace Client 🔑
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="p-8 {{ $radius }} relative space-y-6 border shadow-2xl transition-all" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}50">
                <div class="w-12 h-12 {{ $radius }} flex items-center justify-center font-black text-xl shadow-inner" style="background-color: {{ $primaryColor }}30; color: {{ $accentColor }}">
                    ✓
                </div>
                <h3 class="text-xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Simulation Immediate 2h</h3>
                <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">
                    Obtenez une tarification sur-mesure validée par nos courtiers sous 2 heures avec garantie du meilleur tarif.
                </p>
                <div class="border-t border-slate-800/40 pt-4 flex justify-between items-center text-xs font-bold">
                    <span class="{{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">Ligne Directe Agence</span>
                    <span class="font-mono" style="color: {{ $accentColor }}">+212 5 22 00 00 00</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="border-t py-16 {{ $isDark ? 'border-slate-900/80' : 'border-slate-200 bg-slate-50' }}" style="background-color: {{ $bgColor }}">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-widest" style="color: {{ $accentColor }}">COMPOSANTS THEME READY</span>
                <h2 class="text-2xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Nos Offres d'Assurances</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-2xl">🚗</div>
                    <h3 class="text-base font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Automobile</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Tierce collision, tous risques, assistance panne 24/7 au Maroc.</p>
                </div>
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-2xl">🏠</div>
                    <h3 class="text-base font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Multirisque Habitation</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Protection incendie, dégât des eaux et responsabilité civile.</p>
                </div>
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-2xl">🏢</div>
                    <h3 class="text-base font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Entreprise & Flottes</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Couverture des risques professionnels, flottes auto et accident du travail.</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
