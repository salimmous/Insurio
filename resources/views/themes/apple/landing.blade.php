<!-- THEME: APPLE INSURANCE (PURE WHITE, MINIMAL) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $agencyName ?? 'Apple Insurance' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-slate-900 font-['Inter'] tracking-tight">

    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="font-extrabold text-xl text-slate-900">{{ $agencyName ?? 'Apple Insurance' }}</span>
            <button class="bg-slate-900 text-white px-5 py-2 rounded-full font-bold text-xs">Simuler Tarif</button>
        </div>
    </header>

    <section class="py-36 text-center max-w-4xl mx-auto px-6 space-y-8">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Minimal Design Engine</span>
        <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-none">L'assurance. Redéfinie.</h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-normal">Une clarté absolue. Sans paperasse. Tout est pensé pour vous offrir la sérénité parfaite.</p>
        <div class="pt-4">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-lg">Découvrir les offres ➔</button>
        </div>
    </section>

</body>
</html>
