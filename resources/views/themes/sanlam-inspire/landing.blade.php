<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Sanlam Inspire Agency' }} | Agence Agréée Sanlam Maroc</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 selection:bg-sky-600 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-sky-950 text-sky-100 text-xs py-2.5 px-6 border-b border-sky-900">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold">
            <span>🌐 Partner Officiel Sanlam Maroc • Leader Panafricain de l'Assurance</span>
            <span>📞 Ligne Assistance: +212 5 22 55 66 77</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-sky-600/20">
                    S
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Sanlam Inspire Agency' }}</span>
                    <span class="text-[10px] font-bold text-sky-600 uppercase tracking-widest block -mt-1">Agence Agréée Sanlam</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-sky-600 transition">Garanties Sanlam</a>
                <a href="#stats" class="hover:text-sky-600 transition">Indicateurs</a>
                <a href="#faq" class="hover:text-sky-600 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-sky-600 hover:bg-sky-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Devis Sanlam ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-br from-sky-950 via-sky-900 to-slate-900 text-white border-b border-sky-900">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-900/80 border border-sky-700/60 text-xs font-bold text-sky-300">
                    <span>🌐 Bien Vivre et Réussir Votre Avenir Avec Sanlam</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight">
                    Planifiez & Protégez <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 via-cyan-300 to-teal-300">Votre Avenir En Toute Confiance.</span>
                </h1>

                <p class="text-sky-100 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Une gamme complète d'assurances Auto, Habitation, Santé et Retraite proposée par l'agence partenaire Sanlam Maroc.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-sky-400 hover:bg-sky-300 text-slate-950 font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-sky-400/20 text-center">
                        Consulter Tarif Sanlam ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white text-slate-900 rounded-3xl p-8 border border-slate-200 shadow-2xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-sky-600 block">Simulateur Sanlam</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Tarification Agence</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg">🌐</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">Assistance 24/7 Sanlam</span>
                                <span class="text-xl font-black text-sky-600 font-mono">Dépannage 0 km</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-sky-600 text-white text-[10px] font-bold">Sanlam</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Calculer Tarif ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-sky-950 text-white text-center">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-sky-900/40 border border-sky-800">
                <span class="text-4xl font-black text-sky-300 font-mono">100+</span>
                <span class="text-xs text-sky-200 block mt-2">Ans d'Histoire Groupe</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-900/40 border border-sky-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">45min</span>
                <span class="text-xs text-sky-200 block mt-2">Dépannage Urgence</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-900/40 border border-sky-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">100%</span>
                <span class="text-xs text-sky-200 block mt-2">Conformité ACAPS</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-900/40 border border-sky-800">
                <span class="text-4xl font-black text-purple-300 font-mono">24h/7</span>
                <span class="text-xs text-sky-200 block mt-2">Support Sinistre</span>
            </div>
        </div>
    </section>

    <!-- Cards -->
    <section id="offres" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-widest block">Solutions Sanlam</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Nos Protections Globales.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-slate-900">Auto Sanlam</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Formules adaptées avec bris de glace sans franchise et véhicule de remplacement.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-slate-900">Habitation Sanlam</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Multirisque maison incluant serrurier et plombier d'urgence 24/7.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-slate-900">Santé Sanlam</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Prise en charge médicale et Tiers Payant direct dans les cliniques agréées.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-sky-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Assurez Votre Avenir Avec Sanlam Maroc.</h2>
            <button @click="quoteModal = true" class="bg-slate-900 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Obtenir Mon Devis ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 text-xs text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Sanlam Inspire Agency' }}. Agence Agréée Sanlam Maroc.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-slate-900">Devis Sanlam</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Sanlam transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-sky-600 text-white font-bold py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
