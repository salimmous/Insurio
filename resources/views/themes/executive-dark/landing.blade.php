<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Executive Private Insurance' }} | Haute Protection Patrimoniale</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#090d16] text-slate-100 selection:bg-amber-500 selection:text-black">

    <!-- Top Bar -->
    <div class="bg-[#05080e] border-b border-amber-900/30 text-amber-400 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <span>👑 Cabinet Privé de Courtage & Gestion de Fortune • Maroc</span>
            <span>📞 Conciergerie Privée 24/7: +212 5 22 88 99 00</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#090d16]/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-black flex items-center justify-center font-black text-xl shadow-lg shadow-amber-500/20">
                    ❖
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Executive Private Insurance' }}</span>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest block -mt-1">Private Wealth & Corporate Protection</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-400">
                <a href="#patrimoine" class="hover:text-amber-400 transition">Protection Patrimoine</a>
                <a href="#stats" class="hover:text-amber-400 transition">Chiffres Clefs</a>
                <a href="#avis" class="hover:text-amber-400 transition">Témoignages</a>
                <a href="#faq" class="hover:text-amber-400 transition">FAQ Concierge</a>
            </nav>

            <button @click="quoteModal = true" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-black px-6 py-2.5 rounded-full font-black text-xs shadow-lg shadow-amber-500/20 transition">
                Entretien Privé ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero Section -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#090d16] via-[#0d1322] to-[#090d16] border-b border-slate-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-950/80 border border-amber-700/60 text-xs font-bold text-amber-300">
                    <span>👑 Courtage Privé Pour Dirigeants & Familles Fortunées</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    L'Art de Protéger <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-yellow-200 to-amber-500">Ce Qui A Du Prix.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Gestion sur-mesure des risques complexes : Flottes de prestige, demeures de luxe, yachts, œuvres d'art et responsabilité civile des mandataires sociaux au Maroc.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-amber-500 hover:bg-amber-400 text-black font-black px-8 py-4 rounded-full text-xs transition shadow-lg shadow-amber-500/20 text-center">
                        Solliciter un Bilan Privé ➔
                    </button>
                    <a href="#patrimoine" class="bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-700 font-bold px-8 py-4 rounded-full text-xs transition text-center">
                        Chambres d'Expertise
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-slate-800 grid grid-cols-3 gap-6 text-slate-400 text-xs font-semibold">
                    <div>
                        <span class="text-amber-400 block font-bold">100% Confidentiel</span>
                        <span class="text-[10px]">Accord NDA Privé</span>
                    </div>
                    <div>
                        <span class="text-amber-400 block font-bold">Expert Dédié</span>
                        <span class="text-[10px]">Interlocuteur Unique</span>
                    </div>
                    <div>
                        <span class="text-amber-400 block font-bold">Agrée ACAPS</span>
                        <span class="text-[10px]">Conformité Maroc</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-[#0e1424] rounded-3xl p-8 border border-amber-950/60 shadow-2xl space-y-6">
                    <div class="border-b border-slate-800 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-amber-400 block">Cabinet Privé</span>
                            <h3 class="font-extrabold text-xl text-white">Matrice Patrimoniale</h3>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-amber-950/80 text-amber-400 flex items-center justify-center font-bold text-lg">💎</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 space-y-2">
                            <span class="text-slate-400 text-[10px] uppercase font-bold block">Plafond Garantie De Luxe</span>
                            <span class="text-2xl font-black text-amber-400 font-mono">150.000.000 DH</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-amber-500 hover:bg-amber-400 text-black font-black py-3.5 rounded-xl transition text-xs shadow-lg">
                            Accéder au Salon Privé ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#05080e] border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-[#090d16] border border-slate-800">
                <span class="text-4xl font-black text-amber-400 font-mono">350M DH</span>
                <span class="text-xs text-slate-400 block mt-2">Capitaux Assurés</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#090d16] border border-slate-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">100%</span>
                <span class="text-xs text-slate-400 block mt-2">Discrétion Absolue</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#090d16] border border-slate-800">
                <span class="text-4xl font-black text-cyan-400 font-mono">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Conciergerie Dédiée</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#090d16] border border-slate-800">
                <span class="text-4xl font-black text-purple-400 font-mono">30 Ans</span>
                <span class="text-xs text-slate-400 block mt-2">D'Excellence au Maroc</span>
            </div>
        </div>
    </section>

    <!-- 4 Chambers -->
    <section id="patrimoine" class="py-24 bg-[#090d16]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest block">Chambres de Protection</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Des Solutions de Haute Voleée.</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-[#0e1424] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏎️</span>
                    <h3 class="text-xl font-bold text-white">Flottes de Prestige & Supercars</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Couverture tous risques sans vétusté pour véhicules de collection et de luxe avec assistance transport fermée.</p>
                </div>
                <div class="bg-[#0e1424] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏰</span>
                    <h3 class="text-xl font-bold text-white">Demeures d'Exception & Villas</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Multirisque résidence de luxe incluant objets d'art, bijoux, piscines et responsabilité civile de propriété.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-amber-600 to-amber-700 text-black text-center font-bold">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Confiez Vos Actifs d'Exception à Nos Experts Privés.</h2>
            <button @click="quoteModal = true" class="bg-[#090d16] hover:bg-black text-white font-black px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Prendre Rendez-vous Confidentiel ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#05080e] text-slate-500 py-16 text-xs border-t border-slate-800 text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Executive Private Insurance' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#0e1424] rounded-3xl max-w-md w-full p-8 space-y-4 border border-amber-900/50 text-white">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-black text-lg text-amber-400">Rendez-vous Privé</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Votre demande confidentielle a été transmise !'); quoteModal = false" class="space-y-4 text-xs">
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Nom & Titre *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#090d16] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300 font-bold">Téléphone Ligne Directe *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#090d16] text-white">
                </div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black font-black py-3.5 rounded-xl transition shadow-lg">
                    Confirmer l'Entretien ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
