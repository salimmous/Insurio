<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Linear Insurance' }} | High-Precision Risk Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#08090a] text-slate-100 selection:bg-indigo-500 selection:text-white">

    <!-- Top Announcement -->
    <div class="bg-[#0f1013] border-b border-slate-800/80 text-slate-400 text-xs py-2 px-6 font-mono">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center">
            <span class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-ping"></span>
                INSURIO ENGINE V2.4 RELEASED • HIGH SPEED QUOTE SYSTEM
            </span>
            <span>ACAPS COMPLIANT • MOROCCO</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-[#08090a]/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-indigo-500/20">
                    ≡
                </div>
                <div>
                    <span class="font-extrabold text-lg text-white tracking-tight block">{{ $agencyName ?? 'Linear Insurance' }}</span>
                    <span class="text-[10px] font-mono text-indigo-400 uppercase tracking-widest block -mt-1">Precision Insurance Engine</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-medium text-slate-400 font-mono">
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#stats" class="hover:text-white transition">Metrics</a>
                <a href="#coverage" class="hover:text-white transition">Coverage</a>
                <a href="#faq" class="hover:text-white transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-lg shadow-indigo-600/30 transition font-mono">
                Get Instant Policy ➔
            </button>
        </div>
    </header>

    <!-- 90vh Linear Hero -->
    <section class="min-h-[88vh] flex items-center py-16 bg-gradient-to-b from-[#08090a] via-[#0e1015] to-[#08090a] border-b border-slate-800/80 relative">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-950/80 border border-indigo-800/60 text-xs font-mono text-indigo-300">
                    <span>⌘ Designed for Speed, Precision and Performance</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Insurance Built <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-emerald-400">With Absolute Precision.</span>
                </h1>

                <p class="text-slate-400 text-base sm:text-lg max-w-2xl leading-relaxed font-normal">
                    L'assurance auto, habitation et santé repensée pour la vitesse. Cotation instantanée sous 60 secondes et remboursement accéléré.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2 font-mono">
                    <button @click="quoteModal = true" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-indigo-600/30 text-center">
                        Launch Policy Builder ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card Interface Mockup -->
            <div class="lg:col-span-5">
                <div class="bg-[#0e1015] rounded-3xl p-8 border border-slate-800 shadow-2xl space-y-6">
                    <div class="border-b border-slate-800 pb-4 flex justify-between items-center font-mono">
                        <div>
                            <span class="text-[10px] text-indigo-400 uppercase tracking-widest block">Command Interface</span>
                            <h3 class="font-extrabold text-xl text-white">Live Quote Console</h3>
                        </div>
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                    </div>

                    <div class="space-y-4 text-xs font-mono">
                        <div class="p-4 rounded-2xl bg-[#08090a] border border-slate-800 space-y-2">
                            <span class="text-slate-400 text-[10px]">EXECUTE QUOTE PROTOCOL</span>
                            <div class="flex justify-between text-emerald-400 font-bold">
                                <span>AUTO_TIERCE_PRO</span>
                                <span>180 DH / MONTH</span>
                            </div>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-2xl shadow-lg transition text-xs">
                            Confirm Policy Spec ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-[#0f1013] border-b border-slate-800">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center font-mono">
            <div class="p-8 rounded-3xl bg-[#08090a] border border-slate-800">
                <span class="text-4xl font-black text-indigo-400">1.2s</span>
                <span class="text-xs text-slate-400 block mt-2">Quote Execution Speed</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#08090a] border border-slate-800">
                <span class="text-4xl font-black text-emerald-400">99.9%</span>
                <span class="text-xs text-slate-400 block mt-2">Claim Approval Rate</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#08090a] border border-slate-800">
                <span class="text-4xl font-black text-purple-400">15,000+</span>
                <span class="text-xs text-slate-400 block mt-2">Active Policyholders</span>
            </div>
            <div class="p-8 rounded-3xl bg-[#08090a] border border-slate-800">
                <span class="text-4xl font-black text-amber-400">24/7</span>
                <span class="text-xs text-slate-400 block mt-2">Real-time Telematics</span>
            </div>
        </div>
    </section>

    <!-- 3 Cards -->
    <section id="coverage" class="py-24 bg-[#08090a]">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4 font-mono">
                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest block">Coverage Specs</span>
                <h2 class="text-3xl md:text-5xl font-black text-white">Engineered For Protection.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8 font-mono">
                <div class="bg-[#0e1015] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏎️</span>
                    <h3 class="text-xl font-bold text-white">Auto & Fleet Spec</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Comprehensive auto collision, theft, glass break and 45-minute roadside dispatch.</p>
                    <button @click="quoteModal = true" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs">Run Spec ➔</button>
                </div>
                <div class="bg-[#0e1015] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🏠</span>
                    <h3 class="text-xl font-bold text-white">Home & Property</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Full residential protection with zero hidden deductible on water leak and fire damage.</p>
                    <button @click="quoteModal = true" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs">Run Spec ➔</button>
                </div>
                <div class="bg-[#0e1015] p-8 rounded-3xl border border-slate-800 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-white">Health & Medical</h3>
                    <p class="text-slate-400 text-xs leading-relaxed font-sans">Direct clinic settlement and 48-hour claim processing with zero paperwork delay.</p>
                    <button @click="quoteModal = true" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs">Run Spec ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center font-mono">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Upgrade Your Risk Infrastructure Today.</h2>
            <button @click="quoteModal = true" class="bg-[#08090a] hover:bg-black text-white font-bold px-10 py-4 rounded-full text-xs shadow-2xl transition">
                Initialize Instant Policy ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0f1013] text-slate-500 py-16 text-xs text-center border-t border-slate-800 font-mono">
        © {{ date('Y') }} {{ $agencyName ?? 'Linear Insurance' }}. ACAPS Licensed Morocco.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-black/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-[#0e1015] rounded-3xl max-w-md w-full p-8 space-y-4 border border-slate-800 text-white font-mono">
            <div class="flex justify-between items-center border-b border-slate-800 pb-3">
                <h3 class="font-bold text-base text-indigo-400">Build Policy Spec</h3>
                <button @click="quoteModal = false" class="text-slate-400 hover:text-white">✕</button>
            </div>
            <form @submit.prevent="alert('Policy Spec Executed !'); quoteModal = false" class="space-y-4 text-xs">
                <div>
                    <label class="block mb-1 text-slate-300">Name *</label>
                    <input type="text" required class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#08090a] text-white">
                </div>
                <div>
                    <label class="block mb-1 text-slate-300">Phone GSM *</label>
                    <input type="tel" required class="w-full border border-slate-800 rounded-xl px-4 py-3 bg-[#08090a] text-white">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 rounded-xl transition">
                    Execute ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
