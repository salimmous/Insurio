<!-- THEME 02: APPLE INSURANCE (PURE WHITE, MINIMAL) -->
<div class="bg-white text-slate-900 font-sans tracking-tight">
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="font-black text-xl text-slate-900">{{ $agencyName }}</span>
            <div class="flex items-center gap-6 text-xs font-semibold text-slate-600">
                <button @click="quoteModal = true" class="bg-slate-900 text-white px-4 py-2 rounded-full font-bold">Obtenir Devis</button>
            </div>
        </div>
    </header>

    <section class="py-32 text-center max-w-4xl mx-auto px-6 space-y-8">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Insurio Minimal Engine</span>
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-none">L'assurance. Redéfinie.</h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">Une protection d'une clarté absolue. Sans paperasse inutile. Tout est conçu pour vous offrir la sérénité ultime.</p>
        <div class="pt-4">
            <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-full text-sm transition shadow-lg">Simuler mon tarif ➔</button>
        </div>
    </section>
</div>
