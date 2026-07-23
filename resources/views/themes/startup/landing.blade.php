<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Startup Insurance' }} | Insurance for Fast-Growing Companies</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 selection:bg-indigo-500 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-slate-950 border-b border-slate-800 text-slate-400 text-xs py-2.5 px-6 font-mono">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <span>🚀 INSURANCE ENGINE FOR STARTUPS & SCALE-UPS</span>
            <span class="text-emerald-400 font-bold">100% DIGITAL ONBOARDING</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-emerald-400 text-slate-950 flex items-center justify-center font-black text-xl shadow-lg shadow-indigo-500/20">
                    ⚡
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Startup Insurance' }}</span>
                    <span class="text-[10px] font-mono text-indigo-400 uppercase tracking-widest block -mt-1">Modern Risk Infrastructure</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-400 font-mono">
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#stats" class="hover:text-white transition">Metrics</a>
                <a href="#coverage" class="hover:text-white transition">Coverage</a>
                <a href="#faq" class="hover:text-white transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-lg shadow-indigo-600/30 transition font-mono">
                Get Started ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 border-b border-slate-800 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-950/80 border border-indigo-800/60 text-xs font-mono text-indigo-300">
                    <span>🚀 Tailored Insurance for Founders & Tech Companies</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Scale Your Business. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-teal-300 to-emerald-400">Zero Risk Friction.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    RC Pro Tech, Cyber Security, Santé Groupe et Multirisque Locaux. Une souscription 100% digitale pensée pour les équipes en forte croissance.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-mono">
                    <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-indigo-600/30 text-center">
                        Launch Quote Simulator ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-slate-950 rounded-3xl p-8 border border-slate-800 shadow-2xl space-y-6">
                    <div class="border-b border-slate-800 pb-4 flex justify-between items-center font-mono">
                        <div>
                            <span class="text-[10px] text-indigo-400 uppercase tracking-widest block">Startup Stack</span>
                            <h3 class="font-extrabold text-xl text-white">Coverage Builder</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-indigo-900/50 text-indigo-400 flex items-center justify-center font-bold text-lg">💻</span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-400 block">ONBOARDING TIME</span>
                                <span class="text-xl font-black text-emerald-400">&lt; 2 MINUTES</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold">Insta-Approve</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Start Instant Coverage ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-slate-950 font-mono text-center">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-indigo-400">200+</span>
                <span class="text-xs text-slate-400 block mt-2">Tech Startups Protected</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-emerald-400">100%</span>
                <span class="text-xs text-slate-400 block mt-2">Digital Workflow</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-cyan-400">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">WhatsApp Support</span>
            </div>
            <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800">
                <span class="text-4xl font-black text-purple-400">48h</span>
                <span class="text-xs text-slate-400 block mt-2">Claim Reimbursement</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="coverage" class="py-24 bg-slate-900">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block font-mono">Startup Products</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Built For Scale.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">💻</span>
                    <h3 class="text-xl font-bold text-white font-mono">RC Pro Tech & Cyber</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Couverture des erreurs de code, pertes de données, cyber-attaques et responsabilité contractuelle.</p>
                </div>
                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-white font-mono">Santé Groupe Équipe</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Mutuelle d'entreprise pour attirer et fidéliser vos talents avec tiers payant 100% clinique.</p>
                </div>
                <div class="bg-slate-950 p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏢</span>
                    <h3 class="text-xl font-bold text-white font-mono">Multirisque Coworking & Bureaux</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">Assurance du matériel informatique, ordinateurs portables et locaux contre le vol et dommages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-indigo-600 text-white text-center font-mono">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Protect Your High-Growth Team Today.</h2>
            <button @click="quoteModal = true" class="bg-slate-950 hover:bg-black text-white font-bold px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Get Startup Policy ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-500 py-16 text-xs text-center border-t border-slate-800 font-mono">
        © {{ date('Y') }} {{ $agencyName ?? 'Startup Insurance' }}. ACAPS Licensed Morocco.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-slate-900 rounded-3xl max-w-md w-full p-8 space-y-4 border border-slate-800 text-white font-mono">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-bold text-base text-indigo-400">Startup Quote</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Startup reçue !'); quoteModal = false" class="space-y-4 text-xs font-mono">
                <div>
                    <label class="block mb-1 text-slate-300">Company Name *</label>
                    <input type="text" required placeholder="Startup name" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Founder Phone *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-slate-950 text-white">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-xl transition">
                    Submit Request ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
