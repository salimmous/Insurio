<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Corporate Blue Insurance' }} | Cabinet Agréé d'Assurance & Conseil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 selection:bg-blue-900 selection:text-white">

    <!-- Official Moroccan Header Bar -->
    <div class="bg-blue-950 text-blue-100 text-xs py-2.5 px-6 border-b border-blue-900">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <div class="flex items-center gap-6">
                <span>📍 Casablanca • Rabat • Marrakech • Agadir • Tanger</span>
                <span class="hidden md:inline text-blue-700">|</span>
                <span class="hidden md:inline">📞 Ligne Directe Agence: +212 5 22 44 55 66</span>
            </div>
            <div class="flex items-center gap-4 font-bold text-amber-400">
                <span>🏛️ Agrément Officiel ACAPS N° 2026/88</span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-blue-900 text-white flex items-center justify-center font-black text-xl shadow-md shadow-blue-900/20">
                    🏛️
                </div>
                <div>
                    <span class="font-extrabold text-xl text-blue-950 tracking-tight block">{{ $agencyName ?? 'Corporate Insurance' }}</span>
                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-widest block -mt-1">Cabinet de Courtage et de Conseil</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-blue-900 transition">Assurance Particuliers</a>
                <a href="#pro" class="hover:text-blue-900 transition">Entreprises & Flottes</a>
                <a href="#stats" class="hover:text-blue-900 transition">Chiffres Institutionnels</a>
                <a href="#faq" class="hover:text-blue-900 transition">FAQ Légale</a>
            </nav>

            <button @click="quoteModal = true" class="bg-blue-900 hover:bg-blue-800 text-white px-7 py-3 rounded-xl font-bold text-xs shadow-md transition">
                Devis Express ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero Section -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 text-white relative border-b border-blue-950">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-900/80 border border-blue-700/60 text-xs font-bold text-amber-300">
                    <span>👑 L'Excellence du Courtage d'Assurance au Maroc</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight text-white">
                    Protégez Vos Projets. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-200">Avec Une Institution de Confiance.</span>
                </h1>

                <p class="text-blue-100 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Partenaire privilégié des plus grandes compagnies d'assurance au Maroc. Nous négocions pour vous les meilleures garanties aux tarifs les plus compétitifs du marché.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-500 hover:bg-amber-400 text-blue-950 font-black px-8 py-4 rounded-xl text-xs transition shadow-lg shadow-amber-500/20 text-center">
                        Demander Une Cotation Institutionnelle ➔
                    </button>
                    <a href="#offres" class="bg-blue-900/60 hover:bg-blue-900 text-white border border-blue-700 font-bold px-8 py-4 rounded-xl text-xs transition text-center">
                        Découvrir Nos Garanties
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-blue-800/80 grid grid-cols-3 gap-6 text-blue-200 text-xs font-semibold">
                    <div class="flex items-center gap-2.5">
                        <span class="text-amber-400 text-lg">⚖️</span>
                        <span>Défense Juridique Incluse</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-amber-400 text-lg">🤝</span>
                        <span>Négociation Grands Comptes</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-amber-400 text-lg">⏱️</span>
                        <span>Traitement Sinistre Express</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card Form -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 text-slate-900 shadow-2xl border border-slate-200 space-y-6">
                    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-900 block">Formulaire Officiel</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Demande de Tarif Rapide</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-lg">📄</span>
                    </div>

                    <form @submit.prevent="alert('Demande enregistrée avec succès !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Type de Couverture Souhaitée</label>
                            <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 font-semibold text-slate-800">
                                <option>🚗 Assurance Auto & Flotte</option>
                                <option>🏡 Multirisque Habitation (MSH)</option>
                                <option>🩺 Santé Complémentaire & Groupe</option>
                                <option>🏗️ Tous Risques Chantier / TRC</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nom Complet</label>
                            <input type="text" required placeholder="Votre Nom et Prénom" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Téléphone GSM Maroc</label>
                            <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                        </div>

                        <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl transition text-xs shadow-md">
                            Lancer la Simulation ➔
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Partners Logo Bar -->
    <section class="py-10 bg-slate-50 border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">Compagnies d'Assurance sous Convention</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-70 font-black text-slate-700 text-lg">
                <span>WAFA ASSURANCE</span>
                <span>AXA ASSURANCE</span>
                <span>RMA WATANIYA</span>
                <span>SANLAM MAROC</span>
                <span>SAHAM ASSURANCE</span>
                <span>MAMDA MCMA</span>
            </div>
        </div>
    </section>

    <!-- Institutional Stats -->
    <section id="stats" class="py-20 bg-blue-950 text-white">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-amber-400 font-mono">25+ Ans</span>
                <span class="text-xs font-bold text-blue-200 block mt-2 uppercase tracking-wider">D'Expertise au Maroc</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">45,000+</span>
                <span class="text-xs font-bold text-blue-200 block mt-2 uppercase tracking-wider">Contrats Actifs</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">99.4%</span>
                <span class="text-xs font-bold text-blue-200 block mt-2 uppercase tracking-wider">Sinistres Indemnisés</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-purple-400 font-mono">15</span>
                <span class="text-xs font-bold text-blue-200 block mt-2 uppercase tracking-wider">Agences Régionales</span>
            </div>
        </div>
    </section>

    <!-- Insurance Products Grid -->
    <section id="offres" class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-widest block">Garanties & Formules</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Des Protections Conçues Pour Votre Sérénité.</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-900 flex items-center justify-center text-2xl font-bold">🚗</div>
                    <h3 class="text-xl font-extrabold text-slate-900">Auto & Moto</h3>
                    <p class="text-slate-600 text-xs leading-relaxed font-medium">Formules Tiers, Tierce Collision et Tous Risques avec garantie valeur à neuf.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl text-xs">Obtenir Devis ➔</button>
                </div>

                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-900 flex items-center justify-center text-2xl font-bold">🏡</div>
                    <h3 class="text-xl font-extrabold text-slate-900">Multirisque Habitation</h3>
                    <p class="text-slate-600 text-xs leading-relaxed font-medium">Protection intégrale de votre résidence principale ou secondaire contre tout risque.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl text-xs">Obtenir Devis ➔</button>
                </div>

                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-900 flex items-center justify-center text-2xl font-bold">🩺</div>
                    <h3 class="text-xl font-extrabold text-slate-900">Santé & Mutuelle</h3>
                    <p class="text-slate-600 text-xs leading-relaxed font-medium">Complémentaire santé d'excellence pour la prise en charge des frais médicaux élevés.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl text-xs">Obtenir Devis ➔</button>
                </div>

                <div id="pro" class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 text-purple-900 flex items-center justify-center text-2xl font-bold">🏢</div>
                    <h3 class="text-xl font-extrabold text-slate-900">Risques Entreprises</h3>
                    <p class="text-slate-600 text-xs leading-relaxed font-medium">RC Professionnelle, Accidents du Travail et Flottes d'entreprises sur-mesure.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-900 text-white font-bold py-3 rounded-xl text-xs">Obtenir Devis ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-widest">Témoignages Assurés</span>
                <h2 class="text-3xl font-black text-slate-900">La Confiance de Nos Clients.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 text-xs font-medium">
                <div class="p-8 rounded-3xl bg-white border border-slate-200 space-y-4 shadow-sm">
                    <div class="text-amber-500">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed">"Service de conseil d'une grande valeur. Le cabinet a négocié notre contrat de flotte professionnelle avec 20% de réduction."</p>
                    <div class="font-bold text-slate-900">— Hassan E., Casablanca</div>
                </div>
                <div class="p-8 rounded-3xl bg-white border border-slate-200 space-y-4 shadow-sm">
                    <div class="text-amber-500">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed">"Traitement de dossier sinistre dégât des eaux rapide et indemnisation intégrale versée sous 10 jours."</p>
                    <div class="font-bold text-slate-900">— Mouna L., Rabat</div>
                </div>
                <div class="p-8 rounded-3xl bg-white border border-slate-200 space-y-4 shadow-sm">
                    <div class="text-amber-500">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed">"Agence très professionnelle. Toujours joignables pour des explications claires et un suivi personnalisé."</p>
                    <div class="font-bold text-slate-900">— Rachid M., Marrakech</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional FAQ -->
    <section id="faq" class="py-24 bg-white border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-widest">FAQ Réglementaire</span>
                <h2 class="text-3xl font-black text-slate-900">Foire Aux Questions.</h2>
            </div>

            <div class="space-y-4 text-xs font-medium">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 1 ? null : 1)" class="w-full text-left font-bold text-slate-900 text-sm flex justify-between items-center">
                        <span>Pourquoi passer par un cabinet de courtage plutôt qu'une compagnie directe ?</span>
                        <span class="text-blue-900 font-bold" x-text="faqOpen === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 1" class="pt-3 text-slate-600 leading-relaxed">
                        Le courtier est votre représentant indépendant. Il compare l'ensemble du marché et défend vos intérêts auprès des compagnies pour obtenir les meilleurs tarifs et conditions.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-blue-950 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Confiez Vos Assurance à des Experts Agréés.</h2>
            <button @click="quoteModal = true" class="bg-amber-500 hover:bg-amber-400 text-blue-950 font-black px-10 py-4 rounded-xl text-xs shadow-xl transition">
                Consulter un Conseiller Agence ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-950 text-blue-200 py-16 text-xs border-t border-blue-900">
        <div class="max-w-[1440px] mx-auto px-6 text-center text-blue-400">
            © {{ date('Y') }} {{ $agencyName ?? 'Corporate Insurance' }}. Tous droits réservés. Agrée ACAPS.
        </div>
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-blue-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-blue-950">Demande de Devis Institutionnel</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande a bien été transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl transition shadow-md">
                    Envoyer ma Demande ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
