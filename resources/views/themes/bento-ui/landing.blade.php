<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Bento Insurance' }} | Protection Modulaire Bento Grid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 selection:bg-cyan-500 selection:text-black">

    <!-- Top Bar -->
    <div class="bg-slate-950 border-b border-slate-800 text-slate-400 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <span>🍱 Plateforme Modulaire d'Assurance • Maroc</span>
            <span class="inline-flex items-center gap-1.5 text-cyan-400 text-[10px] font-bold">
                ● Assistance 24/7 en Direct +212 5 22 00 00 00
            </span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-600 text-black flex items-center justify-center font-black text-xl shadow-lg shadow-cyan-500/20">
                    ⊞
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Bento Insurance' }}</span>
                    <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest block -mt-1">Bento Grid Engine</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-400">
                <a href="#bento" class="hover:text-white transition">Grille Bento</a>
                <a href="#stats" class="hover:text-white transition">Chiffres Clés</a>
                <a href="#avis" class="hover:text-white transition">Avis Assurés</a>
                <a href="#faq" class="hover:text-white transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-gradient-to-r from-cyan-400 to-indigo-500 text-slate-950 px-6 py-2.5 rounded-full font-black text-xs shadow-lg shadow-cyan-500/20 hover:opacity-90 transition">
                Devis Modulaire ➔
            </button>
        </div>
    </header>

    <!-- 90vh Bento Hero Section -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 border-b border-slate-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-950/80 border border-cyan-800/60 text-xs font-bold text-cyan-400">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    Architecture d'Assurance 100% Modulaire
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Votre Sécurité. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-indigo-400">Assemblé En Modules Parfaits.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Configurez vos couvertures Auto, Habitation et Santé comme un jeu de bloc Bento. Tarification transparente et souscription instantanée.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-cyan-400/20 text-center">
                        Composer Mon Module ➔
                    </button>
                    <a href="#bento" class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-8 py-4 rounded-full text-xs transition text-center">
                        Voir la Grille Bento
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-slate-800 grid grid-cols-3 gap-6 text-slate-400 text-xs font-semibold">
                    <div class="flex items-center gap-2">
                        <span class="text-cyan-400 text-lg">⚡</span>
                        <span>Devis sous 2 minutes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 text-lg">🛡️</span>
                        <span>Agréé ACAPS Maroc</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-400 text-lg">📱</span>
                        <span>100% Digital & WhatsApp</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Bento Preview -->
            <div class="lg:col-span-5">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-800/90 p-6 rounded-3xl border border-slate-700 space-y-3">
                        <span class="text-2xl">🚗</span>
                        <h4 class="font-extrabold text-white text-sm">Module Auto</h4>
                        <span class="text-[11px] text-cyan-400 font-mono font-bold block">à partir de 180 DH/m</span>
                    </div>
                    <div class="bg-slate-800/90 p-6 rounded-3xl border border-slate-700 space-y-3">
                        <span class="text-2xl">🏡</span>
                        <h4 class="font-extrabold text-white text-sm">Module Maison</h4>
                        <span class="text-[11px] text-teal-400 font-mono font-bold block">à partir de 45 DH/m</span>
                    </div>
                    <div class="col-span-2 bg-gradient-to-br from-indigo-900/60 to-slate-800 p-6 rounded-3xl border border-indigo-700/50 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-300">Score de Couverture</span>
                            <span class="text-xs font-mono font-bold text-emerald-400">98/100 Excellent</span>
                        </div>
                        <div class="w-full bg-slate-900 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-cyan-400 to-emerald-400 h-full w-[98%]"></div>
                        </div>
                        <button @click="quoteModal = true" class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-extrabold text-xs py-2.5 rounded-xl transition mt-2">
                            Optimiser Ma Couverture ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Logos -->
    <section class="py-10 bg-slate-950 border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-500 mb-6">Assureurs Partenaires Intégrés</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-16 opacity-60 font-black text-slate-400 text-lg">
                <span>WAFA ASSURANCE</span>
                <span>AXA MAROC</span>
                <span>RMA</span>
                <span>SANLAM</span>
                <span>SAHAM</span>
            </div>
        </div>
    </section>

    <!-- Bento Grid Section -->
    <section id="bento" class="py-24 bg-slate-900">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest block">Grille Modulaire</span>
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight">Composez Vos Protections Sur-Mesure.</h2>
            </div>

            <!-- Bento Asymmetric Grid -->
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Large Bento 1 -->
                <div class="md:col-span-2 bg-slate-800/80 p-8 rounded-3xl border border-slate-700 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <span class="px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-bold border border-cyan-500/20">Module Phare</span>
                        <h3 class="text-2xl font-black text-white">Assurance Automobile Intelligente</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Assistance dépannage 0 km sur toutes les autoroutes et villes du Maroc en moins de 45 minutes. Véhicule de remplacement équivalent garanti.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-fit bg-cyan-400 text-slate-950 font-black px-6 py-3 rounded-xl text-xs">Simuler Tarif Auto ➔</button>
                </div>

                <!-- Bento 2 -->
                <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-black text-white">Santé & Mutuelle</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Tiers payant dans plus de 500 cliniques agréées au Maroc. Remboursement dossier sous 48h.</p>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 text-cyan-400 font-bold py-2.5 rounded-xl text-xs border border-slate-700">Devis Santé ➔</button>
                </div>

                <!-- Bento 3 -->
                <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-black text-white">Habitation & Vol</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Protection contre dégâts des eaux, incendies et vol avec intervention serrurier/plombier 24/7.</p>
                    <button @click="quoteModal = true" class="w-full bg-slate-900 text-cyan-400 font-bold py-2.5 rounded-xl text-xs border border-slate-700">Devis Maison ➔</button>
                </div>

                <!-- Large Bento 4 -->
                <div class="md:col-span-2 bg-gradient-to-r from-indigo-950 to-slate-800 p-8 rounded-3xl border border-indigo-800/50 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold border border-indigo-500/30">Entreprises & Flottes</span>
                        <h3 class="text-2xl font-black text-white">Multirisque Pro & RC Dirigeants</h3>
                        <p class="text-slate-400 text-xs leading-relaxed">Solution complète pour la protection des locaux commerciaux, véhicules de société et collaborateurs.</p>
                    </div>
                    <button @click="quoteModal = true" class="w-fit bg-indigo-500 text-white font-bold px-6 py-3 rounded-xl text-xs">Audit Pro Gratuit ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Bento Stats -->
    <section id="stats" class="py-20 bg-slate-950">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">99.7%</span>
                <span class="text-xs font-bold text-slate-400 block mt-2">Dossiers Sinistres Validés</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">&lt; 2 min</span>
                <span class="text-xs font-bold text-slate-400 block mt-2">Temps Moyen Devis</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-indigo-400 font-mono">35,000+</span>
                <span class="text-xs font-bold text-slate-400 block mt-2">Modules Activés</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-amber-400 font-mono">24/7</span>
                <span class="text-xs font-bold text-slate-400 block mt-2">Support WhatsApp Live</span>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="avis" class="py-24 bg-slate-900">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">Avis Assurés</span>
                <h2 class="text-3xl font-black text-white">Ce Que Disent Nos Utilisateurs.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 text-xs">
                <div class="p-8 rounded-3xl bg-slate-800/60 border border-slate-700 space-y-4">
                    <p class="text-slate-300 leading-relaxed">"Le concept modulaire est genial. J'ai pu ajouter l'assistance 0 km et le bris de glace à mon contrat auto sans surcoût inutile."</p>
                    <div class="font-bold text-white">— Youssef K., Casablanca</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-800/60 border border-slate-700 space-y-4">
                    <p class="text-slate-300 leading-relaxed">"Souscription via WhatsApp en moins de 10 minutes. Carte verte reçue le jour même."</p>
                    <div class="font-bold text-white">— Meriem R., Marrakech</div>
                </div>
                <div class="p-8 rounded-3xl bg-slate-800/60 border border-slate-700 space-y-4">
                    <p class="text-slate-300 leading-relaxed">"Parfait pour ma startup à Rabat. Assurance RC Pro et matériel souscrits sans tracas administratif."</p>
                    <div class="font-bold text-white">— Tariq A., Fondateur Tech</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bento FAQ -->
    <section id="faq" class="py-24 bg-slate-950 border-t border-slate-800">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">FAQ Modulaire</span>
                <h2 class="text-3xl font-black text-white">Questions Fréquentes.</h2>
            </div>

            <div class="space-y-4 text-xs font-medium">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6">
                    <button @click="faqOpen = (faqOpen === 1 ? null : 1)" class="w-full text-left font-bold text-white text-sm flex justify-between items-center">
                        <span>Puis-je modifier mes modules en cours de contrat ?</span>
                        <span class="text-cyan-400 font-bold" x-text="faqOpen === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="faqOpen === 1" class="pt-3 text-slate-400 leading-relaxed">
                        Oui, vous pouvez ajuster vos options (assistance renforcée, bris de glace, garanties conducteurs) à tout moment.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-cyan-500 to-indigo-600 text-slate-950 text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Construisez Votre Assurance Sur-Mesure Dès Maintenant.</h2>
            <button @click="quoteModal = true" class="bg-slate-950 hover:bg-slate-900 text-white font-black px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Lancer la Simulation Bento ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 py-16 text-xs border-t border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 text-center text-slate-600">
            © {{ date('Y') }} {{ $agencyName ?? 'Bento Insurance' }}. Tous droits réservés.
        </div>
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl border border-slate-800 text-white">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-black text-lg text-white">Devis Modulaire Instantané</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Bento transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <div>
                    <label class="block font-bold text-slate-300 mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-black py-3.5 rounded-xl transition shadow-lg">
                    Valider Mon Devis ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
