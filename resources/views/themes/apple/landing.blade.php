<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Apple Insurance' }} | Protection Minimaliste & Intelligente</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 tracking-tight selection:bg-slate-900 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-slate-900 text-slate-400 text-xs py-2.5 px-6 border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <div class="flex items-center gap-6">
                <span>📞 Assistance Urgente 24/7: +212 5 22 00 00 00</span>
                <span class="hidden md:inline text-slate-500">|</span>
                <span class="hidden md:inline">Casablanca • Rabat • Marrakech • Tanger</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-[10px] font-bold border border-emerald-500/20">
                    ● Agréé ACAPS N° 2026/89
                </span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-xl">
                    
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Apple Insurance' }}</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest block -mt-1">Courtage & Protection Digital</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#solutions" class="hover:text-slate-900 transition">Offres</a>
                <a href="#stats" class="hover:text-slate-900 transition">Performance</a>
                <a href="#avis" class="hover:text-slate-900 transition">Avis Clients</a>
                <a href="#faq" class="hover:text-slate-900 transition">FAQ</a>
            </nav>

            <div class="flex items-center gap-4">
                <button @click="quoteModal = true" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-sm transition">
                    Simuler un Devis ➔
                </button>
            </div>
        </div>
    </header>

    <!-- 90vh Hero Section -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-b from-white via-slate-50 to-slate-100 border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-200/80 border border-slate-300 text-xs font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Garanties Intelligentes & Tarification en Temps Réel
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 leading-[1.05] tracking-tight">
                    L'Assurance. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Purifiée & Simplifiée.</span>
                </h1>

                <p class="text-slate-600 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Protection intégrale pour Automobile, Multirisque Habitation et Santé Complémentaire au Maroc. Zéro paperasse inutile, remboursement sous 48h.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-blue-500/20 text-center">
                        Demander un devis immédiat ➔
                    </button>
                    <a href="#solutions" class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-bold px-8 py-4 rounded-full text-xs transition text-center shadow-xs">
                        Consulter les formules
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-slate-200/80 grid grid-cols-3 gap-6 text-slate-600 text-xs font-semibold">
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">🛡️</span>
                        <span>100% Agrée ACAPS</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">⚡</span>
                        <span>Assistance 0km 45min</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-lg">💳</span>
                        <span>Paiement Facilité</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
                    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block">Calculateur Express</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Estimation Tarif Maroc</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">💡</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Branche d'assurance</label>
                            <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 font-semibold text-slate-800">
                                <option>🚗 Automobile & Moto</option>
                                <option>🏡 Multirisque Habitation</option>
                                <option>🩺 Santé & Hospitalisation</option>
                                <option>🏢 Multirisque Professionnelle</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Ville de résidence</label>
                            <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 font-semibold text-slate-800">
                                <option>Casablanca</option>
                                <option>Rabat</option>
                                <option>Marrakech</option>
                                <option>Tanger</option>
                                <option>Agadir</option>
                            </select>
                        </div>

                        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold uppercase block">Tarif estimé à partir de</span>
                                <span class="text-2xl font-black text-blue-600 font-mono">180 DH<span class="text-xs text-slate-500 font-normal">/mois</span></span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-[10px] font-bold">Best Value</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Obtenir mon Devis Nominatif ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos Bar -->
    <section class="py-10 bg-white border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">Nos Partenaires de Confiance au Maroc</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-60 font-black text-slate-700 text-lg">
                <span>WAFA ASSURANCE</span>
                <span>AXA MAROC</span>
                <span>RMA WATANIYA</span>
                <span>SANLAM</span>
                <span>SAHAM</span>
                <span>MAMDA-MCMA</span>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 bg-slate-900 text-white">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 space-y-2">
                <span class="text-4xl font-black text-blue-400 font-mono">99.8%</span>
                <span class="text-xs font-bold text-slate-300 block uppercase tracking-wider">Satisfaction Client</span>
            </div>
            <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 space-y-2">
                <span class="text-4xl font-black text-emerald-400 font-mono">45min</span>
                <span class="text-xs font-bold text-slate-300 block uppercase tracking-wider">Assistance 0km Dépannage</span>
            </div>
            <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 space-y-2">
                <span class="text-4xl font-black text-purple-400 font-mono">24,000+</span>
                <span class="text-xs font-bold text-slate-300 block uppercase tracking-wider">Assurés Actifs</span>
            </div>
            <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 space-y-2">
                <span class="text-4xl font-black text-amber-400 font-mono">48h</span>
                <span class="text-xs font-bold text-slate-300 block uppercase tracking-wider">Remboursement Maladie</span>
            </div>
        </div>
    </section>

    <!-- Product Cards Grid -->
    <section id="solutions" class="py-24 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest block">Gammes de Protection</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight">Des Formules Taillées Pour Vos Besoins.</h2>
                <p class="text-slate-600 text-sm md:text-base font-medium">Découvrez nos solutions adaptées aux particuliers et professionnels.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">🚗</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-slate-900">Auto & Moto</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-medium">Tierce collision, vol, incendie, véhicule de remplacement & défense juridique.</p>
                    </div>
                    <ul class="text-xs space-y-2 font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Assistance 0 km 24h/24</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Bris de glace sans franchise</li>
                    </ul>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl text-xs transition">Devis Auto ➔</button>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">🏡</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-slate-900">Habitation</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-medium">Dégât des eaux, incendie, vol de mobilier et responsabilité civile famille.</p>
                    </div>
                    <ul class="text-xs space-y-2 font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Serrurier & Plombier d'urgence</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Prise en charge relogement</li>
                    </ul>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl text-xs transition">Devis Maison ➔</button>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-bold">🩺</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-slate-900">Santé Complémentaire</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-medium">Complément CNOPS/CNSS, remboursements optique, dentaire et hospitalisation.</p>
                    </div>
                    <ul class="text-xs space-y-2 font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Tiers Payant Cliniques</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Remboursement sous 48h</li>
                    </ul>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl text-xs transition">Devis Santé ➔</button>
                </div>

                <!-- Card 4 -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">🏢</div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-extrabold text-slate-900">Flottes & Pro</h3>
                        <p class="text-slate-500 text-xs leading-relaxed font-medium">Multirisque professionnelle, RC Exploitation, Accident du Travail & Flottes.</p>
                    </div>
                    <ul class="text-xs space-y-2 font-semibold text-slate-700">
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Audit de risque gratuit</li>
                        <li class="flex items-center gap-2"><span class="text-emerald-500">✓</span> Gestionnaire dédié 24/7</li>
                    </ul>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 rounded-xl text-xs transition">Devis Entreprise ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="avis" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Témoignages Vérifiés</span>
                <h2 class="text-3xl font-black text-slate-900">Ce Que Disent Nos Clients au Maroc.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex text-amber-400">★★★★★</div>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">"Prise en charge de mon accident à Casablanca en moins de 35 minutes. Remorquage et véhicule de remplacement réglés sans tracas."</p>
                    <div class="font-bold text-xs text-slate-900">— Karim B., Casablanca</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex text-amber-400">★★★★★</div>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">"Le devis santé famille m'a permis d'économiser 30% par rapport à mon ancien courtier avec de meilleures garanties."</p>
                    <div class="font-bold text-xs text-slate-900">— Sophia M., Rabat</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="flex text-amber-400">★★★★★</div>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">"Excellente gestion de notre flotte d'entreprise de 18 véhicules. Réactivité exemplaire lors de chaque déclaration de sinistre."</p>
                    <div class="font-bold text-xs text-slate-900">— Mehdi T., Directeur Général, Tanger</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive FAQ -->
    <section id="faq" class="py-24 bg-slate-50 border-t border-slate-200">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Questions Fréquentes</span>
                <h2 class="text-3xl font-black text-slate-900">Tout ce que vous devez savoir.</h2>
            </div>

            <div class="space-y-4 text-xs font-medium">
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 1 ? null : 1)" class="w-full text-left font-bold text-slate-900 text-sm flex justify-between items-center">
                        <span>Quels sont les documents requis pour souscrire une assurance Auto ?</span>
                        <span class="text-blue-600 font-bold" x-text="faqOpen === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 1" class="pt-3 text-slate-600 leading-relaxed">
                        Il vous suffit d'une copie de la Carte Grise du véhicule, du Permis de Conduire et de votre CIN nationale. La souscription s'effectue en 10 minutes en agence ou en ligne.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 2 ? null : 2)" class="w-full text-left font-bold text-slate-900 text-sm flex justify-between items-center">
                        <span>Comment s'effectue la déclaration de sinistre en cas d'accident ?</span>
                        <span class="text-blue-600 font-bold" x-text="faqOpen === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 2" class="pt-3 text-slate-600 leading-relaxed">
                        Vous pouvez déclarer votre sinistre directement via notre numéro d'assistance 24/7 ou en envoyant une photo du constat amiable. Nos experts prennent le relais immédiatement.
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 3 ? null : 3)" class="w-full text-left font-bold text-slate-900 text-sm flex justify-between items-center">
                        <span>Proposez-vous des facilités de paiement échelonné ?</span>
                        <span class="text-blue-600 font-bold" x-text="faqOpen === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 3" class="pt-3 text-slate-600 leading-relaxed">
                        Oui, nous proposons le paiement fractionné semestriel ou trimestriel ainsi que le règlement par chèque ou carte bancaire TPE.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA Section -->
    <section class="py-20 bg-blue-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Obtenez Votre Protection au Meilleur Tarif Dès Aujourd'hui.</h2>
            <p class="text-blue-100 text-sm max-w-xl mx-auto">Rejoignez des milliers d'assurés satisfaits à travers tout le Maroc.</p>
            <button @click="quoteModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Demander mon devis en 2 minutes ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 text-xs">
        <div class="max-w-[1440px] mx-auto px-6 grid md:grid-cols-4 gap-10">
            <div class="space-y-4">
                <span class="font-black text-xl text-white block">{{ $agencyName ?? 'Apple Insurance' }}</span>
                <p class="leading-relaxed">Cabinet de courtage d'assurance agréé par l'ACAPS au Maroc. Expertise, conseil et défense des assurés.</p>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Nos Offres</span>
                <ul class="space-y-2 font-medium">
                    <li>Assurance Automobile & Moto</li>
                    <li>Multirisque Habitation</li>
                    <li>Santé Complémentaire & Mutuelle</li>
                    <li>Assurance Transport & Flottes</li>
                </ul>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Informations</span>
                <ul class="space-y-2 font-medium">
                    <li>Agrément Officiel ACAPS</li>
                    <li>Guide du Sinistre Auto</li>
                    <li>Mentions Légales & Confidentialité</li>
                    <li>Réseau d'Assistance Maroc</li>
                </ul>
            </div>
            <div>
                <span class="font-bold text-white uppercase tracking-widest block mb-4">Contact Agence</span>
                <p class="leading-relaxed font-medium">
                    📍 Boulevard Mohamed V, Casablanca<br>
                    📞 +212 5 22 00 00 00<br>
                    ✉️ contact@insurio.ma
                </p>
            </div>
        </div>
        <div class="max-w-[1440px] mx-auto px-6 pt-12 border-t border-slate-800 text-center text-slate-500">
            © {{ date('Y') }} {{ $agencyName ?? 'Apple Insurance' }}. Tous droits réservés. Powered by Insurio Engine.
        </div>
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-slate-900">Demande de Devis Express</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande de devis a bien été transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Produit Souhaité</label>
                    <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                        <option>Assurance Auto</option>
                        <option>Assurance Habitation</option>
                        <option>Santé Complémentaire</option>
                        <option>Multirisque Professionnelle</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition shadow-md">
                    Envoyer ma Demande ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
