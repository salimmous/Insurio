<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Neo-Brutalist Insurance' }} | Assurance Cash & Sans Bla-Bla</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Space Grotesk', sans-serif; }</style>
</head>
<body class="bg-[#fef08a] text-black selection:bg-black selection:text-yellow-300">

    <!-- Neo Header -->
    <header class="bg-white border-b-4 border-black sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-black text-yellow-300 font-black text-2xl flex items-center justify-center border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    ⚡
                </div>
                <span class="font-black text-2xl tracking-tighter uppercase">{{ $agencyName ?? 'Neo Insurance' }}</span>
            </div>
            
            <button @click="quoteModal = true" class="bg-[#a5f3fc] hover:bg-cyan-300 text-black px-6 py-3 border-4 border-black font-black text-xs shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition">
                ⚡ DEVIS CASH
            </button>
        </div>
    </header>

    <!-- 90vh Neo Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-[#fef08a] border-b-4 border-black">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-block bg-[#fbcfe8] px-4 py-2 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] text-xs font-black uppercase">
                    💥 ZÉRO PAPERASSE. ZÉRO PIÈGE. 100% CLAIR.
                </div>

                <h1 class="text-5xl sm:text-7xl lg:text-8xl font-black leading-none uppercase tracking-tighter">
                    L'ASSURANCE. <br>
                    <span class="bg-black text-white px-3 py-1">SANS BLA-BLA.</span>
                </h1>

                <p class="text-black text-lg max-w-xl font-bold leading-snug">
                    Auto, Habitation et Santé au Maroc. Primes transparentes, remboursement direct et assistance immédiate sans charabia juridique.
                </p>

                <div class="pt-2">
                    <button @click="quoteModal = true" class="bg-[#a5f3fc] hover:bg-cyan-300 text-black font-black px-10 py-5 text-sm uppercase border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition">
                        OBTENIR MON TARIF EN 1 MINUTE ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white p-8 border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-6">
                    <div class="border-b-4 border-black pb-4">
                        <span class="text-xs font-black uppercase text-slate-500">SIMULATEUR NEO</span>
                        <h3 class="text-2xl font-black uppercase">TARIF INSTANTANÉ</h3>
                    </div>

                    <div class="p-4 bg-[#fef08a] border-4 border-black space-y-1">
                        <span class="text-xs font-black block uppercase">TARIF AUTO À PARTIR DE</span>
                        <span class="text-3xl font-black font-mono">180 DH / MOIS</span>
                    </div>

                    <button @click="quoteModal = true" class="w-full bg-black text-white font-black py-4 border-2 border-black text-xs uppercase shadow-[4px_4px_0px_0px_rgba(165,243,252,1)]">
                        LANCER LA SIMULATION ➔
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section class="py-24 bg-white border-b-4 border-black">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <h2 class="text-4xl font-black uppercase text-center">NOS FORMULES POP-ART</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#fbcfe8] p-8 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-4">
                    <span class="text-4xl">🚗</span>
                    <h3 class="text-2xl font-black uppercase">AUTO & MOTO</h3>
                    <p class="font-bold text-xs">Dépannage 0 km sous 45 minutes partout au Maroc.</p>
                </div>
                <div class="bg-[#a5f3fc] p-8 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-4">
                    <span class="text-4xl">🏡</span>
                    <h3 class="text-2xl font-black uppercase">HABITATION</h3>
                    <p class="font-bold text-xs">Serrurier & Plombier d'urgence 24/7 sans surprise.</p>
                </div>
                <div class="bg-[#bbf7d0] p-8 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] space-y-4">
                    <span class="text-4xl">🩺</span>
                    <h3 class="text-2xl font-black uppercase">SANTÉ 100%</h3>
                    <p class="font-bold text-xs">Tiers payant direct dans les cliniques sans avance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-black text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-4xl sm:text-6xl font-black uppercase tracking-tight">ALORS, ON S'ASSURE ?</h2>
            <button @click="quoteModal = true" class="bg-[#fef08a] text-black font-black px-10 py-5 text-sm uppercase border-4 border-white shadow-[6px_6px_0px_0px_rgba(255,255,255,1)]">
                DEVIS IMMÉDIAT ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white text-black py-12 text-xs text-center border-t-4 border-black font-bold">
        © {{ date('Y') }} {{ $agencyName ?? 'Neo-Brutalist Insurance' }}. TOUS DROITS RÉSERVÉS.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="bg-[#fef08a] border-4 border-black p-8 max-w-md w-full shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-4">
            <div class="flex justify-between items-center border-b-4 border-black pb-3">
                <h3 class="font-black text-xl uppercase">DEVIS INSTANTANÉ</h3>
                <button @click="quoteModal = false" class="font-black text-2xl">✕</button>
            </div>
            <form @submit.prevent="alert('Transmis !'); quoteModal = false" class="space-y-4 font-bold text-xs">
                <div>
                    <label class="block mb-1 uppercase">NOM COMPLET</label>
                    <input type="text" required class="w-full border-4 border-black p-3 bg-white">
                </div>
                <div>
                    <label class="block mb-1 uppercase">TÉLÉPHONE GSM</label>
                    <input type="tel" required class="w-full border-4 border-black p-3 bg-white">
                </div>
                <button type="submit" class="w-full bg-black text-white font-black py-4 uppercase">ENVOYER ➔</button>
            </form>
        </div>
    </div>

</body>
</html>
