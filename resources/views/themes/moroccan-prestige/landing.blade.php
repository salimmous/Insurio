<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Assurance Prestige Maroc' }} | L'Excellence du Courtage National</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#0f172a] text-slate-100 selection:bg-amber-600 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-[#090d16] border-b border-amber-600/30 text-amber-400 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold">
            <span>🇲🇦 L'Assurance Marocaine d'Excellence au Service du Royaume</span>
            <span>📞 Assistance Agence 24h/7: +212 5 22 00 11 22</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#0f172a]/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-500 via-emerald-600 to-blue-900 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-amber-500/20">
                    🇲🇦
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Assurance Prestige Maroc' }}</span>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block -mt-1">Courtage & Conseil Agréé ACAPS</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-300">
                <a href="#offres" class="hover:text-amber-400 transition">Nos Offres</a>
                <a href="#stats" class="hover:text-amber-400 transition">Chiffres</a>
                <a href="#avis" class="hover:text-amber-400 transition">Avis</a>
                <a href="#faq" class="hover:text-amber-400 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-amber-500 hover:bg-amber-400 text-slate-950 px-6 py-2.5 rounded-full font-black text-xs shadow-lg transition">
                Devis Express ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#0f172a] via-[#1e293b] to-[#0f172a] border-b border-slate-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-950/80 border border-amber-500/40 text-xs font-bold text-amber-300">
                    <span>🇲🇦 Cabinet de Courtage N°1 en Proximité et Confiance</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Votre Patrimoine. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-emerald-400 to-blue-400">Protégé Par Des Experts Du Royaume.</span>
                </h1>

                <p class="text-slate-300 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Une expertise marocaine éprouvée pour la couverture de vos véhicules, habitations, santé et risques professionnels sur tout le territoire national.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-500/20 text-center">
                        Calculer Mon Devis Maroc ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-[#1e293b] rounded-3xl p-8 border border-amber-500/30 shadow-2xl space-y-6">
                    <div class="border-b border-slate-700 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400 block">Simulateur National</span>
                            <h3 class="font-extrabold text-xl text-white">Tarification Maroc</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-amber-950 text-amber-400 flex items-center justify-center font-bold text-lg">🏛️</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-[#0f172a] border border-slate-800 space-y-2">
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Assistance Dépannage 0 km</span>
                            <span class="text-2xl font-black text-amber-400 font-mono">Sous 45 Minutes</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-black py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Obtenir Mon Devis ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#090d16]">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-[#0f172a] border border-slate-800">
                <span class="text-4xl font-black text-amber-400 font-mono">30 Villes</span>
                <span class="text-xs text-slate-400 block mt-2">Couvertes au Maroc</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0f172a] border border-slate-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">100%</span>
                <span class="text-xs text-slate-400 block mt-2">Conforme ACAPS</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0f172a] border border-slate-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">40,000+</span>
                <span class="text-xs text-slate-400 block mt-2">Assurés Satisfaits</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0f172a] border border-slate-800">
                <span class="text-4xl font-black text-purple-400 font-mono">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Assistance Urgente</span>
            </div>
        </div>
    </section>

    <!-- Cards -->
    <section id="offres" class="py-24 bg-[#0f172a]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">Garanties du Royaume</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Nos Formules d'Assurance.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#1e293b] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-white">Auto Maroc</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Formules adaptées aux routes marocaines avec remorquage 0 km et constat amiable assisté.</p>
                </div>
                <div class="bg-[#1e293b] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-white">Habitation & Villa</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Multirisque maison prémunissant contre les incendies, vol et dégâts des eaux.</p>
                </div>
                <div class="bg-[#1e293b] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-white">Santé & Mutuelle</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Prise en charge directe dans le réseau national des cliniques et laboratoires agréés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-amber-500 text-slate-950 text-center font-bold">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Faites Confiance Au Courtier National d'Excellence.</h2>
            <button @click="quoteModal = true" class="bg-[#0f172a] text-white font-black px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Obtenir Mon Devis En 2 Min ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#090d16] text-slate-500 py-16 text-xs text-center border-t border-slate-800">
        © {{ date('Y') }} {{ $agencyName ?? 'Assurance Prestige Maroc' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#1e293b] rounded-3xl max-w-md w-full p-8 space-y-4 border border-amber-500/30 text-white">
            <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                <h3 class="font-black text-lg text-amber-400">Devis Prestige Maroc</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande reçue !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-700 rounded-xl px-4 py-3 bg-[#0f172a] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-700 rounded-xl px-4 py-3 bg-[#0f172a] text-white">
                </div>
                <button type="submit" class="w-full bg-amber-500 text-slate-950 font-black py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
