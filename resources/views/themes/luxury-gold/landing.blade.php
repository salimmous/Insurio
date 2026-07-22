<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Luxury Gold Insurance' }} | VIP Wealth & Assets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800;900&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Cinzel', serif; }</style>
</head>
<body class="bg-zinc-950 text-amber-100 min-h-screen selection:bg-amber-500 selection:text-zinc-950">

    <!-- Prestige Header -->
    <header class="border-b border-amber-500/20 bg-zinc-900/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <span class="font-bold text-xl md:text-2xl tracking-widest text-amber-400 uppercase">
                P R E S T I G E • {{ $agencyName ?? 'Luxury Gold' }}
            </span>
            <button class="border border-amber-500 text-amber-400 hover:bg-amber-500 hover:text-zinc-950 px-6 py-2.5 rounded-none font-['Plus_Jakarta_Sans'] text-xs font-bold uppercase tracking-widest transition">
                Conciergerie Privée
            </button>
        </div>
    </header>

    <!-- Luxury Hero -->
    <section class="py-32 max-w-5xl mx-auto px-8 text-center space-y-8">
        <span class="text-xs font-['Plus_Jakarta_Sans'] font-bold uppercase tracking-widest text-amber-500 block">
            Privilège & Patrimoine High-Net-Worth
        </span>
        <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight">
            L'Excellence d'une Couverture Privée Sur-Mesure
        </h1>
        <p class="text-zinc-400 font-['Plus_Jakarta_Sans'] text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
            Résidences d'exception, véhicules de prestige, yachts, œuvres d'art et protection intégrale de patrimoine privé.
        </p>
        <div class="pt-4 font-['Plus_Jakarta_Sans'] flex justify-center">
            <button class="bg-amber-500 text-zinc-950 font-bold text-xs uppercase tracking-widest px-10 py-4 shadow-2xl hover:bg-amber-400 transition">
                Demander un Entretien Privé
            </button>
        </div>
    </section>

    <!-- VIP Services -->
    <section class="py-24 border-t border-amber-500/20 bg-zinc-900/50">
        <div class="max-w-6xl mx-auto px-8 grid md:grid-cols-3 gap-8 text-center font-['Plus_Jakarta_Sans']">
            <div class="p-8 border border-amber-500/20 rounded-none bg-zinc-900 space-y-4">
                <span class="text-2xl text-amber-400">🏛️</span>
                <h3 class="font-serif text-lg font-bold text-amber-300">Immobilier d'Exception</h3>
                <p class="text-xs text-zinc-400 leading-relaxed">Couverture d'actifs d'architecture et villas de luxe au Maroc.</p>
            </div>
            <div class="p-8 border border-amber-500/20 rounded-none bg-zinc-900 space-y-4">
                <span class="text-2xl text-amber-400">🏎️</span>
                <h3 class="font-serif text-lg font-bold text-amber-300">Supercars & Collection</h3>
                <p class="text-xs text-zinc-400 leading-relaxed">Garantie sur-mesure pour véhicules de sport et d'exception.</p>
            </div>
            <div class="p-8 border border-amber-500/20 rounded-none bg-zinc-900 space-y-4">
                <span class="text-2xl text-amber-400">💎</span>
                <h3 class="font-serif text-lg font-bold text-amber-300">Œuvres & Bijoux</h3>
                <p class="text-xs text-zinc-400 leading-relaxed">Expertise internationale et assurance objets d'art précieux.</p>
            </div>
        </div>
    </section>

    <!-- Prestige Footer -->
    <footer class="py-12 border-t border-amber-500/20 text-center font-['Plus_Jakarta_Sans'] text-xs text-zinc-500">
        <span>© {{ date('Y') }} {{ $agencyName ?? 'Luxury Gold' }}. Tous droits réservés.</span>
    </footer>

</body>
</html>
