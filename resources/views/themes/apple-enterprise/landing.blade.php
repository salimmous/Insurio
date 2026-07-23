<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Apple Enterprise Insurance' }} | Corporate Risk Protection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 tracking-tight selection:bg-indigo-600 selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-slate-900 border-b border-slate-800 text-slate-400 text-xs py-2 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <div class="flex items-center gap-6">
                <span>🏢 Division Risques Entreprises & Flottes Maroc</span>
                <span class="hidden md:inline text-slate-700">|</span>
                <span class="hidden md:inline">Casablanca CFC • Rabat Financial District</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-bold border border-blue-500/20">
                    Certifié ISO 27001 • Habilité ACAPS
                </span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-slate-950/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-indigo-500/20">
                    ⌘
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Apple Enterprise' }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block -mt-1">Corporate & Fleet Protection</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-400">
                <a href="#solutions" class="hover:text-white transition">Solutions Entreprise</a>
                <a href="#stats" class="hover:text-white transition">Métriques</a>
                <a href="#clients" class="hover:text-white transition">Références</a>
                <a href="#faq" class="hover:text-white transition">FAQ Corporate</a>
            </nav>

            <div class="flex items-center gap-4">
                <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-lg shadow-indigo-600/30 transition">
                    Audit de Risque Gratuit ➔
                </button>
            </div>
        </div>
    </header>

    <!-- 90vh Executive Hero Section -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 border-b border-slate-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/20 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full relative z-10">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-950/80 border border-indigo-800/60 text-xs font-bold text-indigo-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Gestion de Risques Industriels & Flottes d'Entreprise
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Protection Maximale <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-blue-400 to-cyan-400">Pour Vos Actifs Stratégiques.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-normal">
                    Cabinet de courtage spécialisé dans la gestion des grands comptes, PME/PMI et flottes au Maroc. Multirisque industrielle, RC Dirigeants et Transport.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-xl shadow-indigo-600/30 text-center">
                        Demander un Appel d'Offres ➔
                    </button>
                    <a href="#solutions" class="bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-700 font-bold px-8 py-4 rounded-full text-xs transition text-center">
                        Explorer les garanties
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-slate-800/80 grid grid-cols-3 gap-6 text-slate-400 text-xs font-semibold">
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">🏛️</span>
                        <span>Grands Comptes & PME</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">📈</span>
                        <span>Optimisation de Prime</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">🤝</span>
                        <span>Gestionnaire Dédié</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Live Risk Matrix Card -->
            <div class="lg:col-span-5">
                <div class="bg-slate-900/90 rounded-3xl p-8 border border-slate-800 shadow-2xl space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 block">Tableau de Bord Risque</span>
                            <h3 class="font-extrabold text-xl text-white">Simulateur Flotte & RC</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-indigo-900/50 text-indigo-400 flex items-center justify-center font-bold text-lg">📊</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div>
                            <label class="block font-bold text-slate-300 mb-1">Nombre de véhicules en flotte</label>
                            <input type="number" value="15" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 font-mono font-bold text-indigo-400">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Secteur d'activité</label>
                            <select class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 font-semibold text-slate-300">
                                <option>Transport & Logistique</option>
                                <option>BTP & Construction</option>
                                <option>Industrie & Agroalimentaire</option>
                                <option>Services & Nouvelles Technologies</option>
                            </select>
                        </div>

                        <div class="p-4 rounded-2xl bg-indigo-950/60 border border-indigo-800/50 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Couverture Maximale</span>
                                <span class="text-2xl font-black text-emerald-400 font-mono">100M DH</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">Tier 1 Insured</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Recevoir la Cotation Corporate ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos -->
    <section class="py-10 bg-slate-900 border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-500 mb-6">Compagnies d'Assurance Partenaires au Maroc</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-70 font-black text-slate-400 text-lg">
                <span>WAFA ASSURANCE</span>
                <span>AXA ASSURANCE MAROC</span>
                <span>RMA WATANIYA</span>
                <span>SANLAM CORPORATE</span>
                <span>CAT RISQUES CAPITAL</span>
            </div>
        </div>
    </section>

    <!-- Executive Stats -->
    <section id="stats" class="py-20 bg-slate-950">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 space-y-2">
                <span class="text-4xl font-black text-indigo-400 font-mono">140M DH</span>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Primes Souscrites Gérées</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 space-y-2">
                <span class="text-4xl font-black text-emerald-400 font-mono">500+</span>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Entreprises & Flottes</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 space-y-2">
                <span class="text-4xl font-black text-cyan-400 font-mono">15min</span>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Prise en Charge Sinistre Pro</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900/60 border border-slate-800 space-y-2">
                <span class="text-4xl font-black text-purple-400 font-mono">100%</span>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Conformité Réglementaire ACAPS</span>
            </div>
        </div>
    </section>

    <!-- 6 Corporate Solutions Cards -->
    <section id="solutions" class="py-24 bg-slate-900 border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block">Solutions Entreprises</span>
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight">Une Protection à la Hauteur de Vos Enjeux.</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-950 text-indigo-400 flex items-center justify-center text-2xl font-bold">🚛</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">Assurance Flotte Automobile</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Gestion centralisée des véhicules légers, poids lourds et engins de chantier avec conciergerie sinistre.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis Flotte ➔</button>
                </div>

                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-950 text-emerald-400 flex items-center justify-center text-2xl font-bold">🛡️</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">RC Civile & Exploitation</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Couverture des dommages causés aux tiers, RC Professionnelle, RC Produits et Défense Recours.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis RC ➔</button>
                </div>

                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-purple-950 text-purple-400 flex items-center justify-center text-2xl font-bold">🏢</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">Multirisque Industrielle</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Protection des locaux, stocks, machines, pertes d'exploitation et bris de machine d'urgence.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis Usine ➔</button>
                </div>

                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-950 text-cyan-400 flex items-center justify-center text-2xl font-bold">👷</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">Accidents du Travail (AT)</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Conformité légale stricte au Maroc pour la couverture de l'ensemble de vos collaborateurs et ouvriers.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis AT ➔</button>
                </div>

                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-amber-950 text-amber-400 flex items-center justify-center text-2xl font-bold">✈️</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">Transport & Cargo</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Assurance des marchandises transportées par voie terrestre, maritime et aérienne à l'international.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis Cargo ➔</button>
                </div>

                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6 hover:border-indigo-600/50 transition">
                    <div class="w-14 h-14 rounded-2xl bg-rose-950 text-rose-400 flex items-center justify-center text-2xl font-bold">👨‍💼</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-white">RC Mandataires Sociaux</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Protection du patrimoine personnel des dirigeants et administrateurs contre les mises en cause.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-indigo-400 font-bold py-3 rounded-xl text-xs transition border border-slate-800">Devis Dirigeant ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Client References -->
    <section id="clients" class="py-24 bg-slate-950">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Témoignages Direction</span>
                <h2 class="text-3xl font-black text-white">Ils Nous Font Confiance.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4">
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">"Un accompagnement précieux lors de l'audit annuel de nos 4 sites industriels à Mohammedia. Économie de 15% sur la prime globale."</p>
                    <div class="font-bold text-xs text-white">— Directeur Financier, Groupe Industriel Marocain</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4">
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">"Gestion sans faille de notre flotte de 45 véhicules de livraison. Réactivité exemplaire lors de chaque déclaration de sinistre."</p>
                    <div class="font-bold text-xs text-white">— Responsable Logistique, Casablanca</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 space-y-4">
                    <p class="text-xs text-slate-300 leading-relaxed font-medium">"Courtier de grande rigueur. La couverture AT et Santé Groupe de nos 200 employés est gérée avec une clarté remarquable."</p>
                    <div class="font-bold text-xs text-white">— DRH, Entreprise de Services, Rabat</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Corporate FAQ -->
    <section id="faq" class="py-24 bg-slate-900 border-t border-slate-800">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Questions Entreprises</span>
                <h2 class="text-3xl font-black text-white">Expertise & Réglementation.</h2>
            </div>

            <div class="space-y-4 text-xs font-medium">
                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 1 ? null : 1)" class="w-full text-left font-bold text-white text-sm flex justify-between items-center">
                        <span>Comment s'effectue le transfert de gestion de nos contrats d'assurance ?</span>
                        <span class="text-indigo-400 font-bold" x-text="faqOpen === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 1" class="pt-3 text-slate-400 leading-relaxed">
                        Nous gérons l'intégralité du transfert via un ordre de remplacement formel sans interruption de vos garanties ni pénalités.
                    </div>
                </div>

                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 2 ? null : 2)" class="w-full text-left font-bold text-white text-sm flex justify-between items-center">
                        <span>Proposez-vous un accompagnement dédié en cas de contrôle ACAPS ou audit ?</span>
                        <span class="text-indigo-400 font-bold" x-text="faqOpen === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 2" class="pt-3 text-slate-400 leading-relaxed">
                        Oui, nos juristes et experts en assurances professionnelles vous assistent pour garantir la conformité intégrale de vos polices avec la législation marocaine.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong Corporate CTA -->
    <section class="py-20 bg-indigo-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Optimisez la Protection de Votre Entreprise Dès Aujourd'hui.</h2>
            <p class="text-indigo-100 text-sm max-w-xl mx-auto">Confiez vos risques industriels et flottes à un cabinet de courtage de référence.</p>
            <button @click="quoteModal = true" class="bg-slate-950 hover:bg-slate-900 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Prendre Rendez-vous avec un Expert Corporate ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-16 text-xs border-t border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 grid md:grid-cols-4 gap-10">
            <div class="space-y-4">
                <span class="font-black text-xl text-white block">{{ $agencyName ?? 'Apple Enterprise' }}</span>
                <p class="leading-relaxed">Cabinet de courtage d'assurance agréé ACAPS spécialisé dans les risques d'entreprises et flottes au Maroc.</p>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Gamme Corporate</span>
                <ul class="space-y-2 font-medium">
                    <li>Flottes Automobile</li>
                    <li>Multirisque Industrielle</li>
                    <li>RC Dirigeants & Professionnelle</li>
                    <li>Accidents du Travail</li>
                </ul>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Conformité & Droits</span>
                <ul class="space-y-2 font-medium">
                    <li>Agrément ACAPS N° 2026</li>
                    <li>Charte Confidentialité PME</li>
                    <li>Gestion des Recours Sinistre</li>
                </ul>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Siège Social</span>
                <p class="leading-relaxed font-medium">
                    📍 Anfa Place, Casablanca<br>
                    📞 +212 5 22 99 00 00<br>
                    ✉️ corporate@insurio.ma
                </p>
            </div>
        </div>
        <div class="max-w-[1440px] mx-auto px-6 pt-12 border-t border-slate-900 text-center text-slate-600">
            © {{ date('Y') }} {{ $agencyName ?? 'Apple Enterprise' }}. Tous droits réservés.
        </div>
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl border border-slate-800 text-white">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-black text-lg text-white">Demande de Cotation Corporate</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande de cotation entreprise a été transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Raison Sociale / Société *</label>
                    <input type="text" required placeholder="Nom de votre entreprise" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Nom & Fonction du Contact *</label>
                    <input type="text" required placeholder="ex: Karim Benali (DAF)" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Téléphone Direct *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-xl transition shadow-lg">
                    Transmettre la Demande à nos Experts ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
