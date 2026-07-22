<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Corporate Blue Insurance' }} | Solutions PME & Holding</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 selection:bg-blue-900 selection:text-white">

    <!-- Institutional Topbar -->
    <div class="bg-blue-950 text-slate-300 text-xs py-2.5 px-8 border-b border-blue-900 flex justify-between items-center font-medium">
        <div class="flex items-center gap-6">
            <span>📞 Assistance Corporate 24/7: +212 5 22 00 00 00</span>
            <span class="hidden md:inline text-blue-300">Casablanca Financial City • Siège Régional</span>
        </div>
        <div class="flex items-center gap-4">
            <button @click="lang = 'fr'" :class="lang === 'fr' ? 'text-white font-bold' : 'text-slate-400'">FR 🇫🇷</button>
            <span class="text-blue-800">|</span>
            <button @click="lang = 'ar'" :class="lang === 'ar' ? 'text-white font-bold' : 'text-slate-400'">العربية 🇲🇦</button>
        </div>
    </div>

    <!-- Corporate Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 bg-blue-900 text-white font-black flex items-center justify-center rounded-lg shadow-sm">C</div>
                <div>
                    <span class="font-extrabold text-xl tracking-tight text-blue-950 block">{{ $agencyName ?? 'Corporate Insurance' }}</span>
                    <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block -mt-0.5">Courtage Agréé ACAPS</span>
                </div>
            </div>
            <nav class="hidden lg:flex items-center gap-8 text-xs font-bold text-slate-700">
                <a href="#audit" class="hover:text-blue-900 transition">Audit des Risques</a>
                <a href="#flottes" class="hover:text-blue-900 transition">Flottes Automobile</a>
                <a href="#at" class="hover:text-blue-900 transition">Accident du Travail</a>
                <a href="#sante" class="hover:text-blue-900 transition">Santé Groupe</a>
                <a href="#contact" class="hover:text-blue-900 transition">Contact</a>
            </nav>
            <button @click="quoteModal = true" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                Devis Institutionnel
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="py-24 bg-gradient-to-b from-blue-50/70 via-white to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-7 space-y-6">
                <span class="inline-block text-[11px] font-extrabold uppercase tracking-widest bg-blue-100 text-blue-900 px-3.5 py-1.5 rounded-md">
                    Conseil & Courtage d'Assurances Entreprises
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                    Solvabilité & Protection Globale pour vos Risques d'Entreprise
                </h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed font-medium">
                    Accompagnement stratégique certifié conformité ACAPS pour les PME et grands groupes industriels au Maroc. Prises en charge sous 2 heures.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-lg shadow-md transition text-center">
                        Lancer un Audit d'Assurance
                    </button>
                    <a href="#services" class="bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-lg shadow-xs hover:bg-slate-50 transition text-center">
                        Consulter nos Offres
                    </a>
                </div>
            </div>
            <div class="md:col-span-5 bg-white border border-slate-200 p-8 rounded-xl shadow-lg space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-lg text-slate-900">Simulateur Risques Pro</h3>
                    <p class="text-xs text-slate-500 font-medium">Garantie des meilleures cotations compagnies</p>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-md flex justify-between items-center font-bold">
                        <span class="text-slate-700">RC Professionnelle & Exploitation</span>
                        <span class="text-blue-900">Garantie 50M DH</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-md flex justify-between items-center font-bold">
                        <span class="text-slate-700">Accident du Travail (AT)</span>
                        <span class="text-blue-900">Conforme ACAPS</span>
                    </div>
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-md flex justify-between items-center font-bold">
                        <span class="text-slate-700">Flotte Automobile d'Entreprise</span>
                        <span class="text-blue-900">Assistance 24/7</span>
                    </div>
                </div>
                <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-3.5 rounded-lg font-bold text-xs uppercase tracking-wider transition">
                    Obtenir une cotation ➔
                </button>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-8 space-y-12">
            <div class="text-center space-y-2 max-w-2xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-900">Expertise Métier</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Solutions Risques & Flottes</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center font-bold text-lg">🏢</div>
                    <h3 class="font-bold text-lg text-slate-900">Multirisque Industrielle</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Protection intégrale des locaux, machines, stocks et pertes d'exploitation après sinistre.</p>
                </div>
                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center font-bold text-lg">🚛</div>
                    <h3 class="font-bold text-lg text-slate-900">Gestion de Flottes</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Optimisation des primes et couverture globale pour parcs de véhicules commerciaux.</p>
                </div>
                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-900 rounded-lg flex items-center justify-center font-bold text-lg">👨‍⚕️</div>
                    <h3 class="font-bold text-lg text-slate-900">Santé & Prévoyance Groupe</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">Couverture maladie complémentaire et garanties décès-invalidité pour salariés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-blue-950 text-slate-400 py-12 text-xs border-t border-blue-900">
        <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <span class="font-bold text-white text-sm block">{{ $agencyName ?? 'Corporate Insurance' }}</span>
                <span class="text-slate-400">Casablanca Financial City • contact@corporate-insurance.ma</span>
            </div>
            <span class="text-slate-500">© {{ date('Y') }} {{ $agencyName ?? 'Corporate Insurance' }}. Tous droits réservés.</span>
        </div>
    </footer>

</body>
</html>
