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
    $slug = $theme->slug ?? 'axa-inspire';
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

    <!-- Top Assistance Bar (Enterprise Standard) -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-6 border-b border-slate-800 flex justify-between items-center font-medium">
        <div class="flex items-center gap-6">
            <span class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>Assistance 24/7 Maroc: +212 5 22 00 00 00</span>
            </span>
            <span class="hidden md:inline-flex items-center gap-2 text-slate-400">
                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Casablanca • Rabat • Marrakech • Tanger</span>
            </span>
        </div>
        
        <!-- Language Switcher -->
        <div class="flex items-center gap-3">
            <button @click="lang = 'fr'" :class="lang === 'fr' ? 'text-white font-bold' : 'text-slate-400 hover:text-white'" class="transition">FR 🇫🇷</button>
            <span class="text-slate-700">|</span>
            <button @click="lang = 'ar'" :class="lang === 'ar' ? 'text-white font-bold' : 'text-slate-400 hover:text-white'" class="transition">العربية 🇲🇦</button>
        </div>
    </div>

    <!-- Sticky Main Navigation Header -->
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
                        <span x-show="lang === 'fr'">Agence Agréée d'Assurance • {{ strtoupper($slug) }}</span>
                        <span x-show="lang === 'ar'" style="display:none;">وكالة تأمين معتمدة بالمملكة المغربية</span>
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-8">
                <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold text-slate-600">
                    <a href="#services" class="hover:text-slate-900 transition">Offres & Garanties</a>
                    <a href="#sinistres" class="hover:text-slate-900 transition">Déclaration Sinistre</a>
                    <a href="#agencies" class="hover:text-slate-900 transition">Nos Agences</a>
                    <a href="#contact" class="hover:text-slate-900 transition">Contact</a>
                </nav>

                <button @click="quoteModal = true" class="text-white font-bold text-xs px-5 py-2.5 {{ $radius }} shadow-xs transition flex items-center gap-2 hover:opacity-95" style="background-color: {{ $primaryColor }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                    <span>Obtenir Devis</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Structural Theme Hero Section -->
    <section class="py-20 bg-gradient-to-b from-slate-50 to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>Habilité par l'ACAPS • N° Agrément Officiel</span>
                </div>

                <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    <span x-show="lang === 'fr'">Assurance & Conseil pour Familles et Entreprises avec <span style="color: {{ $primaryColor }}">{{ $agencyName }}</span></span>
                    <span x-show="lang === 'ar'" style="display:none;">تأمين واستشارات موثوقة للعائلات والشركات بالمغرب</span>
                </h1>

                <p class="text-slate-600 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                    Protection intégrale pour Automobile, Multirisque Habitation, Santé Complémentaire, Risques Professionnels et Flottes avec tarification ajustée sous 2h.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="text-white font-bold text-xs uppercase tracking-wider px-8 py-3.5 {{ $radius }} shadow-xs transition flex items-center justify-center gap-2" style="background-color: {{ $primaryColor }}">
                        <span>Demander Devis En Ligne</span>
                    </button>
                    <a href="#sinistres" class="bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider px-8 py-3.5 {{ $radius }} shadow-xs transition text-center hover:bg-slate-50">
                        <span>Déclarer Un Sinistre</span>
                    </a>
                </div>
            </div>

            <!-- Simulation Box -->
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

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold">
                            <span class="text-slate-600">Assurance Auto Tierce / Tous Risques</span>
                            <span class="text-indigo-600">Disponible</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold">
                            <span class="text-slate-600">Multirisque Habitation & Vol</span>
                            <span class="text-indigo-600">Disponible</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 font-semibold">
                            <span class="text-slate-600">Santé Complémentaire & Voyage</span>
                            <span class="text-indigo-600">Disponible</span>
                        </div>
                    </div>

                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-3 rounded-xl shadow-xs transition">
                        Lancer la simulation ➔
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Accreditations Strip -->
    <section class="py-10 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 text-center space-y-4">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Compagnies d'Assurance Partenaires Agréées au Maroc
            </span>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 font-extrabold text-slate-400 text-sm tracking-tight">
                <span>AXA ASSURANCE</span>
                <span>RMA WATANIYA</span>
                <span>SANLAM MAROC</span>
                <span>WAFA ASSURANCE</span>
                <span>ZURICH</span>
                <span>ALLIANZ</span>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="py-20 bg-[#F8FAFC]">
        <div class="max-w-7xl mx-auto px-6 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Solutions Sur-Mesure</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Nos Garanties & Offres</h2>
                <p class="text-xs text-slate-500 font-medium">Une gamme d'assurances complètes adaptées aux particuliers et professionnels.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Auto -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A2 2 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/></svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900">Assurance Automobile</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Garanties Tous Risques, Tierce collision, vol, incendie et bris de glace avec assistance 0 km.</p>
                </div>

                <!-- Habitation -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900">Multirisque Habitation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Protection intégrale de votre résidence contre l'incendie, le dégât des eaux et le vol.</p>
                </div>

                <!-- Santé -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <h3 class="font-bold text-base text-slate-900">Santé & Prévoyance</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-medium">Remboursement des soins médicaux, optiques, dentaires et prises en charge d'hospitalisation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-slate-900 text-slate-400 py-12 text-xs font-medium border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <span class="font-bold text-sm text-white block">{{ $agencyName }}</span>
                <span class="mt-1 block text-slate-400">Casablanca, Maroc • contact@agence-assurance.ma</span>
            </div>
            <div class="text-right text-slate-500">
                <span class="block">© {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.</span>
                <span class="text-[10px] text-indigo-400 font-semibold block mt-0.5">Propulsé par Insurio Enterprise Theme Engine V3</span>
            </div>
        </div>
    </footer>

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
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Branche d'Assurance</label>
                    <select class="w-full border border-slate-200 rounded-xl px-3 py-2 font-medium">
                        <option>Assurance Automobile</option>
                        <option>Multirisque Habitation</option>
                        <option>Santé & Prévoyance</option>
                        <option>Risques Entreprises & Flottes</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-xs">
                    Transmettre ma demande ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
