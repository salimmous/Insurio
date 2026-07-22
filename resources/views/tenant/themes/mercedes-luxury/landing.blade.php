<!-- THEME: MERCEDES LUXURY (VIP PRIVATE BANKING STYLE) -->
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Private Banking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Cinzel', serif; } </style>
</head>
<body class="bg-zinc-950 text-amber-100 min-h-screen">
    <header class="border-b border-amber-500/20 bg-zinc-900/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-22 flex items-center justify-between">
            <span class="font-bold text-2xl tracking-widest text-amber-400 uppercase">M E R C E D E S • {{ $agencyName }}</span>
            <button @click="quoteModal = true" class="border border-amber-500 text-amber-400 hover:bg-amber-500 hover:text-zinc-950 px-6 py-2.5 font-sans text-xs font-bold uppercase tracking-widest transition">Private Desk</button>
        </div>
    </header>

    <section class="py-32 max-w-5xl mx-auto px-8 text-center space-y-8">
        <span class="text-xs font-sans font-bold uppercase tracking-widest text-amber-500">Excellence & Patrimoine VIP</span>
        <h1 class="text-4xl md:text-6xl font-serif text-white leading-tight">L'Équilibre Parfait de la Sécurité Privée</h1>
        <p class="text-zinc-400 font-sans text-sm md:text-base max-w-2xl mx-auto leading-relaxed">Résidences d'exception, yachts, collections de prestige et gouvernance familiale.</p>
        <div class="pt-4 font-sans">
            <button @click="quoteModal = true" class="bg-amber-500 text-zinc-950 font-bold text-xs uppercase tracking-widest px-9 py-4 shadow-2xl hover:bg-amber-400 transition">Conseil Privé Sur-Mesure</button>
        </div>
    </section>
</body>
</html>
