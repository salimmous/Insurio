<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Insurance Marketplace' }} | Comparateur 12 Assureurs au Maroc</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-blue-600 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-blue-900 text-blue-100 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold">
            <span>⚡ Comparez Instantanément 12 Compagnies d'Assurance au Maroc</span>
            <span>🏷️ Garantie Meilleur Prix d'Agence</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-blue-600/20">
                    🛍️
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Insurance Marketplace' }}</span>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block -mt-1">Comparateur Agréé Maroc</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-blue-600 transition">Offres</a>
                <a href="#comparateur" class="hover:text-blue-600 transition">Comparateur</a>
                <a href="#stats" class="hover:text-blue-600 transition">Chiffres</a>
                <a href="#faq" class="hover:text-blue-600 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Lancer le Comparateur ➔
            </button>
        </div>
    </header>

    <!-- 90vh Marketplace Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-b from-white via-blue-50/60 to-slate-100 border-b border-slate-200">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100 border border-blue-200 text-xs font-bold text-blue-800">
                    <span>⚡ Économisez jusqu'à 30% sur votre Assurance Auto & Santé</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 leading-[1.05] tracking-tight">
                    Comparez. Économisez. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-600">Souscrivez En 3 Clics.</span>
                </h1>

                <p class="text-slate-600 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Premier comparateur d'assurance agréé au Maroc. Obtenez les cotations directes de Wafa, AXA, RMA, Sanlam et Saham au meilleur prix garanti.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-blue-600/20 text-center">
                        Lancer la Comparaison Gratuitement ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Comparator Engine Card -->
            <div class="lg:col-span-5">
                <div id="comparateur" class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 block">Comparateur Multi-Offres</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Moteur de Cotation Live</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">⚡</span>
                    </div>

                    <div class="space-y-3 text-xs font-medium">
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="font-black text-blue-900">WAFA</span>
                                <div>
                                    <span class="font-bold text-slate-900 block">Pack Auto Sérénité</span>
                                    <span class="text-[10px] text-slate-500">Assistance 0km incluse</span>
                                </div>
                            </div>
                            <span class="font-black text-emerald-600 font-mono text-base">180 DH/m</span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="font-black text-indigo-900">AXA</span>
                                <div>
                                    <span class="font-bold text-slate-900 block">Formule Tous Risques</span>
                                    <span class="text-[10px] text-slate-500">Véhicule remplacement</span>
                                </div>
                            </div>
                            <span class="font-black text-emerald-600 font-mono text-base">210 DH/m</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Voir Toutes les Offres ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-blue-950 text-white">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">12</span>
                <span class="text-xs font-bold text-blue-200 block mt-2">Compagnies Comparées</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">30%</span>
                <span class="text-xs font-bold text-blue-200 block mt-2">Économie Moyenne</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-amber-400 font-mono">50,000+</span>
                <span class="text-xs font-bold text-blue-200 block mt-2">Devis Générés</span>
            </div>
            <div class="p-8 rounded-3xl bg-blue-900/40 border border-blue-800">
                <span class="text-4xl font-black text-purple-300 font-mono">100%</span>
                <span class="text-xs font-bold text-blue-200 block mt-2">Gratuit & Sans Engagement</span>
            </div>
        </div>
    </section>

    <!-- Cards -->
    <section id="offres" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest block">Marketplace Assurance</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Toutes Les Assurances En Un Seul Endroit.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-slate-900">Assurance Auto</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Comparez Tiers, Vol, Incendie et Tous Risques avec options de franchise ajustables.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-xs">Comparez ➔</button>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-slate-900">Mutuelle Santé</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Comparez les complémentaires santé avec Tiers Payant direct en clinique.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-xs">Comparez ➔</button>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-slate-900">Habitation</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Comparez les contrats MSH pour appartements et villas au meilleur prix.</p>
                    <button @click="quoteModal = true" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl text-xs">Comparez ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-blue-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Trouvez Votre Assurance Au Meilleur Prix En 3 Clics.</h2>
            <button @click="quoteModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Démarrer La Comparaison ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 text-xs text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Insurance Marketplace' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-slate-900">Comparateur Instantané</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Comparaison lancée !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom Complexe *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl transition">
                    Comparer Les Tarifs ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
