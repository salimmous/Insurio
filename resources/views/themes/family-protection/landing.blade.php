<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Family Protection' }} | Protéger vos Enfants & Foyer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-rose-50/30 text-slate-900">

    <header class="bg-white border-b border-rose-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white font-bold flex items-center justify-center text-xl">❤️</div>
                <span class="font-black text-xl text-rose-950">{{ $agencyName ?? 'Family Protection' }}</span>
            </div>
            <button class="bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs px-5 py-2.5 rounded-2xl shadow-md">Assurance Famille</button>
        </div>
    </header>

    <section class="py-24 max-w-5xl mx-auto px-6 text-center space-y-6">
        <span class="px-4 py-1.5 bg-rose-100 text-rose-800 text-xs font-bold rounded-full">Prévoyance & Avenir des Enfants</span>
        <h1 class="text-4xl md:text-6xl font-black text-rose-950">Garantir la Sérénité et le Confort de Votre Foyer</h1>
        <p class="text-slate-600 text-base max-w-xl mx-auto">Solutions d'assurance éducation, étude des enfants et capital prévoyance souscription rapide.</p>
        <button class="bg-rose-600 text-white font-bold text-xs px-8 py-4 rounded-2xl shadow-lg">Simuler mon tarif ➔</button>
    </section>

</body>
</html>
