<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Healthcare Insurance' }} | Mutuelle & Santé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-teal-50/30 text-slate-900">

    <header class="bg-white border-b border-teal-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-600 text-white font-bold flex items-center justify-center text-xl">✚</div>
                <span class="font-extrabold text-xl text-slate-900">{{ $agencyName ?? 'Healthcare Insurance' }}</span>
            </div>
            <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md">Espace Tiers-Payant</button>
        </div>
    </header>

    <section class="py-24 max-w-5xl mx-auto px-6 text-center space-y-6">
        <span class="px-4 py-1.5 bg-teal-100 text-teal-800 text-xs font-bold rounded-full">Protection Santé & Remboursement Clinique</span>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900">Prise en Charge Médicale Intégrale pour Votre Famille</h1>
        <p class="text-slate-600 text-base max-w-xl mx-auto">Remboursement garanti des soins dentaires, optiques, hospitalisation et ordonnances sous 48h.</p>
        <button class="bg-teal-600 text-white font-bold text-xs px-8 py-4 rounded-xl shadow-lg">Calculer ma cotisation santé ➔</button>
    </section>

</body>
</html>
