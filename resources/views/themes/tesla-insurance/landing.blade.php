<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Tesla Telematics Insurance' }} | Autonomous Risk Rating</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-black text-white selection:bg-red-600 selection:text-white">

    <!-- Top Telematics Banner -->
    <div class="bg-[#09090b] border-b border-red-900/40 text-red-500 text-xs py-2.5 px-6 font-mono">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-600 animate-ping"></span>
                AUTOPILOT SAFETY SCORE: 98/100 • REAL-TIME RATING ACTIVE
            </span>
            <span>MOROCCO TELEMATICS ENGINE</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-black/90 backdrop-blur-md border-b border-zinc-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-red-600/30">
                    T
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-widest uppercase block">{{ $agencyName ?? 'Tesla Insurance' }}</span>
                    <span class="text-[9px] font-mono text-red-500 uppercase tracking-widest block -mt-1">Real-Time Risk Telematics</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-zinc-400 font-mono">
                <a href="#telematics" class="hover:text-white transition">Driver Score</a>
                <a href="#stats" class="hover:text-white transition">Metrics</a>
                <a href="#coverage" class="hover:text-white transition">Coverage</a>
                <a href="#faq" class="hover:text-white transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-lg shadow-red-600/30 transition font-mono">
                Order Coverage ➔
            </button>
        </div>
    </header>

    <!-- 95vh Tesla Hero -->
    <section class="min-h-[90vh] flex items-center py-16 bg-gradient-to-b from-black via-zinc-950 to-black border-b border-zinc-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-950/80 border border-red-800/60 text-xs font-mono text-red-400">
                    <span>⚡ Real-Time Driving Safety Score Engine</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Autonomous Safety Rating. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-400 to-amber-500">Primes Ajustées À La Seconde.</span>
                </h1>

                <p class="text-zinc-400 text-base sm:text-lg max-w-2xl leading-relaxed font-normal">
                    Assurance auto télématique de nouvelle génération au Maroc. Payez selon votre score de conduite réel. Économisez jusqu'à 35% sur votre prime annuelle.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-mono">
                    <button @click="quoteModal = true" class="bg-red-600 hover:bg-red-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-red-600/30 text-center">
                        Calculate My Safety Score ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Live Telematics Score Card -->
            <div class="lg:col-span-5">
                <div class="bg-zinc-900 rounded-3xl p-8 border border-zinc-800 shadow-2xl space-y-6">
                    <div class="border-b border-zinc-800 pb-4 flex justify-between items-center font-mono">
                        <div>
                            <span class="text-[10px] text-red-500 uppercase tracking-widest block">Telematics Meter</span>
                            <h3 class="font-extrabold text-xl text-white">Driver Score Live</h3>
                        </div>
                        <span class="text-2xl font-black text-emerald-400 font-mono">98/100</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div class="p-4 rounded-2xl bg-black border border-zinc-800 space-y-2">
                            <div class="flex justify-between text-[11px]">
                                <span>FREINAGE D'URGENCE</span>
                                <span class="text-emerald-400 font-bold">0.02/100 KM</span>
                            </div>
                            <div class="w-full bg-zinc-900 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-400 h-full w-[98%]"></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-black border border-zinc-800 flex justify-between items-center">
                            <div>
                                <span class="text-zinc-500 text-[10px] block">RÉDUCTION PRIME ESTIMÉE</span>
                                <span class="text-2xl font-black text-red-500 font-mono">-35% / AN</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-red-600 text-white text-[10px] font-bold">Top Driver</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Activate Telematics Policy ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-zinc-950 font-mono text-center border-b border-zinc-800">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-black border border-zinc-800">
                <span class="text-4xl font-black text-red-500">1M+ KM</span>
                <span class="text-xs text-zinc-400 block mt-2">Trajets Analysés</span>
            </div>
            <div class="p-8 rounded-3xl bg-black border border-zinc-800">
                <span class="text-4xl font-black text-emerald-400">0 ms</span>
                <span class="text-xs text-zinc-400 block mt-2">Détection Crash Instantanée</span>
            </div>
            <div class="p-8 rounded-3xl bg-black border border-zinc-800">
                <span class="text-4xl font-black text-cyan-400">-35%</span>
                <span class="text-xs text-zinc-400 block mt-2">Réduction Conducteur Sûr</span>
            </div>
            <div class="p-8 rounded-3xl bg-black border border-zinc-800">
                <span class="text-4xl font-black text-amber-400">24/7</span>
                <span class="text-xs text-zinc-400 block mt-2">Dépannage Autopilot</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="coverage" class="py-24 bg-black">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 font-mono">
                <span class="text-xs font-bold text-red-500 uppercase tracking-widest block">Telematics Specs</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Autonomous Protection Matrix.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 font-mono">
                <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 space-y-6">
                    <span class="text-3xl">🏎️</span>
                    <h3 class="text-xl font-bold text-white">Auto Telematics</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed font-sans">Collision, vol, incendie et dépannage sous 30 minutes avec géolocalisation GPS.</p>
                </div>
                <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 space-y-6">
                    <span class="text-3xl">🏠</span>
                    <h3 class="text-xl font-bold text-white">Smart Home Shield</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed font-sans">Protection connectée contre les dégâts des eaux, incendie et intrusions.</p>
                </div>
                <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 space-y-6">
                    <span class="text-3xl">🔋</span>
                    <h3 class="text-xl font-bold text-white">EV & Battery Protection</h3>
                    <p class="text-zinc-400 text-xs leading-relaxed font-sans">Garantie spécifique batterie haute tension et assistance recharge d'urgence.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-red-600 text-white text-center font-mono">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Upgrade To Real-Time Insurance Today.</h2>
            <button @click="quoteModal = true" class="bg-black hover:bg-zinc-900 text-white font-bold px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Order Telematics Policy ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-zinc-950 text-zinc-500 py-16 text-xs text-center border-t border-zinc-800 font-mono">
        © {{ date('Y') }} {{ $agencyName ?? 'Tesla Insurance' }}. ACAPS Licensed Morocco.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-zinc-900 rounded-3xl max-w-md w-full p-8 space-y-4 border border-zinc-800 text-white font-mono">
            <div class="flex justify-between items-center border-b border-zinc-800 pb-3">
                <h3 class="font-bold text-base text-red-500">Order Policy Spec</h3>
                <button @click="quoteModal = false" class="text-zinc-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Tesla Policy Order Received !'); quoteModal = false" class="space-y-4 text-xs">
                <div>
                    <label class="block mb-1 text-zinc-300">Name *</label>
                    <input type="text" required class="w-full border border-zinc-800 rounded-xl px-4 py-3 bg-black text-white">
                </div>
                <div>
                    <label class="block mb-1 text-zinc-300">Phone GSM *</label>
                    <input type="tel" required class="w-full border border-zinc-800 rounded-xl px-4 py-3 bg-black text-white">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-3.5 rounded-xl transition">
                    Submit ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
