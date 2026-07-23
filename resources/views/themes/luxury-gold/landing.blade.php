<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Luxury Gold Insurance' }} | Cabinet Prestige & Conseil Privé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; } .gold-title { font-family: 'Cinzel', serif; }</style>
</head>
<body class="bg-[#09090b] text-slate-100 selection:bg-amber-500 selection:text-black">

    <!-- Gold Top Bar -->
    <div class="bg-[#040405] border-b border-amber-500/20 text-amber-400 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-bold tracking-wider">
            <span>✨ Cabinet Prestige Agréé ACAPS • Casablanca • Rabat</span>
            <span>👑 Conciergerie VIP 24/7: +212 5 22 99 00 11</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#09090b]/90 backdrop-blur-md border-b border-amber-500/20 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-amber-700 text-black flex items-center justify-center font-black text-xl shadow-lg shadow-amber-500/20">
                    👑
                </div>
                <div>
                    <span class="font-bold text-lg text-white tracking-widest uppercase gold-title block">{{ $agencyName ?? 'Luxury Gold Insurance' }}</span>
                    <span class="text-[9px] font-bold text-amber-400 uppercase tracking-widest block -mt-1">Prestige Wealth Protection</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-300">
                <a href="#prestige" class="hover:text-amber-400 transition">Offres Prestige</a>
                <a href="#stats" class="hover:text-amber-400 transition">Excellence</a>
                <a href="#avis" class="hover:text-amber-400 transition">Avis VIP</a>
                <a href="#faq" class="hover:text-amber-400 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 text-black px-6 py-2.5 rounded-full font-black text-xs shadow-lg shadow-amber-500/20 hover:opacity-90 transition">
                Devis Prestige ➔
            </button>
        </div>
    </header>

    <!-- 90vh Gold Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#09090b] via-[#14120b] to-[#09090b] border-b border-amber-500/20 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-950/80 border border-amber-500/40 text-xs font-bold text-amber-300">
                    <span>👑 L'Excellence Absolue de l'Assurance Privée</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight gold-title">
                    Prestige & Sérénité. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-500">Protection Sur-Mesure.</span>
                </h1>

                <p class="text-slate-300 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Protection haut de gamme pour véhicules de luxe, villas d'architectes, embarcations et responsabilités d'entreprise d'envergure.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-gradient-to-r from-amber-400 to-amber-600 text-black font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-500/20 text-center">
                        Consulter un Expert Privé ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-[#14120b] rounded-3xl p-8 border border-amber-500/30 shadow-2xl space-y-6">
                    <div class="border-b border-amber-500/20 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400 block">Cabinet Gold</span>
                            <h3 class="font-extrabold text-xl text-white gold-title">Simulateur VIP</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-amber-950 text-amber-400 flex items-center justify-center font-bold text-lg">💎</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-[#09090b] border border-amber-500/20 space-y-2">
                            <span class="text-slate-400 text-[10px] font-bold uppercase block">Plafond Garantie De Luxe</span>
                            <span class="text-2xl font-black text-amber-400 font-mono">100.000.000 DH</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-gradient-to-r from-amber-400 to-amber-600 text-black font-black py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Obtenir Mon Devis Gold ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#040405] border-b border-amber-500/20">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-[#09090b] border border-amber-500/20">
                <span class="text-4xl font-black text-amber-400 font-mono">200M DH</span>
                <span class="text-xs text-slate-400 block mt-2">Primes Gérées</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#09090b] border border-amber-500/20">
                <span class="text-4xl font-black text-emerald-400 font-mono">100%</span>
                <span class="text-xs text-slate-400 block mt-2">Défense Concierge</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#09090b] border border-amber-500/20">
                <span class="text-4xl font-black text-cyan-400 font-mono">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Assistance Dédiée</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#09090b] border border-amber-500/20">
                <span class="text-4xl font-black text-purple-400 font-mono">VIP</span>
                <span class="text-xs text-slate-400 block mt-2">Ligne Directe</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="prestige" class="py-24 bg-[#09090b]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">Collection Gold</span>
                <h2 class="text-3xl md:text-5xl font-black text-white gold-title">Une Protection Sans Compromis.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#14120b] p-8 rounded-3xl border border-amber-500/20 space-y-6">
                    <span class="text-3xl">🏎️</span>
                    <h3 class="text-xl font-bold text-white gold-title">Auto De Luxe & Sport</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Couverture intégrale sans franchise pour véhicules d'exception avec remorquage sécurisé.</p>
                </div>
                <div class="bg-[#14120b] p-8 rounded-3xl border border-amber-500/20 space-y-6">
                    <span class="text-3xl">🏰</span>
                    <h3 class="text-xl font-bold text-white gold-title">Demeures & Palais</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Assurance multirisque prestige couvrant votre propriété, mobilier d'art et collections.</p>
                </div>
                <div class="bg-[#14120b] p-8 rounded-3xl border border-amber-500/20 space-y-6">
                    <span class="text-3xl">💼</span>
                    <h3 class="text-xl font-bold text-white gold-title">Flottes & Entreprises</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Multirisque industrielle et RC dirigeants sur-mesure pour groupes d'envergure.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 text-black text-center font-bold">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight gold-title">Rejoignez Notre Cercle d'Assurés VIP.</h2>
            <button @click="quoteModal = true" class="bg-[#09090b] text-white font-black px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Solliciter Mon Devis Prestige ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#040405] text-slate-500 py-16 text-xs text-center border-t border-amber-500/20">
        © {{ date('Y') }} {{ $agencyName ?? 'Luxury Gold Insurance' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#14120b] rounded-3xl max-w-md w-full p-8 space-y-4 border border-amber-500/30 text-white">
            <div class="flex justify-between items-center border-b border-amber-500/20 pb-3">
                <h3 class="font-black text-lg text-amber-400 gold-title">Devis Prestige</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Gold reçue !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Nom Complexe *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-amber-500/20 rounded-xl px-4 py-3 bg-[#09090b] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Téléphone Ligne Directe *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-amber-500/20 rounded-xl px-4 py-3 bg-[#09090b] text-white">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-amber-600 text-black font-black py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
