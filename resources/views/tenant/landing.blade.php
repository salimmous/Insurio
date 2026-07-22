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
    $bgColor = $colors['bg'] ?? '#FFFFFF';
    $accentColor = $colors['accent'] ?? '#2563EB';
    $textColor = '#0F172A';
    $radius = $configComp['radius'] ?? 'rounded-2xl';
    $slug = $theme->slug ?? 'corporate-insurance';

    // Mapping slugs to modular theme templates in tenant/themes/
    $themeMap = [
        'corporate-insurance' => 'tenant.themes.corporate_insurance',
        'axa-inspire' => 'tenant.themes.corporate_insurance',
        'apple-insurance' => 'tenant.themes.apple_insurance',
        'luxury-gold' => 'tenant.themes.luxury_gold',
        'luxury-private' => 'tenant.themes.luxury_gold',
        'modern-saas' => 'tenant.themes.modern_saas',
        'startup-insurance' => 'tenant.themes.modern_saas',
        'healthcare-insurance' => 'tenant.themes.healthcare_insurance',
        'healthcare' => 'tenant.themes.healthcare_insurance',
    ];

    $viewName = $themeMap[$slug] ?? null;
?>
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false, claimModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Assurances & Conseils au Maroc</title>
    <meta name="description" content="Plateforme d'assurance agréée et conseil en courtage au Maroc. Solutions Auto, Habitation, Santé, Entreprises.">
    <meta name="keywords" content="assurance maroc, assurance auto casablanca, de visu, wafa, axa, rma, sanlam">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800;900&family=Noto+Kufi+Arabic:wght@400;600;700;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', 'Noto Kufi Arabic', sans-serif; background-color: {{ $bgColor }}; color: {{ $textColor }}; }
    </style>
</head>
<body class="min-h-screen selection:bg-indigo-500 selection:text-white overflow-x-hidden bg-white text-slate-900">

    @if($viewName && view()->exists($viewName))
        @include($viewName)
    @else
        <!-- Default Handcrafted Light-First Insurance Engine Layout -->
        <div class="bg-slate-900 text-slate-300 text-xs py-2 px-6 border-b border-slate-800 flex justify-between items-center font-medium">
            <div class="flex items-center gap-6">
                <span>📞 Assistance 24/7 Maroc: +212 5 22 00 00 00</span>
                <span class="hidden md:inline">Casablanca • Rabat • Marrakech • Tanger</span>
            </div>
            <div class="flex items-center gap-3">
                <button @click="lang = 'fr'" :class="lang === 'fr' ? 'text-white font-bold' : 'text-slate-400'">FR 🇫🇷</button>
                <span>|</span>
                <button @click="lang = 'ar'" :class="lang === 'ar' ? 'text-white font-bold' : 'text-slate-400'">العربية 🇲🇦</button>
            </div>
        </div>

        <header class="bg-white/95 border-b border-slate-200 sticky top-0 z-50 backdrop-blur-md shadow-xs">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 {{ $radius }} flex items-center justify-center font-bold text-white shadow-xs" style="background-color: {{ $primaryColor }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-900 tracking-tight block">
                            {{ $agencyName }}
                        </span>
                        <span class="block text-[9.5px] font-bold uppercase tracking-wider text-slate-500 -mt-0.5">
                            Agence Agréée d'Assurance • {{ strtoupper($slug) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center gap-8">
                    <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold text-slate-600">
                        <a href="#services" class="hover:text-slate-900">Offres & Garanties</a>
                        <a href="#sinistres" class="hover:text-slate-900">Déclaration Sinistre</a>
                        <a href="#contact" class="hover:text-slate-900">Contact</a>
                    </nav>

                    <button @click="quoteModal = true" class="text-white font-bold text-xs px-5 py-2.5 {{ $radius }} shadow-xs transition flex items-center gap-2 hover:opacity-95" style="background-color: {{ $primaryColor }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                        <span>Obtenir Devis</span>
                    </button>
                </div>
            </div>
        </header>

        <section class="py-20 bg-gradient-to-b from-slate-50 to-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-12 items-center">
                <div class="md:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Habilité par l'ACAPS • N° Agrément Officiel</span>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Assurance & Conseil pour Familles et Entreprises avec <span style="color: {{ $primaryColor }}">{{ $agencyName }}</span>
                    </h1>

                    <p class="text-slate-600 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                        Protection intégrale pour Automobile, Multirisque Habitation, Santé Complémentaire, Risques Professionnels et Flottes avec tarification ajustée sous 2h.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        <button @click="quoteModal = true" class="text-white font-bold text-xs uppercase tracking-wider px-8 py-3.5 {{ $radius }} shadow-xs transition flex items-center justify-center gap-2" style="background-color: {{ $primaryColor }}">
                            <span>Demander Devis En Ligne</span>
                        </button>
                    </div>
                </div>

                <div class="md:col-span-5">
                    <div class="bg-white border border-slate-200 rounded-2xl p-8 space-y-6 shadow-md">
                        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-base text-slate-900">Devis Rapide sous 2h</h3>
                                <span class="text-xs text-slate-500 font-medium">Garantie des meilleurs tarifs partenaires</span>
                            </div>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition">
                            Lancer la simulation ➔
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Interactive Quote Request Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 space-y-4 shadow-xl text-slate-900">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <h3 class="font-bold text-base text-slate-900">Demande de Devis Instantané</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-lg">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande a été transmise à l\'agence !'); quoteModal = false" class="space-y-3 text-xs font-medium">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom & Prénom</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone GSM</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-200 rounded-xl px-3 py-2">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-xs">
                    Transmettre ma demande ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
