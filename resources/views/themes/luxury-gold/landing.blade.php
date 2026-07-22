<!-- THEME: LUXURY GOLD (VIP PRIVATE BANKING STYLE) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $agencyName ?? 'Luxury Gold' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-zinc-950 text-amber-100 font-['Cinzel'] min-h-screen">

    <header class="border-b border-amber-500/20 bg-zinc-900/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <span class="font-bold text-2xl tracking-widest text-amber-400 uppercase">L U X U R Y • {{ $agencyName ?? 'Prestige Insurance' }}</span>
            <button class="border border-amber-500 text-amber-400 hover:bg-amber-500 hover:text-zinc-950 px-6 py-2.5 rounded-none font-['Plus_Jakarta_Sans'] text-xs font-bold uppercase tracking-widest transition">Private Concierge</button>
        </div>
    </header>

    <section class="py-32 max-w-5xl mx-auto px-8 text-center space-y-8">
        <span class="text-xs font-['Plus_Jakarta_Sans'] font-bold uppercase tracking-widest text-amber-500">Privilège & Patrimoine High-Net-Worth</span>
        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight">L'Excellence d'une Couverture Privée Sur-Mesure</h1>
        <p class="text-zinc-400 font-['Plus_Jakarta_Sans'] text-sm md:text-base max-w-2xl mx-auto leading-relaxed">Résidences d'exception, yachts, collections privées et protection de patrimoine familial.</p>
        <div class="pt-4 font-['Plus_Jakarta_Sans']">
            <button class="bg-amber-500 text-zinc-950 font-bold text-xs uppercase tracking-widest px-10 py-4 shadow-2xl hover:bg-amber-400 transition">Demander un Entretien Privé</button>
        </div>
    </section>

</body>
</html>
