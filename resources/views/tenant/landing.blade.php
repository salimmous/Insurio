<?php
    $config = \App\Models\TenantWebsiteConfig::first();
    $theme = $config && $config->theme ? $config->theme : \App\Models\WebsiteTheme::first();
    $colors = $theme ? ($theme->colors ?? []) : [];
    $content = $config ? ($config->content ?? []) : [];
    $seo = $config ? ($config->seo ?? []) : [];

    $primaryColor = $colors['primary'] ?? '#1E40AF';
    $secondaryColor = $colors['secondary'] ?? '#3B82F6';
    $bgColor = $colors['bg'] ?? '#0F172A';
    $cardBgColor = $colors['card_bg'] ?? '#1E293B';
    $accentColor = $colors['accent'] ?? '#38BDF8';
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: {{ $bgColor }}; }
    </style>
</head>
<body class="text-slate-100 min-h-screen selection:bg-teal-500 selection:text-slate-950 overflow-x-hidden">
    <!-- Dynamic Background Gradients based on Super Admin Theme Tokens -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none overflow-hidden opacity-30">
        <div class="absolute top-[-10%] left-[-20%] w-[600px] h-[600px] rounded-full blur-[120px]" style="background-color: {{ $primaryColor }}"></div>
        <div class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] rounded-full blur-[100px]" style="background-color: {{ $secondaryColor }}"></div>
    </div>

    <!-- Header Navigation -->
    <header class="border-b border-slate-900/80 backdrop-blur-md sticky top-0 z-50 bg-slate-950/80">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow-lg" style="background-color: {{ $primaryColor }}">
                    {{ substr(tenant('name'), 0, 1) }}
                </div>
                <div>
                    <span class="font-extrabold text-xl bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                        {{ tenant('name') }}
                    </span>
                    <span class="block text-[10px] font-bold uppercase tracking-widest -mt-1" style="color: {{ $accentColor }}">Partenaire Assurance</span>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="#services" class="text-sm font-medium text-slate-400 hover:text-white transition-colors hidden md:block">Nos Services</a>
                <a href="#contact" class="text-sm font-medium text-slate-400 hover:text-white transition-colors hidden md:block">Contact</a>
                <a href="{{ route('login') }}" class="text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition-all shadow-lg flex items-center gap-2" style="background-color: {{ $primaryColor }}">
                    Espace Agent 🔑
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-20 pb-32 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-xs font-semibold" style="background-color: {{ $primaryColor }}15; border-color: {{ $primaryColor }}40; color: {{ $accentColor }}">
                <span>{{ $content['badge_text'] ?? '🛡️ Assurances professionnelles au Maroc' }}</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight">
                {{ $content['hero_title'] ?? 'Protégez ce qui compte le plus avec ' . tenant('name') }}
            </h1>
            
            <p class="text-slate-400 text-lg max-w-xl">
                {{ $content['hero_subtitle'] ?? 'Une agence moderne, réactive et à votre écoute pour vous proposer les meilleures solutions d\'assurance.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="#contact" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-white font-semibold text-sm px-8 py-4 rounded-2xl text-center transition-all">
                    Demander Devis
                </a>
                <a href="{{ route('login') }}" class="text-white font-semibold text-sm px-8 py-4 rounded-2xl text-center transition-all shadow-lg" style="background-color: {{ $primaryColor }}">
                    Accéder à mon Espace 🔑
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="p-8 rounded-3xl relative space-y-6 border shadow-2xl" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}40">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-xl" style="background-color: {{ $primaryColor }}20; color: {{ $accentColor }}">
                    ✓
                </div>
                <h3 class="text-xl font-bold text-white">Prise en Charge Rapide</h3>
                <p class="text-slate-400 text-sm">
                    Nos conseillers étudient votre dossier et vous proposent le meilleur tarif garanti.
                </p>
                <div class="border-t border-slate-800 pt-4 flex justify-between items-center text-xs text-slate-400">
                    <span>Contact Direct</span>
                    <span class="font-mono font-bold" style="color: {{ $accentColor }}">{{ $content['phone'] ?? '+212 5 22 00 00 00' }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="border-t border-slate-900/60 py-24" style="background-color: {{ $bgColor }}">
        <div class="max-w-7xl mx-auto px-6 space-y-16">
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <h2 class="text-3xl font-extrabold text-white">Nos Solutions d'Assurance</h2>
                <p class="text-slate-400 text-sm">Des formules complètes pour couvrir tous vos besoins.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl border transition-all space-y-4" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold" style="background-color: {{ $primaryColor }}20; color: {{ $accentColor }}">🚗</div>
                    <h3 class="text-xl font-bold text-white">Assurance Automobile</h3>
                    <p class="text-slate-400 text-sm">Garanties Tous Risques, Tierce, DR, Bris de Glace & Assistance 24/7.</p>
                </div>
                <div class="p-8 rounded-3xl border transition-all space-y-4" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold" style="background-color: {{ $primaryColor }}20; color: {{ $accentColor }}">🏠</div>
                    <h3 class="text-xl font-bold text-white">Multirisque Habitation</h3>
                    <p class="text-slate-400 text-sm">Protection complète de vos biens immobiliers et responsabilité civile familiale.</p>
                </div>
                <div class="p-8 rounded-3xl border transition-all space-y-4" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-bold" style="background-color: {{ $primaryColor }}20; color: {{ $accentColor }}">🏢</div>
                    <h3 class="text-xl font-bold text-white">Risques Entreprises</h3>
                    <p class="text-slate-400 text-sm">Flotte auto, accident du travail, multirisque industrielle et perte d'exploitation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="border-t border-slate-900 bg-slate-950 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-xs text-slate-500">
            <div>
                <span class="font-bold text-white text-sm block">{{ tenant('name') }}</span>
                <span>{{ $content['address'] ?? 'Maroc' }} • {{ $content['email'] ?? 'contact@agence.ma' }}</span>
            </div>
            <div>
                <span>© {{ date('Y') }} {{ tenant('name') }}. Propulsé par Insurio White Label Platform.</span>
            </div>
        </div>
    </footer>
</body>
</html>
