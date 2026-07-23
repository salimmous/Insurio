<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Healthcare Medical Insurance' }} | Mutuelle & Santé Maroc</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-teal-50/40 text-slate-900 selection:bg-teal-600 selection:text-white">

    <!-- Top Medical Bar -->
    <div class="bg-teal-950 text-teal-100 text-xs py-2.5 px-6 border-b border-teal-900">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <span>🩺 Tiers Payant Agréé Dans Plus de 500 Cliniques au Maroc</span>
            <span>📞 Télé-assistance Médicale 24/7: +212 5 22 99 88 77</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-teal-100 sticky top-0 z-50">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-teal-600/20">
                    ✚
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Healthcare Insurance' }}</span>
                    <span class="text-[10px] font-bold text-teal-600 uppercase tracking-widest block -mt-1">Mutuelle Santé & Hospitalisation</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#offres" class="hover:text-teal-600 transition">Couvertures Santé</a>
                <a href="#stats" class="hover:text-teal-600 transition">Réseau Cliniques</a>
                <a href="#avis" class="hover:text-teal-600 transition">Témoignages</a>
                <a href="#faq" class="hover:text-teal-600 transition">FAQ Médicale</a>
            </nav>

            <button @click="quoteModal = true" class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Devis Santé Express ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-b from-white via-teal-50/50 to-emerald-50/30 border-b border-teal-100">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-teal-100 border border-teal-200 text-xs font-bold text-teal-800">
                    <span>🏥 Complémentaire Santé CNOPS / CNSS Au Maroc</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 leading-[1.05] tracking-tight">
                    Votre Santé. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-emerald-600 to-cyan-600">Prise En Charge Sans Attente.</span>
                </h1>

                <p class="text-slate-600 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Mutuelle complémentaire individuelle, familiale et groupe entreprise. Prise en charge à 100% des frais d'hospitalisation, optique, dentaire et maternité.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-teal-600 hover:bg-teal-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-teal-600/20 text-center">
                        Calculer Ma Cotisation Santé ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 border border-teal-100 shadow-xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-teal-600 block">Tarificateur Santé</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Estimation Mutuelle</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg">💊</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nombre d'assurés</label>
                            <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                                <option>Individuel</option>
                                <option selected>Couple + Enfants</option>
                                <option>Senior (60 ans+)</option>
                            </select>
                        </div>

                        <div class="p-4 rounded-2xl bg-teal-50 border border-teal-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">Remboursement garanti</span>
                                <span class="text-2xl font-black text-teal-600 font-mono">Sous 48H</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-bold">100% Tiers Payant</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Obtenir Mon Tarif Santé ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-teal-950 text-white">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-teal-900/40 border border-teal-800">
                <span class="text-4xl font-black text-emerald-400 font-mono">500+</span>
                <span class="text-xs font-bold text-teal-200 block mt-2">Cliniques & Labos Agréés</span>
            </div>
            <div class="p-8 rounded-3xl bg-teal-900/40 border border-teal-800">
                <span class="text-4xl font-black text-teal-300 font-mono">48H</span>
                <span class="text-xs font-bold text-teal-200 block mt-2">Remboursement Maladie</span>
            </div>
            <div class="p-8 rounded-3xl bg-teal-900/40 border border-teal-800">
                <span class="text-4xl font-black text-amber-400 font-mono">100%</span>
                <span class="text-xs font-bold text-teal-200 block mt-2">Hospitalisation Prise en Charge</span>
            </div>
            <div class="p-8 rounded-3xl bg-teal-900/40 border border-teal-800">
                <span class="text-4xl font-black text-purple-300 font-mono">24/7</span>
                <span class="text-xs font-bold text-teal-200 block mt-2">Télé-consultation Médicale</span>
            </div>
        </div>
    </section>

    <!-- Formules -->
    <section id="offres" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-teal-600 uppercase tracking-widest block">Formules Santé</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Prise en Charge Intégrale au Maroc.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-teal-50/50 p-8 rounded-3xl border border-teal-100 space-y-6">
                    <span class="text-3xl">👨‍👩‍👧</span>
                    <h3 class="text-xl font-bold text-slate-900">Mutuelle Famille</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Couverture pédiatrique, optique, soins dentaires et maternité avec Tiers Payant clinique direct.</p>
                    <button @click="quoteModal = true" class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-teal-50/50 p-8 rounded-3xl border border-teal-100 space-y-6">
                    <span class="text-3xl">👴</span>
                    <h3 class="text-xl font-bold text-slate-900">Mutuelle Senior</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Prise en charge renforcée des pathologies chroniques, prothèses dentaires et bilans médicaux.</p>
                    <button @click="quoteModal = true" class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-teal-50/50 p-8 rounded-3xl border border-teal-100 space-y-6">
                    <span class="text-3xl">🏢</span>
                    <h3 class="text-xl font-bold text-slate-900">Santé Groupe Entreprise</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Assurance maladie complémentaire collective pour salariés avec gestionnaire de compte dédié.</p>
                    <button @click="quoteModal = true" class="w-full bg-teal-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-teal-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Protégez La Santé De Vos Proches Sans Souci Financier.</h2>
            <button @click="quoteModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Consulter Nos Tarifs Santé ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 text-xs text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Healthcare Insurance' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-slate-900">Devis Mutuelle Santé</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande Santé transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-teal-600 text-white font-bold py-3.5 rounded-xl transition">
                    Envoyer Ma Demande ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
