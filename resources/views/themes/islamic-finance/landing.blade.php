<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Takaful Islamic Insurance' }} | Assurance Takaful Conforme à la Charia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#022c22] text-slate-100 selection:bg-amber-500 selection:text-black">

    <!-- Sharia Top Bar -->
    <div class="bg-[#011c16] border-b border-emerald-900/50 text-amber-300 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <span>🕌 Assurance Takaful Solidaire • Validée Par Le Comité Sharia Officiel</span>
            <span>📞 Assistance Takaful 24/7: +212 5 22 77 66 55</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#022c22]/90 backdrop-blur-md border-b border-emerald-900/60 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-amber-500 text-black flex items-center justify-center font-black text-xl shadow-lg shadow-emerald-500/20">
                    ☪
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Takaful Insurance' }}</span>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block -mt-1">Assurance Takaful Ethique</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-300">
                <a href="#takaful" class="hover:text-amber-400 transition">Produits Takaful</a>
                <a href="#stats" class="hover:text-amber-400 transition">Partage des Bénéfices</a>
                <a href="#avis" class="hover:text-amber-400 transition">Avis Membres</a>
                <a href="#faq" class="hover:text-amber-400 transition">FAQ Sharia</a>
            </nav>

            <button @click="quoteModal = true" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-black px-6 py-2.5 rounded-full font-black text-xs shadow-lg shadow-amber-500/20 transition">
                Devis Takaful ➔
            </button>
        </div>
    </header>

    <!-- 90vh Sharia Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#022c22] via-[#044434] to-[#022c22] border-b border-emerald-900/50 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-700/60 text-xs font-bold text-amber-300">
                    <span>✨ 100% Conforme aux Principes de la Finance Islamique</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Protection Solidaire. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-emerald-300">Sans Riba Ni Gharar.</span>
                </h1>

                <p class="text-emerald-100/80 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Solution d'Assurance Takaful mutuelle et équitable. Les excédents du fonds d'assurance sont redistribués aux participants conformément à la Charia.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-400 hover:bg-amber-300 text-black font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-400/20 text-center">
                        Calculer Mon Devis Takaful ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-[#033a2d] rounded-3xl p-8 border border-emerald-700/60 shadow-2xl space-y-6">
                    <div class="border-b border-emerald-800 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400 block">Simulation Takaful</span>
                            <h3 class="font-extrabold text-xl text-white">Souscription Éthique</h3>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-emerald-900 text-amber-400 flex items-center justify-center font-bold text-lg">🕌</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-[#022c22] border border-emerald-800 space-y-2">
                            <span class="text-emerald-300 text-[10px] uppercase font-bold block">Partage des Bénéfices Excédentaires</span>
                            <span class="text-2xl font-black text-amber-400 font-mono">100% Redistribué</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-amber-400 hover:bg-amber-300 text-black font-black py-3.5 rounded-xl transition text-xs shadow-lg">
                            Rejoindre le Fonds Takaful ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#011c16]">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-[#022c22] border border-emerald-900/60">
                <span class="text-4xl font-black text-amber-400 font-mono">0 Riba</span>
                <span class="text-xs text-emerald-200 block mt-2">100% Halal & Éthique</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#022c22] border border-emerald-900/60">
                <span class="text-4xl font-black text-emerald-400 font-mono">100%</span>
                <span class="text-xs text-emerald-200 block mt-2">Approuvé Comité Sharia</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#022c22] border border-emerald-900/60">
                <span class="text-4xl font-black text-cyan-400 font-mono">20,000+</span>
                <span class="text-xs text-emerald-200 block mt-2">Membres Participants</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#022c22] border border-emerald-900/60">
                <span class="text-4xl font-black text-purple-300 font-mono">24/7</span>
                <span class="text-xs text-emerald-200 block mt-2">Assistance Solidaire</span>
            </div>
        </div>
    </section>

    <!-- Takaful Solutions -->
    <section id="takaful" class="py-24 bg-[#022c22]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">Gamme Takaful</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Des Garanties Éthiques & Adaptées.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#033a2d] p-8 rounded-3xl border border-emerald-800 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-white">Takaful Automobile</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Couverture véhicules selon les principes de solidarité et partage de risque Takaful.</p>
                    <button @click="quoteModal = true" class="w-full bg-amber-400 text-black font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-[#033a2d] p-8 rounded-3xl border border-emerald-800 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-white">Takaful Habitation</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Protection de la résidence principale contre tout sinistre conforme à la Charia.</p>
                    <button @click="quoteModal = true" class="w-full bg-amber-400 text-black font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-[#033a2d] p-8 rounded-3xl border border-emerald-800 space-y-6">
                    <span class="text-3xl">👨‍👩‍👧</span>
                    <h3 class="text-xl font-bold text-white">Takaful Famille & Santé</h3>
                    <p class="text-slate-300 text-xs leading-relaxed">Prévoyance et prise en charge des frais de santé de la famille sans placement usuraire.</p>
                    <button @click="quoteModal = true" class="w-full bg-amber-400 text-black font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-amber-500 to-amber-600 text-black text-center font-bold">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Rejoignez La Première Communauté d'Assurance Takaful.</h2>
            <button @click="quoteModal = true" class="bg-[#022c22] hover:bg-black text-white font-black px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Demander Mon Devis Takaful ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#011c16] text-slate-500 py-16 text-xs text-center border-t border-emerald-900/50">
        © {{ date('Y') }} {{ $agencyName ?? 'Takaful Insurance' }}. Agrée par l'ACAPS & Conseil Supérieur des Oulémas.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#033a2d] rounded-3xl max-w-md w-full p-8 space-y-4 border border-amber-500/40 text-white">
            <div class="flex justify-between items-center border-b border-emerald-800 pb-3">
                <h3 class="font-black text-lg text-amber-400">Devis Takaful</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Takaful transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block mb-1 text-slate-200">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-emerald-800 rounded-xl px-4 py-3 bg-[#022c22] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-200">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-emerald-800 rounded-xl px-4 py-3 bg-[#022c22] text-white">
                </div>
                <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-black font-black py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
