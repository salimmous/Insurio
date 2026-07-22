<!-- THEME 03: LUXURY GOLD (VIP PRIVATE BANKING STYLE) -->
<div class="bg-zinc-950 text-amber-100 font-serif min-h-screen">
    <header class="border-b border-amber-500/20 bg-zinc-900/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <span class="font-bold text-2xl tracking-widest text-amber-400 font-serif uppercase">L U X U R Y • {{ $agencyName }}</span>
            <button @click="quoteModal = true" class="border border-amber-500 text-amber-400 hover:bg-amber-500 hover:text-zinc-950 px-6 py-2.5 rounded-none font-sans text-xs font-bold uppercase tracking-widest transition">Gestion de Patrimoine VIP</button>
        </div>
    </header>

    <section class="py-28 max-w-5xl mx-auto px-6 text-center space-y-8">
        <span class="text-xs font-sans font-bold uppercase tracking-widest text-amber-500">Privilège & Protection High-Net-Worth</span>
        <h1 class="text-4xl md:text-6xl font-serif text-white leading-tight">L'Excellence d'une Couverture Privée Sur-Mesure</h1>
        <p class="text-zinc-400 font-sans text-sm md:text-base max-w-2xl mx-auto leading-relaxed">Résidences d'exception, yachts, collections privées et protection de patrimoine familial.</p>
        <div class="pt-4 font-sans">
            <button @click="quoteModal = true" class="bg-amber-500 text-zinc-950 font-bold text-xs uppercase tracking-widest px-8 py-4 shadow-2xl hover:bg-amber-400 transition">Prendre Rendez-vous Privé</button>
        </div>
    </section>
</div>
