<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'RMA Inspire Agency' }} | Agence Agréée RMA Wataniya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 selection:bg-blue-900 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-blue-950 text-blue-100 text-xs py-2.5 px-6 border-b border-blue-900">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold">
            <span>🔵 Partner Officiel RMA Wataniya • Assurances Particuliers & Entreprises</span>
            <span>📞 Assistance Agence: +212 5 22 33 44 55</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-900 text-amber-400 flex items-center justify-center font-black text-xl shadow-md shadow-blue-900/20">
                    RMA
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'RMA Inspire Agency' }}</span>
                    <span class="text-[10px] font-bold text-blue-900 uppercase tracking-widest block -mt-1">Agence Agréée d'Assurances</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-blue-900 transition">Garanties RMA</a>
                <a href="#stats" class="hover:text-blue-900 transition">Confiance</a>
                <a href="#faq" class="hover:text-blue-900 transition">FAQ RMA</a>
            </nav>

            <button @click="quoteModal = true" class="bg-blue-900 hover:bg-blue-800 text-amber-400 px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Simuler Tarif RMA ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 text-white border-b border-blue-900">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-900/80 border border-blue-700/60 text-xs font-bold text-amber-300">
                    <span>🔵 Votre Sécurité Entre De Bonnes Mains</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight">
                    Votre Avenir. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-200">Protégé Par L'Excellence RMA.</span>
                </h1>

                <p class="text-blue-100 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Protection automobile avec assistance 0 km, multirisque habitation complète et couverture maladie groupe pour vous et votre entreprise.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-400 hover:bg-amber-300 text-blue-950 font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-400/20 text-center">
                        Demander Devis RMA ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white text-slate-900 rounded-3xl p-8 border border-slate-200 shadow-2xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-900 block">Simulateur Agence</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Tarif Officiel RMA</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center font-bold text-lg">🛡️</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">Assistance 0 km RMA</span>
                                <span class="text-xl font-black text-blue-900 font-mono">24H/24 & 7J/7</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-amber-400 text-blue-950 text-[10px] font-bold">RMA Wataniya</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-blue-900 hover:bg-blue-800 text-amber-400 font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Calculer Tarif ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-blue-950 text-white text-center">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-amber-400 font-mono">N°1</span>
                <span class="text-xs text-blue-200 block mt-2">Qualité de Service Sinistre</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">45min</span>
                <span class="text-xs text-blue-200 block mt-2">Intervention Dépannage</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">100k+</span>
                <span class="text-xs text-blue-200 block mt-2">Assurés Agence</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-purple-300 font-mono">100%</span>
                <span class="text-xs text-blue-200 block mt-2">Agréé ACAPS</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="offres" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-blue-900 uppercase tracking-widest block">Solutions RMA</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Nos Formules d'Assurance.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-slate-900">Auto RMA</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Formules Tiers, Tierce Collision et Tous Risques avec franchise minimale.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-slate-900">Habitation RMA</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Protection incendie, vol et dégât des eaux pour appartements et villas.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-slate-900">Santé RMA</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Prise en charge médicale et Tiers Payant clinique dans tout le Maroc.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-blue-900 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Souscrivez Votre Assurance RMA En Agence ou En Ligne.</h2>
            <button @click="quoteModal = true" class="bg-amber-400 hover:bg-amber-300 text-blue-950 font-black px-10 py-4 rounded-full text-xs shadow-xl transition">
                Obtenir Mon Devis ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-950 text-blue-300 py-16 text-xs text-center border-t border-blue-900">
        © {{ date('Y') }} {{ $agencyName ?? 'RMA Inspire Agency' }}. Agence Agréée RMA Wataniya.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-blue-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-blue-950">Devis RMA</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande RMA transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-blue-900 text-amber-400 font-bold py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
