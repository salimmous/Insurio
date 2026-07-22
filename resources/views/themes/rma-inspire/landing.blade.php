<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'RMA Inspire' }} | Institutionnel & Proximité</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900">

    <div class="bg-blue-900 text-slate-200 text-xs py-2 px-6 flex justify-between items-center">
        <span>RMA Partner Network • Réseau d'agences agréées au Maroc</span>
        <span>Assistance 24h/7j: +212 5 22 00 00 00</span>
    </div>

    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-500 text-blue-950 font-black flex items-center justify-center rounded-xl text-lg">R</div>
                <span class="font-black text-xl text-blue-950">{{ $agencyName ?? 'RMA Inspire' }}</span>
            </div>
            <button class="bg-blue-900 text-white font-bold text-xs px-5 py-2.5 rounded-xl">Devis En Ligne</button>
        </div>
    </header>

    <section class="py-24 bg-slate-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 text-center space-y-6">
            <span class="px-3.5 py-1.5 bg-amber-100 text-amber-900 text-xs font-bold rounded-md">Institution d'Assurance au Maroc</span>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900">Protégez Votre Avenir avec un Leader</h1>
            <p class="text-slate-600 text-base max-w-xl mx-auto font-medium">Auto, Habitation, Retraite & Santé avec un réseau national de proximité.</p>
            <button class="bg-blue-900 text-white font-bold text-xs px-8 py-4 rounded-xl shadow-md">Simuler mes garanties ➔</button>
        </div>
    </section>

</body>
</html>
