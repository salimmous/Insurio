<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Stripe SaaS Insurance' }} | Financial Infrastructure for Risk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#0a0e1a] text-slate-100 selection:bg-purple-500 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-[#060912] border-b border-slate-800 text-slate-400 text-xs py-2.5 px-6 font-mono">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <span>🌐 FINANCIAL INFRASTRUCTURE FOR RISK & INSURANCE</span>
            <span class="text-violet-400 font-bold">API FIRST • ACAPS MAROC</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#0a0e1a]/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-violet-600 via-indigo-600 to-cyan-500 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-violet-500/20">
                    S
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Stripe SaaS Insurance' }}</span>
                    <span class="text-[10px] font-mono text-violet-400 uppercase tracking-widest block -mt-1">Digital Risk Infrastructure</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-medium text-slate-400 font-mono">
                <a href="#features" class="hover:text-white transition">Infrastructure</a>
                <a href="#stats" class="hover:text-white transition">Metrics</a>
                <a href="#coverage" class="hover:text-white transition">Products</a>
                <a href="#faq" class="hover:text-white transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-lg shadow-violet-600/30 transition font-mono">
                Start Integration ➔
            </button>
        </div>
    </header>

    <!-- 95vh Stripe Mesh Gradient Hero -->
    <section class="min-h-[90vh] flex items-center py-16 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-violet-900/30 via-[#0a0e1a] to-[#0a0e1a] border-b border-slate-800/80 relative overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full relative z-10">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-violet-950/80 border border-violet-800/60 text-xs font-mono text-violet-300">
                    <span>✨ Financial Infrastructure for Modern Insurance</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    The Modern Engine <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-purple-300 to-cyan-400">For Risk Management.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-normal">
                    Une plateforme d'assurance ultra-fluide pour l'Automobile, l'Habitation, la Santé et les Flottes d'entreprises. Tarifs transparents et souscription automatisée.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-mono">
                    <button @click="quoteModal = true" class="bg-violet-600 hover:bg-violet-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-violet-600/30 text-center">
                        Explore Live Quotes ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card Mockup -->
            <div class="lg:col-span-5">
                <div class="bg-[#0f1424] rounded-3xl p-8 border border-violet-900/40 shadow-2xl space-y-6">
                    <div class="border-b border-slate-800 pb-4 flex justify-between items-center font-mono">
                        <div>
                            <span class="text-[10px] text-violet-400 uppercase tracking-widest block">Mesh Console</span>
                            <h3 class="font-extrabold text-xl text-white">Live Policy Metric</h3>
                        </div>
                        <span class="w-3 h-3 rounded-full bg-cyan-400 animate-pulse"></span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div class="p-4 rounded-2xl bg-[#0a0e1a] border border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="text-slate-400 text-[10px]">QUOTE SPEED</span>
                                <span class="text-xl font-black text-cyan-400">0.8 SECONDS</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-violet-500/20 text-violet-300 text-[10px] font-bold">API Ready</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-violet-600 hover:bg-violet-500 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Get Custom Proposal ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#060912] font-mono text-center border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-[#0a0e1a] border border-slate-800">
                <span class="text-4xl font-black text-violet-400">99.9%</span>
                <span class="text-xs text-slate-400 block mt-2">Uptime & Reliability</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0a0e1a] border border-slate-800">
                <span class="text-4xl font-black text-cyan-400">48h</span>
                <span class="text-xs text-slate-400 block mt-2">Automated Payout</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0a0e1a] border border-slate-800">
                <span class="text-4xl font-black text-emerald-400">40,000+</span>
                <span class="text-xs text-slate-400 block mt-2">Active Contracts</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#0a0e1a] border border-slate-800">
                <span class="text-4xl font-black text-purple-400">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Concierge Assistance</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="coverage" class="py-24 bg-[#0a0e1a]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 font-mono">
                <span class="text-xs font-bold text-violet-400 uppercase tracking-widest block">Financial Products</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Full-Stack Coverage.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 font-mono">
                <div class="bg-[#0f1424] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-white">Auto & Mobility</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Collision, theft, fire, 45-minute roadside assistance with zero hassle.</p>
                </div>
                <div class="bg-[#0f1424] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-white">Home & Property</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Residential and luxury villa multi-risk coverage with zero hidden deductibles.</p>
                </div>
                <div class="bg-[#0f1424] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-white">Health & Medical</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Instant hospital admission and 48-hour claim reimbursement across Morocco.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-violet-600 via-purple-600 to-indigo-600 text-white text-center font-mono">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Transform Your Risk Management Infrastructure.</h2>
            <button @click="quoteModal = true" class="bg-[#0a0e1a] hover:bg-black text-white font-bold px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Get Started Now ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#060912] text-slate-500 py-16 text-xs text-center border-t border-slate-800 font-mono">
        © {{ date('Y') }} {{ $agencyName ?? 'Stripe SaaS Insurance' }}. ACAPS Licensed Morocco.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#0f1424] rounded-3xl max-w-md w-full p-8 space-y-4 border border-slate-800 text-white font-mono">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-bold text-base text-violet-400">Instant Proposal</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Proposal Sent !'); quoteModal = false" class="space-y-4 text-xs">
                <div>
                    <label class="block mb-1 text-slate-300">Name *</label>
                    <input type="text" required class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#0a0e1a] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Phone GSM *</label>
                    <input type="tel" required class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#0a0e1a] text-white">
                </div>
                <button type="submit" class="w-full bg-violet-600 hover:bg-violet-500 text-white font-bold py-3.5 rounded-xl transition">
                    Submit ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
