<!-- THEME 05: HEALTHCARE INSURANCE (MEDICAL & HOSPITALS FOCUS) -->
<div class="bg-sky-50/50 text-slate-900 font-sans">
    <header class="bg-white border-b border-sky-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center font-bold text-white">✚</div>
                <span class="font-bold text-xl text-slate-900">{{ $agencyName }} • Santé</span>
            </div>
            <button @click="quoteModal = true" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs">Mutuelle & Hospitalisation</button>
        </div>
    </header>

    <section class="py-24 max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <span class="text-xs font-bold text-teal-700 bg-teal-50 border border-teal-200 px-3 py-1 rounded-full">Prise en charge Médicale 100%</span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight">La Santé de vos Proches & Collaborateurs en Toute Sécurité</h1>
            <p class="text-slate-600 text-sm md:text-base leading-relaxed">Remboursement garanti des soins dentaires, optiques, consultations spécialisées et cliniques agréées au Maroc.</p>
            <button @click="quoteModal = true" class="bg-teal-600 text-white px-8 py-3.5 rounded-xl font-bold text-xs shadow-md">Simuler Mutuelle Santé ➔</button>
        </div>
        <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-md space-y-4">
            <h3 class="font-bold text-base text-slate-900">Calculateur Tiers-Payant</h3>
            <div class="p-3 bg-sky-50 rounded-xl border border-sky-100 text-xs font-semibold text-sky-900">Remboursement jusqu'à 90% des frais engagés</div>
        </div>
    </section>
</div>
