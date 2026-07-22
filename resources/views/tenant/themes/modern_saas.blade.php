<!-- THEME 04: MODERN SAAS (STRIPE / LINEAR STYLE) -->
<div class="bg-slate-50 text-slate-900 font-sans">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md">S</div>
                <span class="font-extrabold text-xl text-slate-900 tracking-tight">{{ $agencyName }}</span>
            </div>
            <button @click="quoteModal = true" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md hover:bg-indigo-700 transition">Devis Instantané ⚡</button>
        </div>
    </header>

    <section class="py-24 max-w-6xl mx-auto px-6 text-center space-y-8">
        <span class="px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-bold">Plateforme SaaS d'Assurance 2.0</span>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">Gérez vos contrats d'assurance en temps réel</h1>
        <p class="text-slate-600 text-base max-w-2xl mx-auto leading-relaxed">Souscription digitale en 3 minutes, attestation instantanée en PDF et suivi en direct.</p>
        <button @click="quoteModal = true" class="bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg">Commencer la simulation ➔</button>
    </section>
</div>
