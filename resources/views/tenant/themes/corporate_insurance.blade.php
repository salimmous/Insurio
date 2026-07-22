<!-- THEME 01: CORPORATE INSURANCE (AXA/ALLIANZ STYLE) -->
<div class="bg-white text-slate-900 font-sans">
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-6 border-b border-slate-800 flex justify-between items-center font-medium">
        <div class="flex items-center gap-6">
            <span>📞 Assistance 24/7 Corporate: +212 5 22 00 00 00</span>
            <span class="hidden md:inline">Casablanca • Financial Center</span>
        </div>
        <div class="flex items-center gap-3">
            <button @click="lang = 'fr'" :class="lang === 'fr' ? 'text-white font-bold' : 'text-slate-400'">FR 🇫🇷</button>
            <span>|</span>
            <button @click="lang = 'ar'" :class="lang === 'ar' ? 'text-white font-bold' : 'text-slate-400'">العربية 🇲🇦</button>
        </div>
    </div>

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-900 flex items-center justify-center font-black text-white">C</div>
                <span class="font-extrabold text-xl text-slate-900 tracking-tight">{{ $agencyName }}</span>
            </div>
            <div class="flex items-center gap-8 text-xs font-bold text-slate-700">
                <a href="#solutions" class="hover:text-blue-900">Solutions</a>
                <a href="#stats" class="hover:text-blue-900">Chiffres Clés</a>
                <button @click="quoteModal = true" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl">Devis Institutionnel</button>
            </div>
        </div>
    </header>

    <section class="py-24 bg-gradient-to-b from-blue-50/50 to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-7 space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-blue-900 bg-blue-100 px-3 py-1 rounded-full">Groupe d'Assurance Institutionnel</span>
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight">Expertise, Sérénité & Solvabilité pour votre Entreprise</h1>
                <p class="text-slate-600 text-base leading-relaxed">Accompagnement haut de gamme pour la couverture de vos actifs, flottes de véhicules et protection sociale des collaborateurs.</p>
                <div class="flex gap-4">
                    <button @click="quoteModal = true" class="bg-blue-900 text-white px-8 py-3.5 rounded-xl font-black text-xs uppercase tracking-wider">Lancer audit d'assurance</button>
                </div>
            </div>
            <div class="md:col-span-5 bg-white border border-slate-200 p-8 rounded-2xl shadow-lg space-y-4">
                <h3 class="font-bold text-lg text-slate-900">Simulateur Corporate</h3>
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex justify-between">
                        <span>Accident du Travail (AT)</span>
                        <span class="text-blue-900 font-bold">Conforme ACAPS</span>
                    </div>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex justify-between">
                        <span>Responsabilité Civile (RC Corp)</span>
                        <span class="text-blue-900 font-bold">Garantie 50M DH</span>
                    </div>
                </div>
                <button @click="quoteModal = true" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold text-xs">Simuler mes primes ➔</button>
            </div>
        </div>
    </section>
</div>
