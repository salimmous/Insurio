<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Executive Dark' }} | High-End Brokerage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 selection:bg-teal-400 selection:text-slate-950 min-h-screen">

    <header class="p-4 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-6xl mx-auto bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-full px-8 h-16 flex items-center justify-between shadow-2xl">
            <span class="font-extrabold text-sm text-white tracking-widest uppercase">Executive • {{ $agencyName ?? 'Executive Dark' }}</span>
            <button class="bg-teal-400 hover:bg-teal-300 text-slate-950 font-black text-xs px-5 py-2 rounded-full uppercase tracking-wider transition">
                Simuler ➔
            </button>
        </div>
    </header>

    <section class="pt-40 pb-28 max-w-5xl mx-auto px-6 text-center space-y-8">
        <span class="text-xs font-mono font-bold uppercase tracking-widest text-teal-400">Courtage Privé Haut de Gamme</span>
        <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">L'Excellence du Courtage d'Assurance pour Décideurs</h1>
        <p class="text-slate-400 text-base max-w-2xl mx-auto leading-relaxed">Mandat exclusif de négociation des primes et garanties auprès des plus grandes compagnies du marché.</p>
        <button class="bg-teal-400 hover:bg-teal-300 text-slate-950 font-black text-xs uppercase tracking-wider px-8 py-4 rounded-full shadow-xl transition">
            Demander Étude Gratuite
        </button>
    </section>

</body>
</html>
