<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Maison de Haute Assurance' }} | Excellence & Sur-Mesure</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,800;0,900;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; } .serif-title { font-family: 'Playfair Display', serif; }</style>
</head>
<body class="bg-[#1c1917] text-stone-100 selection:bg-amber-600 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-[#12100e] border-b border-stone-800 text-amber-500/80 text-xs py-2.5 px-6 font-serif">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <span>⚜️ Maison de Courtage Privé & Haute Protection • Casablanca • Paris • Genève</span>
            <span>📞 Salon Concierge: +212 5 22 99 77 00</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#1c1917]/90 backdrop-blur-md border-b border-stone-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-600 to-stone-900 text-amber-300 flex items-center justify-center font-serif text-xl border border-amber-600/40">
                    LV
                </div>
                <div>
                    <span class="font-bold text-lg text-white tracking-widest uppercase serif-title block">{{ $agencyName ?? 'Maison Haute Protection' }}</span>
                    <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest block -mt-1">Private Brokerage & Fine Art</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-medium text-stone-400 tracking-wider">
                <a href="#chambres" class="hover:text-amber-400 transition">Collection</a>
                <a href="#stats" class="hover:text-amber-400 transition">Patrimoine</a>
                <a href="#concierge" class="hover:text-amber-400 transition">Conciergerie</a>
                <a href="#faq" class="hover:text-amber-400 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-amber-600 hover:bg-amber-500 text-stone-950 px-6 py-2.5 rounded-full font-bold text-xs shadow-lg transition">
                Entretien Privé ➔
            </button>
        </div>
    </header>

    <!-- 90vh Luxury Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#1c1917] via-[#26221f] to-[#1c1917] border-b border-stone-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-950/60 border border-amber-800/40 text-xs font-serif text-amber-300">
                    <span>⚜️ L'Excellence du Sur-Mesure Patrimonial</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-[1.05] serif-title">
                    L'Art Suprême <br>
                    <span class="italic text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-400 to-amber-600">De La Protection.</span>
                </h1>

                <p class="text-stone-300 text-base sm:text-lg max-w-2xl leading-relaxed font-light">
                    Haut courtage dédié aux collections privées, yachts, demeures historiques, supercars et responsabilités de gouvernance au Maroc et à l'international.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-medium">
                    <button @click="quoteModal = true" class="bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold px-8 py-4 rounded-full text-xs transition shadow-xl text-center">
                        Solliciter Notre Expertise Privée ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Signature Card -->
            <div class="lg:col-span-5">
                <div class="bg-[#26221f] rounded-3xl p-8 border border-amber-800/40 shadow-2xl space-y-6">
                    <div class="border-b border-stone-700 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] text-amber-400 font-bold uppercase tracking-widest block">Signature Privée</span>
                            <h3 class="font-bold text-xl text-white serif-title">Chambre d'Évaluation</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-amber-950 text-amber-400 flex items-center justify-center font-bold text-lg">👑</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-[#1c1917] border border-stone-800 space-y-2">
                            <span class="text-stone-400 text-[10px] uppercase block">Garantie d'Exception Maximale</span>
                            <span class="text-2xl font-black text-amber-400 font-mono">200.000.000 DH</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Contacter le Cabinet Privé ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#12100e] border-b border-stone-800">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center font-serif">
            <div class="p-8 rounded-3xl bg-[#1c1917] border border-stone-800">
                <span class="text-4xl font-bold text-amber-400 font-mono">500M DH</span>
                <span class="text-xs text-stone-400 block mt-2">Patrimoine Assuré</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#1c1917] border border-stone-800">
                <span class="text-4xl font-bold text-amber-400 font-mono">100%</span>
                <span class="text-xs text-stone-400 block mt-2">Confidentialité Signée</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#1c1917] border border-stone-800">
                <span class="text-4xl font-bold text-amber-400 font-mono">24/7</span>
                <span class="text-xs text-stone-400 block mt-2">Concierge Dédié</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#1c1917] border border-stone-800">
                <span class="text-4xl font-bold text-amber-400 font-mono">3 Villes</span>
                <span class="text-xs text-stone-400 block mt-2">Salons Privés Maroc</span>
            </div>
        </div>
    </section>

    <!-- 3 Collections -->
    <section id="chambres" class="py-24 bg-[#1c1917]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">Collection Privée</span>
                <h2 class="text-3xl md:text-5xl font-bold text-white serif-title">Haute Protection du Patrimoine.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#26221f] p-8 rounded-3xl border border-stone-800 space-y-6">
                    <span class="text-3xl">🏎️</span>
                    <h3 class="text-xl font-bold text-white serif-title">Supercars & Prestige</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">Couverture valeur agréée d'expert pour véhicules de collection et de luxe sans décote.</p>
                </div>
                <div class="bg-[#26221f] p-8 rounded-3xl border border-stone-800 space-y-6">
                    <span class="text-3xl">🏰</span>
                    <h3 class="text-xl font-bold text-white serif-title">Villas & Domaines</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">Multirisque résidence d'exception couvrant œuvres d'art, bijoux et objets précieux.</p>
                </div>
                <div class="bg-[#26221f] p-8 rounded-3xl border border-stone-800 space-y-6">
                    <span class="text-3xl">🛥️</span>
                    <h3 class="text-xl font-bold text-white serif-title">Yachts & Marine</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">Assurance corps et machines pour embarcations de plaisance et yachts en Méditerranée et Atlantique.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-amber-600 text-stone-950 text-center font-bold">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-extrabold serif-title">Demandez Un Bilan Patrimonial Sur-Mesure.</h2>
            <button @click="quoteModal = true" class="bg-[#1c1917] text-white font-bold px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Prendre Rendez-vous en Salon Privé ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#12100e] text-stone-500 py-16 text-xs text-center border-t border-stone-800">
        © {{ date('Y') }} {{ $agencyName ?? 'Maison de Haute Assurance' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#26221f] rounded-3xl max-w-md w-full p-8 space-y-4 border border-amber-800/40 text-white">
            <div class="flex justify-between items-center border-b border-stone-700 pb-3">
                <h3 class="font-bold text-lg text-amber-400 serif-title">Entretien Privé</h3>
                <button @click="quoteModal = false" class="text-stone-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande confidentielle a été reçue.'); quoteModal = false" class="space-y-4 text-xs">
                <div>
                    <label class="block mb-1 text-stone-300 font-bold">Nom Complexe *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-stone-800 rounded-xl px-4 py-3 bg-[#1c1917] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-stone-300 font-bold">Téléphone Ligne Directe *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-stone-800 rounded-xl px-4 py-3 bg-[#1c1917] text-white">
                </div>
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold py-3.5 rounded-xl transition">
                    Transmettre ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
