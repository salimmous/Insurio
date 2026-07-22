<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Marketplace Insurance' }} | Comparateur Gratuit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-900">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <span class="font-black text-xl text-blue-600">🔍 {{ $agencyName ?? 'Marketplace Assurances' }}</span>
            <button class="bg-blue-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl">Comparer 10 Offres</button>
        </div>
    </header>

    <section class="py-20 max-w-6xl mx-auto px-6 space-y-8">
        <div class="text-center space-y-4">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900">Comparez et Économisez jusqu'à 40% sur Votre Assurance</h1>
            <p class="text-slate-600 text-base">Plus de 15 compagnies d'assurance comparées en temps réel au Maroc.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-lg grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Branche</label>
                <select class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-bold">
                    <option>Assurance Automobile</option>
                    <option>Multirisque Habitation</option>
                    <option>Santé Complémentaire</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Ville</label>
                <input type="text" value="Casablanca" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-bold">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Puissance Fiscale</label>
                <select class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs font-bold">
                    <option>6 CV - 8 CV</option>
                    <option>9 CV - 11 CV</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-blue-600 text-white font-bold text-xs py-3 rounded-xl shadow-md hover:bg-blue-700 transition">
                    Comparer Maintenant 🔍
                </button>
            </div>
        </div>
    </section>

</body>
</html>
