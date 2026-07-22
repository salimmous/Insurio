<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Sanlam Inspire' }} | Corporate Blue</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900">

    <header class="bg-sky-900 text-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <span class="font-black text-xl tracking-tight">{{ $agencyName ?? 'Sanlam Inspire' }}</span>
            <button class="bg-sky-500 hover:bg-sky-400 text-slate-950 font-black text-xs px-5 py-2.5 rounded-xl">Espace Client</button>
        </div>
    </header>

    <section class="py-24 bg-sky-50 border-b border-sky-100">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="px-3.5 py-1.5 bg-sky-200 text-sky-900 text-xs font-bold rounded-md">Assurance Particuliers & Pros</span>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900">La Confiance d'un Grand Groupe Panafricain</h1>
                <p class="text-slate-600 text-base">Solutions d'assurance auto, épargne retraite et couverture des risques professionnels.</p>
                <button class="bg-sky-900 text-white font-bold text-xs px-8 py-4 rounded-xl shadow-md">Obtenir Devis Sanlam ➔</button>
            </div>
            <div class="bg-white p-8 rounded-2xl border border-sky-100 shadow-md">
                <h3 class="font-bold text-lg text-slate-900 mb-4">Calculateur Rapide</h3>
                <div class="p-4 bg-sky-50 rounded-xl text-xs font-bold text-sky-900">Devis Auto & Habitation sous 2 min</div>
            </div>
        </div>
    </section>

</body>
</html>
