<!-- THEME 01: CORPORATE BLUE (AXA/ALLIANZ CORPORATE STYLE) -->
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false }" :dir="lang === 'ar' ? 'rtl' : 'ltr'">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Institutionnel Corporate</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <!-- Corporate Dark Blue Top Bar -->
    <div class="bg-blue-950 text-blue-200 text-xs py-2.5 px-8 border-b border-blue-900 flex justify-between items-center font-semibold">
        <div class="flex items-center gap-6">
            <span>🏛️ Groupe d'Assurance Institutionnel Agréé ACAPS</span>
            <span>📞 Ligne Directe Corporate: +212 5 22 00 00 00</span>
        </div>
        <div class="flex gap-4">
            <button @click="lang = 'fr'" :class="lang === 'fr' ? 'text-white font-bold' : 'text-blue-400'">FR 🇫🇷</button>
            <button @click="lang = 'ar'" :class="lang === 'ar' ? 'text-white font-bold' : 'text-blue-400'">العربية 🇲🇦</button>
        </div>
    </div>

    <header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-22 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center font-black text-2xl text-white shadow-lg">C</div>
                <span class="font-black text-2xl tracking-tight text-white">{{ $agencyName }}</span>
            </div>
            <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-500 text-white font-black text-xs px-6 py-3 rounded-lg uppercase tracking-wider shadow-xl transition">Audit Risque Enterprise</button>
        </div>
    </header>

    <section class="py-28 bg-gradient-to-b from-blue-950/60 via-slate-900 to-slate-950 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-12 gap-16 items-center">
            <div class="md:col-span-7 space-y-8">
                <span class="px-4 py-1.5 bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-black rounded-full uppercase tracking-widest">Partenaire Immobilier & Grands Comptes</span>
                <h1 class="text-4xl md:text-6xl font-black tracking-tight leading-tight text-white">Solvabilité & Protection des Actifs Stratégiques</h1>
                <p class="text-slate-400 text-base leading-relaxed">Couverture sur-mesure des risques industriels, responsabilité civile des dirigeants et flottes de transports logistiques.</p>
                <button @click="quoteModal = true" class="bg-blue-600 text-white font-black text-xs uppercase tracking-widest px-8 py-4 rounded-lg shadow-2xl hover:bg-blue-500 transition">Demander un Devis Corporate ➔</button>
            </div>
            <div class="md:col-span-5 bg-slate-800/80 border border-slate-700 p-8 rounded-xl shadow-2xl space-y-6">
                <h3 class="font-bold text-xl text-white">Calculateur de Couverture</h3>
                <div class="space-y-3 text-xs">
                    <div class="p-4 bg-slate-900 border border-slate-800 rounded-lg flex justify-between">
                        <span>Multirisque Industrielle</span>
                        <span class="text-blue-400 font-bold">100M DH</span>
                    </div>
                </div>
                <button @click="quoteModal = true" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-lg text-xs">Lancer l'évaluation ➔</button>
            </div>
        </div>
    </section>
</body>
</html>
