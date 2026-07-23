<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', faqOpen: null, quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Family Protection Insurance' }} | Protection Complète de la Famille</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-sky-50/50 text-slate-900 selection:bg-sky-500 selection:text-white">

    <!-- Top Bar -->
    <div class="bg-sky-900 text-sky-100 text-xs py-2.5 px-6">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center font-medium">
            <span>❤️ Protection Santé, Habitation & Auto Pour les Familles au Maroc</span>
            <span>📞 Assistance Famille 24/7: +212 5 22 11 22 33</span>
        </div>
    </div>

    <!-- Header -->
    <header class="bg-white/90 backdrop-blur-md border-b border-sky-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-[1440px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-600 text-white flex items-center justify-center font-black text-xl shadow-md shadow-sky-600/20">
                    👨‍👩‍👧‍👦
                </div>
                <div>
                    <span class="font-extrabold text-lg text-slate-900 tracking-tight block">{{ $agencyName ?? 'Family Protection' }}</span>
                    <span class="text-[10px] font-bold text-sky-600 uppercase tracking-widest block -mt-1">Assurance Famille & Santé</span>
                </div>
            </div>
            
            <nav class="hidden lg:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#formules" class="hover:text-sky-600 transition">Formules Famille</a>
                <a href="#stats" class="hover:text-sky-600 transition">Garanties</a>
                <a href="#avis" class="hover:text-sky-600 transition">Avis Parents</a>
                <a href="#faq" class="hover:text-sky-600 transition">FAQ</a>
            </nav>

            <button @click="quoteModal = true" class="bg-sky-600 hover:bg-sky-500 text-white px-6 py-2.5 rounded-full font-bold text-xs shadow-md transition">
                Devis Famille ➔
            </button>
        </div>
    </header>

    <!-- 90vh Hero -->
    <section class="min-h-[85vh] flex items-center py-16 bg-gradient-to-b from-white via-sky-50/60 to-emerald-50/40 border-b border-sky-100">
        <div class="max-w-[1440px] mx-auto px-6 grid lg:grid-cols-12 gap-12 items-center w-full">
            <div class="lg:col-span-7 space-y-8">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-100 border border-emerald-200 text-xs font-bold text-emerald-800">
                    <span>💚 Protégez Vos Proches En Toute Sérénité</span>
                </div>

                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 leading-[1.05] tracking-tight">
                    Votre Famille <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600">Mérite La Meilleure Protection.</span>
                </h1>

                <p class="text-slate-600 text-base sm:text-lg max-w-2xl leading-relaxed font-medium">
                    Une couverture complète réunissant Assurance Santé 100%, Voiture Familiale et Multirisque Domicile. Zéro tracas financier lors des imprévus de la vie.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                    <button @click="quoteModal = true" class="bg-sky-600 hover:bg-sky-500 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg shadow-sky-600/20 text-center">
                        Calculer Mon Pack Famille ➔
                    </button>
                </div>
            </div>

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-3xl p-8 border border-sky-100 shadow-xl space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-sky-600 block">Simulation Famille</span>
                            <h3 class="font-extrabold text-xl text-slate-900">Pack Sérénité Foyer</h3>
                        </div>
                        <span class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg">🏡</span>
                    </div>

                    <div class="space-y-4 text-xs font-medium">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Nombre d'enfants à charge</label>
                            <select class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50">
                                <option>1 Enfant</option>
                                <option selected>2 Enfants</option>
                                <option>3 Enfants ou +</option>
                            </select>
                        </div>

                        <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 flex justify-between items-center">
                            <div>
                                <span class="text-[10px] text-slate-500 font-bold block">Tarif Pack Global dès</span>
                                <span class="text-2xl font-black text-sky-600 font-mono">240 DH<span class="text-xs font-normal">/mois</span></span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-500 text-white text-[10px] font-bold">100% Remboursé</span>
                        </div>

                        <button @click="quoteModal = true" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-2xl shadow-md transition text-xs">
                            Obtenir la Fiche Famille ➔
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section id="stats" class="py-20 bg-sky-900 text-white">
        <div class="max-w-[1440px] mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-sky-800/50 border border-sky-700">
                <span class="text-4xl font-black text-emerald-400 font-mono">30,000+</span>
                <span class="text-xs font-bold text-sky-200 block mt-2">Familles Assurées</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-800/50 border border-sky-700">
                <span class="text-4xl font-black text-sky-300 font-mono">100%</span>
                <span class="text-xs font-bold text-sky-200 block mt-2">Tiers Payant Cliniques</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-800/50 border border-sky-700">
                <span class="text-4xl font-black text-amber-400 font-mono">48h</span>
                <span class="text-xs font-bold text-sky-200 block mt-2">Dossier Santé Traité</span>
            </div>
            <div class="p-8 rounded-3xl bg-sky-800/50 border border-sky-700">
                <span class="text-4xl font-black text-purple-300 font-mono">24h/7</span>
                <span class="text-xs font-bold text-sky-200 block mt-2">Assistance Médicale</span>
            </div>
        </div>
    </section>

    <!-- Formules -->
    <section id="formules" class="py-24 bg-white">
        <div class="max-w-[1440px] mx-auto px-6 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold text-sky-600 uppercase tracking-widest block">Formules Sur-Mesure</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900">Tout Pour le Bien-Être de Votre Foyer.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-sky-50/50 p-8 rounded-3xl border border-sky-100 space-y-6">
                    <span class="text-3xl">🚗</span>
                    <h3 class="text-xl font-bold text-slate-900">Voiture Familiale</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Assistance panne devant le domicile, véhicule 7 places de remplacement et siège enfant garanti.</p>
                    <button @click="quoteModal = true" class="w-full bg-sky-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-sky-50/50 p-8 rounded-3xl border border-sky-100 space-y-6">
                    <span class="text-3xl">🩺</span>
                    <h3 class="text-xl font-bold text-slate-900">Mutuelle Santé Enfants & Parents</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Couverture optique, soins dentaires, pédiatrie et hospitalisation sans avance de frais.</p>
                    <button @click="quoteModal = true" class="w-full bg-sky-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
                <div class="bg-sky-50/50 p-8 rounded-3xl border border-sky-100 space-y-6">
                    <span class="text-3xl">🏡</span>
                    <h3 class="text-xl font-bold text-slate-900">Maison & Responsabilité Civile</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Dégât des eaux, incendie, vol et protection des dommages causés par les enfants à l'école.</p>
                    <button @click="quoteModal = true" class="w-full bg-sky-600 text-white font-bold py-3 rounded-xl text-xs">Simuler ➔</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Strong CTA -->
    <section class="py-20 bg-sky-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black tracking-tight">Offrez À Votre Famille La Protection Qu'Elle Mérite.</h2>
            <button @click="quoteModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-10 py-4 rounded-full text-xs shadow-xl transition">
                Prendre Mon Devis Famille ➔
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-16 text-xs text-center">
        © {{ date('Y') }} {{ $agencyName ?? 'Family Protection' }}. Tous droits réservés.
    </footer>

    <!-- Quote Modal -->
    <div x-show="quoteModal" style="display:none;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-4 shadow-2xl text-slate-900">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-black text-lg text-slate-900">Devis Famille Instantané</h3>
                <button @click="quoteModal = false" class="text-slate-400 font-bold">✕</button>
            </div>
            <form @submit.prevent="alert('Demande transmise !'); quoteModal = false" class="space-y-4 text-xs font-medium">
                <div>
                    <label class="block font-bold mb-1">Nom & Prénom *</label>
                    <input type="text" required placeholder="Votre nom" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <div>
                    <label class="block font-bold mb-1">Téléphone GSM *</label>
                    <input type="tel" required placeholder="06 00 00 00 00" class="w-full border rounded-xl px-4 py-3 bg-slate-50">
                </div>
                <button type="submit" class="w-full bg-sky-600 text-white font-bold py-3.5 rounded-xl transition">
                    Envoyer ➔
                </button>
            </form>
        </div>
    </div>

</body>
</html>
