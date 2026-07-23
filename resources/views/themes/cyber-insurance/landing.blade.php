<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Cyber Defense Insurance' }} | Protection des Risques Informatiques & Données</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Space Grotesk', sans-serif; }</style>
</head>
<body class="bg-[#070b14] text-slate-100 selection:bg-cyan-500 selection:text-black">

    <!-- Cyber Banner -->
    <div class="bg-[#0c1222] border-b border-cyan-900/50 text-cyan-400 text-xs py-2 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-mono">
            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-ping"></span>
                <span>CYBER THREAT LEVEL: SAFE • SHIELD ACTIVE</span>
            </div>
            <span>SOC 2 TYPE II • CNDP MAROC COMPLIANT</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#070b14]/90 backdrop-blur-md border-b border-cyan-900/40 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-950 border border-cyan-500/40 text-cyan-400 flex items-center justify-center font-bold text-xl shadow-lg shadow-cyan-500/10">
                    🛡️
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-wider font-mono block">{{ $agencyName ?? 'Cyber Defense Insurance' }}</span>
                    <span class="text-[10px] font-mono text-cyan-500 uppercase tracking-widest block -mt-1">Cyber & Digital Risk Protocol</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-400 font-mono">
                <a href="#cyber-coverage" class="hover:text-cyan-400 transition">Protection Cyber</a>
                <a href="#metrics" class="hover:text-cyan-400 transition">Audit Metrics</a>
                <a href="#tech" class="hover:text-cyan-400 transition">Garanties</a>
                <a href="#faq" class="hover:text-cyan-400 transition">FAQ Tech</a>
            </nav>

            <button @click="quoteModal = true" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 px-6 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-cyan-500/20 transition font-mono">
                Run Cyber Risk Audit ➔
            </button>
        </div>
    </header>

    <!-- 90vh Cyber Hero Section -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#070b14] via-[#0c1324] to-[#070b14] border-b border-cyan-900/30 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-950/80 border border-cyan-500/30 text-xs font-mono text-cyan-300">
                    <span>⚡ Protection Ransomware & Piratage Données</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Blindez Votre Entreprise <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-teal-300 to-emerald-400">Contre Les Attaques Cyber.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-sans font-medium">
                    Assurance spécialisée dans la couverture des pertes d'exploitation dues aux ransomwares, vol de données clients, extorsion et responsabilité numérique au Maroc.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-mono">
                    <button @click="quoteModal = true" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-black px-8 py-4 rounded-xl text-xs transition shadow-lg shadow-cyan-400/20 text-center">
                        Lancer Audit De Risque ➔
                    </button>
                    <a href="#cyber-coverage" class="bg-slate-900 hover:bg-slate-800 text-slate-300 border border-cyan-900/50 font-bold px-8 py-4 rounded-xl text-xs transition text-center">
                        Garanties Matrice
                    </a>
                </div>

                <!-- Trust Badges -->
                <div class="pt-6 border-t border-cyan-900/30 grid grid-cols-3 gap-6 text-slate-400 text-xs font-mono">
                    <div>
                        <span class="text-cyan-400 block font-bold">100% CNDP</span>
                        <span class="text-[10px]">Conformité Loi 09-08</span>
                    </div>
                    <div>
                        <span class="text-emerald-400 block font-bold">Zero Fraud</span>
                        <span class="text-[10px]">Indemnisation directe</span>
                    </div>
                    <div>
                        <span class="text-purple-400 block font-bold">SOC 24/7</span>
                        <span class="text-[10px]">Cellule de Crise</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card Cyber Dashboard -->
            <div class="lg:col-span-5">
                <div class="bg-[#0b101d] rounded-3xl p-8 border border-cyan-500/30 shadow-2xl space-y-6">
                    <div class="border-b border-cyan-900/40 pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-mono text-cyan-400 uppercase tracking-widest block">Cyber Scanner</span>
                            <h3 class="font-extrabold text-xl text-white font-mono">Index de Vulnérabilité</h3>
                        </div>
                        <span class="w-10 h-10 rounded-xl bg-cyan-950 border border-cyan-500/30 text-cyan-400 flex items-center justify-center font-mono text-sm">99.4</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div class="p-4 rounded-2xl bg-cyan-950/40 border border-cyan-500/20 space-y-2">
                            <div class="flex justify-between text-[11px]">
                                <span>Protection Ransomware</span>
                                <span class="text-emerald-400 font-bold">ACTIVE (50M DH)</span>
                            </div>
                            <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden">
                                <div class="bg-emerald-400 h-full w-[95%]"></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-cyan-950/40 border border-cyan-500/20 space-y-2">
                            <div class="flex justify-between text-[11px]">
                                <span>Perte d'Exploitation SI</span>
                                <span class="text-cyan-400 font-bold">ACTIVE (100%)</span>
                            </div>
                            <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden">
                                <div class="bg-cyan-400 h-full w-[90%]"></div>
                            </div>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black py-3.5 rounded-xl transition text-xs shadow-lg">
                            Activer Mon Bouclier Cyber ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Metrics -->
    <section id="metrics" class="py-20 bg-[#070b14] border-b border-cyan-900/30">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center font-mono">
            <div class="p-8 rounded-3xl bg-[#0c1222] border border-cyan-900/30">
                <span class="text-4xl font-black text-cyan-400">50M DH</span>
                <span class="text-xs text-slate-400 block mt-2">Plafond Garantie Cyber</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0c1222] border border-cyan-900/30">
                <span class="text-4xl font-black text-emerald-400">&lt; 15 min</span>
                <span class="text-xs text-slate-400 block mt-2">Activation Cellule Crise</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0c1222] border border-cyan-900/30">
                <span class="text-4xl font-black text-purple-400">100%</span>
                <span class="text-xs text-slate-400 block mt-2">Frais Forensique Couverts</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0c1222] border border-cyan-900/30">
                <span class="text-4xl font-black text-amber-400">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Veille Menaces SOC</span>
            </div>
        </div>
    </section>

    <!-- 6 Cyber Coverage Cards -->
    <section id="cyber-coverage" class="py-24 bg-[#090e1a]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 font-mono">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest block">Matrice de Protection</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Couverture 360° Risques Numériques.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-[#0c1222] p-8 rounded-3xl border border-cyan-900/40 space-y-6">
                    <span class="text-3xl">💻</span>
                    <h3 class="text-xl font-bold text-white font-mono">Attaques Ransomware</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Prise en charge des frais de décryptage, négociation d'urgence et remise en état des serveurs.</p>
                </div>
                <div class="bg-[#0c1222] p-8 rounded-3xl border border-cyan-900/40 space-y-6">
                    <span class="text-3xl">🔐</span>
                    <h3 class="text-xl font-bold text-white font-mono">Vol de Données Clients</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Couverture des frais de notification obligatoire CNDP et défense judiciaire en responsabilité.</p>
                </div>
                <div class="bg-[#0c1222] p-8 rounded-3xl border border-cyan-900/40 space-y-6">
                    <span class="text-3xl">📉</span>
                    <h3 class="text-xl font-bold text-white font-mono">Perte d'Exploitation SI</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Indemnisation de la perte de chiffre d'affaires consécutive à l'arrêt forcé du système d'information.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-cyan-600 to-indigo-700 text-slate-950 text-center font-mono">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight text-white">Sécurisez Votre Capital Numérique Dès Aujourd'hui.</h2>
            <button @click="quoteModal = true" class="bg-[#070b14] hover:bg-black text-white font-black px-10 py-4 rounded-xl text-xs shadow-2xl transition">
                Obtenir Mon Audit De Protection ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#070b14] text-slate-500 py-16 text-xs border-t border-cyan-900/30 font-mono text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Cyber Defense Insurance' }}. Protocol ACAPS / CNDP Active.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#0c1222] rounded-3xl max-w-md w-full p-8 space-y-4 border border-cyan-500/40 text-white font-mono">
            <div class="flex justify-between items-center border-b border-cyan-900/50 pb-3">
                <h3 class="font-bold text-base text-cyan-400">Cyber Audit & Quote</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Audit Cyber Transmis !'); quoteModal = false" class="space-y-4 text-xs font-mono">
                <div>
                    <label class="block mb-1 text-slate-300">Nom Entreprise *</label>
                    <input type="text" required placeholder="Société" class="w-full border border-cyan-900 rounded-xl px-4 py-3 bg-[#070b14] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Email IT / DSI *</label>
                    <input type="email" required placeholder="dsi@entreprise.ma" class="w-full border border-cyan-900 rounded-xl px-4 py-3 bg-[#070b14] text-white">
                </div>
                <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-black py-3.5 rounded-xl transition">
                    Submit Cyber Request ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
