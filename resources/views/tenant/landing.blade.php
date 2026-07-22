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

    <!-- Header Navigation with Interactive Language Switch (🇫🇷 FR / 🇲🇦 AR) -->
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
                    <span class="block text-[9px] font-bold uppercase tracking-widest -mt-1" style="color: {{ $accentColor }}">
                        <span x-show="lang === 'fr'">Agence Agréée • Assurance & Courtage</span>
                        <span x-show="lang === 'ar'" style="display:none;">وكالة تأمين واستشارات معتمدة</span>
                    </span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Navigation Links -->
                <a href="#services" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">
                    <span x-show="lang === 'fr'">Nos Services</span>
                    <span x-show="lang === 'ar'" style="display:none;">خدماتنا</span>
                </a>
                <a href="#sinistres" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">
                    <span x-show="lang === 'fr'">Sinistres & Assistance</span>
                    <span x-show="lang === 'ar'" style="display:none;">الحوادث والنجدة</span>
                </a>
                <a href="#contact" class="text-xs font-bold uppercase tracking-wider {{ $isDark ? 'text-slate-400 hover:text-white' : 'text-slate-600 hover:text-slate-900' }} transition-colors hidden md:block">
                    <span x-show="lang === 'fr'">Contact</span>
                    <span x-show="lang === 'ar'" style="display:none;">اتصل بنا</span>
                </a>
                
                <!-- BILINGUAL LANGUAGE SWITCHER (🇫🇷 / 🇲🇦) -->
                <div class="flex items-center gap-1 bg-slate-900/60 p-1 rounded-xl border border-slate-800">
                    <button @click="lang = 'fr'" :class="lang === 'fr' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition flex items-center gap-1">
                        🇫🇷 FR
                    </button>
                    <button @click="lang = 'ar'" :class="lang === 'ar' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition flex items-center gap-1">
                        🇲🇦 العربية
                    </button>
                </div>

                <button @click="quoteModal = true" class="text-white font-extrabold text-xs px-5 py-2.5 {{ $radius }} transition-all shadow-lg flex items-center gap-2" style="background-color: {{ $primaryColor }}">
                    <span x-show="lang === 'fr'">Devis Instantané 📝</span>
                    <span x-show="lang === 'ar'" style="display:none;">طلب تسعيرة 📝</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section with RTL / LTR Native Adaptation -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-24 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 {{ $radius }} border text-xs font-extrabold" style="background-color: {{ $primaryColor }}15; border-color: {{ $primaryColor }}40; color: {{ $accentColor }}">
                <span x-show="lang === 'fr'">🛡️ Courtage et Assurances Agréés au Maroc</span>
                <span x-show="lang === 'ar'" style="display:none;">🛡️ وكالة تأمين واستشارات معتمدة بالمغرب</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black leading-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                <span x-show="lang === 'fr'">Protégez vos proches & votre avenir avec <span style="color: {{ $accentColor }}">{{ $agencyName }}</span></span>
                <span x-show="lang === 'ar'" style="display:none;">احمِ عائلتك ومستقبلك مع <span style="color: {{ $accentColor }}">{{ $agencyName }}</span></span>
            </h1>
            
            <p class="{{ $isDark ? 'text-slate-400' : 'text-slate-600' }} text-sm md:text-base max-w-xl leading-relaxed">
                <span x-show="lang === 'fr'">Agence d'assurance réactive à votre écoute pour vous accompagner dans le choix des meilleures garanties Automobile, Multirisque Habitation, Santé et Entreprises.</span>
                <span x-show="lang === 'ar'" style="display:none;">وكالة تأمين سريعة التجاوب لتوفير أفضل التغطيات التأمينية للسيارات، المنازل، الصحة والشركات بأفضل الأسعار بالمغرب.</span>
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <button @click="quoteModal = true" class="{{ $radius }} text-white font-extrabold text-xs uppercase tracking-wider px-8 py-3.5 text-center transition-all shadow-lg flex items-center justify-center gap-2" style="background-color: {{ $primaryColor }}">
                    <span x-show="lang === 'fr'">Obtenir Devis Gratuit</span>
                    <span x-show="lang === 'ar'" style="display:none;">احصل على تسعيرة مجانية</span>
                </button>
                <a href="#sinistres" class="{{ $radius }} border text-xs uppercase font-extrabold tracking-wider px-8 py-3.5 text-center transition-all shadow-sm {{ $isDark ? 'bg-slate-900 border-slate-800 text-white hover:bg-slate-800' : 'bg-slate-100 border-slate-200 text-slate-900 hover:bg-slate-200' }}">
                    <span x-show="lang === 'fr'">Déclarer Un Sinistre 🚨</span>
                    <span x-show="lang === 'ar'" style="display:none;">التصريح بالحادث 🚨</span>
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="p-8 {{ $radius }} relative space-y-6 border shadow-2xl transition-all" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}50">
                <div class="w-12 h-12 {{ $radius }} flex items-center justify-center font-black text-xl shadow-inner" style="background-color: {{ $primaryColor }}30; color: {{ $accentColor }}">
                    ✓
                </div>
                <h3 class="text-xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                    <span x-show="lang === 'fr'">Simulation En Ligne Instantanée</span>
                    <span x-show="lang === 'ar'" style="display:none;">محاكاة فورية عبر الإنترنت</span>
                </h3>
                <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }} leading-relaxed">
                    <span x-show="lang === 'fr'">Étude de votre dossier sous 2 heures avec garantie des tarifs les plus avantageux des compagnies partenaires.</span>
                    <span x-show="lang === 'ar'" style="display:none;">دراسة ملفكم خلال ساعتين مع ضمان أفضل الأسعار من شركات التأمين الشريكة.</span>
                </p>
                <div class="border-t border-slate-800/40 pt-4 flex justify-between items-center text-xs font-bold">
                    <span class="{{ $isDark ? 'text-slate-500' : 'text-slate-400' }}">
                        <span x-show="lang === 'fr'">Assistance 24h/7j</span>
                        <span x-show="lang === 'ar'" style="display:none;">خدمة النجدة 24/24</span>
                    </span>
                    <span class="font-mono" style="color: {{ $accentColor }}">+212 5 22 00 00 00</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners & Accreditations (AXA, RMA, Sanlam, Wafa, Zurich, Allianz) -->
    <section class="py-8 border-y border-slate-800/60 bg-slate-950/40 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 text-center space-y-4">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">
                <span x-show="lang === 'fr'">COMPAGNIES PARTENAIRES AGRÉÉES</span>
                <span x-show="lang === 'ar'" style="display:none;">شركات التأمين الشريكة المعتمدة</span>
            </span>
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-14 font-black text-slate-400 text-sm tracking-tight opacity-70">
                <span>AXA ASSURANCE</span>
                <span>RMA WATANIYA</span>
                <span>SANLAM MAROC</span>
                <span>WAFA ASSURANCE</span>
                <span>ZURICH</span>
                <span>ALLIANZ</span>
            </div>
        </div>
    </section>

    <!-- 12 Insurance Components Grid -->
    <section id="services" class="border-t py-20 {{ $isDark ? 'border-slate-900/80' : 'border-slate-200 bg-slate-50' }}" style="background-color: {{ $bgColor }}">
        <div class="max-w-7xl mx-auto px-6 space-y-14">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-widest" style="color: {{ $accentColor }}">
                    <span x-show="lang === 'fr'">SOLUTIONS SUR-MESURE</span>
                    <span x-show="lang === 'ar'" style="display:none;">حلول تأمينية متكاملة</span>
                </span>
                <h2 class="text-3xl font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                    <span x-show="lang === 'fr'">Toutes Nos Offres d'Assurances</span>
                    <span x-show="lang === 'ar'" style="display:none;">جميع عروض التأمين والاستشارة</span>
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- 1. Auto -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🚗</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Automobile</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Tous Risques, Tierce, Bris de Glace, Vol & Incendie avec assistance dépannage 0 km.</p>
                </div>
                <!-- 2. Habitation -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🏠</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Multirisque Habitation</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Protection complète de vos villas et appartements contre incendie, vol et dégât des eaux.</p>
                </div>
                <!-- 3. Santé -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🏥</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Santé & Prévoyance</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Remboursement des frais médicaux, hospitalisation, dentaire et maternité au Maroc et à l'étranger.</p>
                </div>
                <!-- 4. Entreprise -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🏢</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Risques Entreprises & Flottes</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Multirisque industrielle, accident du travail (AT), responsabilité civile professionnelle.</p>
                </div>
                <!-- 5. Vie -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">🛡️</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Vie & Décès</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Garantissez l'avenir financier de votre famille en cas d'imprévu ou d'invalidité.</p>
                </div>
                <!-- 6. Voyage -->
                <div class="p-6 {{ $radius }} border space-y-3 shadow-sm hover:shadow-xl transition" style="background-color: {{ $cardBgColor }}; border-color: {{ $primaryColor }}30">
                    <div class="text-3xl">✈️</div>
                    <h3 class="text-lg font-black {{ $isDark ? 'text-white' : 'text-slate-900' }}">Assurance Voyage Schengen</h3>
                    <p class="text-xs {{ $isDark ? 'text-slate-400' : 'text-slate-600' }}">Attestation conforme pour demandes de visa Schengen, Omra et déplacements professionnels.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-slate-950 text-white border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-1">
                <div class="text-4xl font-black text-teal-400">15 000+</div>
                <div class="text-xs font-bold text-slate-400">Assurés Conquis</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-black text-teal-400">98%</div>
                <div class="text-xs font-bold text-slate-400">Satisfaction Client</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-black text-teal-400">&lt; 2h</div>
                <div class="text-xs font-bold text-slate-400">Délai d'Étude Devis</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-black text-teal-400">24h/7j</div>
                <div class="text-xs font-bold text-slate-400">Assistance Dépannage</div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer id="contact" class="border-t py-12 {{ $isDark ? 'border-slate-900 bg-slate-950 text-slate-500' : 'border-slate-200 bg-white text-slate-600' }}">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-xs">
            <div>
                <span class="font-extrabold text-sm block {{ $isDark ? 'text-white' : 'text-slate-900' }}">{{ $agencyName }}</span>
                <span class="mt-1 block">Casablanca, Maroc • contact@agence-assurance.ma</span>
            </div>
            <div class="text-right">
                <span class="block">© {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.</span>
                <span class="text-[10px] text-teal-500 font-bold block mt-0.5">Propulsé par Insurio Enterprise Website Engine (v2.0)</span>
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
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Type d'Assurance</label>
                    <select class="w-full border border-slate-200 rounded-xl px-3 py-2">
                        <option>Assurance Automobile</option>
                        <option>Multirisque Habitation</option>
                        <option>Santé & Prévoyance</option>
                        <option>Flotte & Entreprise</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-950 font-black py-3 rounded-xl transition shadow-md">
                    Envoyer ma demande 🚀
                </button>
            </form>
        </div>
    </div>

</body>
</html>
