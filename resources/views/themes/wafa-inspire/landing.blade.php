<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Wafa Inspire Agency' }} | Agence Agréée Wafa Assurance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 selection:bg-rose-900 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-rose-950 text-rose-100 text-xs py-2.5 px-6 border-b border-rose-900">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold">
            <span>🔴 Partner Officiel Wafa Assurance • N°1 de l'Assurance au Maroc</span>
            <span>📞 Assistance 24/7 Agence: +212 5 22 10 20 30</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-900 text-amber-400 flex items-center justify-center font-black text-xl shadow-md shadow-rose-900/20">
                    W
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Wafa Inspire Agency' }}</span>
                    <span class="text-[10px] font-bold text-rose-900 uppercase tracking-widest block -mt-1">Agence Agréée Wafa Assurance</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-rose-900 transition">Garanties Wafa</a>
                <a href="#stats" class="hover:text-rose-900 transition">Leadership</a>
                <a href="#faq" class="hover:text-rose-900 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-rose-900 hover:bg-rose-800 text-amber-400 px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Devis Wafa ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-br from-rose-950 via-rose-900 to-slate-900 text-white border-b border-rose-900">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-900/80 border border-rose-700/60 text-xs font-bold text-amber-300">
                    <span>👑 Le N°1 de l'Assurance Au Maroc À Votre Service</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight">
                    Votre Tranquillité. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-200">Garantie Par Le Leader National.</span>
                </h1>

                <p class="text-rose-100 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Protection intégrale Auto, Habitation, Santé et Retraite proposée par l'agence partenaire officielle Wafa Assurance.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-400 hover:bg-amber-300 text-rose-950 font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-400/20 text-center">
                        Consulter Tarif Wafa ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white text-slate-900 rounded-3xl p-8 border border-slate-200 shadow-2xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-rose-900 block">Simulateur Officiel</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Tarification Wafa</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-900 flex items-center justify-center font-bold text-lg">🛡️</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">Assistance Wafa SOS</span>
                                <span class="text-xl font-black text-rose-900 font-mono">Dépannage 0 km</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-rose-900 text-amber-400 text-[10px] font-bold">Wafa</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-rose-900 hover:bg-rose-800 text-amber-400 font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Obtenir Mon Devis Wafa ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-rose-950 text-white text-center">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-rose-900/40 border border-rose-800">
                <span class="text-4xl font-black text-amber-400 font-mono">N°1</span>
                <span class="text-xs text-rose-200 block mt-2">Part de Marché Maroc</span>
            </div>
            <div class="p-8 rounded-3xl bg-rose-900/40 border border-rose-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">45min</span>
                <span class="text-xs text-rose-200 block mt-2">Assistance Dépannage</span>
            </div>
            <div class="p-8 rounded-3xl bg-rose-900/40 border border-rose-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">2M+</span>
                <span class="text-xs text-rose-200 block mt-2">Assurés au Maroc</span>
            </div>
            <div class="p-8 rounded-3xl bg-rose-900/40 border border-rose-800">
                <span class="text-4xl font-black text-purple-300 font-mono">100%</span>
                <span class="text-xs text-rose-200 block mt-2">Agréé ACAPS</span>
            </div>
        </div>
    </section>

    <!-- Cards -->
    <section id="offres" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-rose-900 uppercase tracking-widest block">Solutions Wafa</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Vos Garanties d'Excellence.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-slate-900">Auto Wafa</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Assurance automobile tous risques avec véhicule de remplacement et assistance 0 km.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-slate-900">Habitation Wafa</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Multirisque habitation complète prémunissant contre incendie, vol et degâts des eaux.</p>
                </div>
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-200 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-slate-900">Santé Wafa</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Remboursement médical rapide et Tiers Payant clinique dans tout le Maroc.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-rose-900 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Souscrivez Votre Assurance Wafa En Toute Confiance.</h2>
            <button @click="quoteModal = true" class="bg-amber-400 hover:bg-amber-300 text-rose-950 font-black px-10 py-4 rounded-full text-xs shadow-xl transition">
                Demander Mon Devis Wafa ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-rose-950 text-rose-300 py-16 text-xs text-center border-t border-rose-900">
        © {{ date('Y') }} {{ $agencyName ?? 'Wafa Inspire Agency' }}. Agence Agréée Wafa Assurance Maroc.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-rose-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-rose-950">Devis Wafa Assurance</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Wafa transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-rose-900 text-amber-400 font-bold py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
