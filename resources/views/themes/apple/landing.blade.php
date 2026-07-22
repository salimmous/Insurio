<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Apple Insurance' }} | Minimalist Protection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white text-slate-900 tracking-tight selection:bg-slate-900 selection:text-white">

    <!-- Minimal Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="font-extrabold text-xl text-slate-900 tracking-tighter">{{ $agencyName ?? 'Apple Insurance' }}</span>
            <div class="flex items-center gap-6 text-xs font-semibold text-slate-600">
                <a href="#vision" class="hover:text-slate-900 transition">Vision</a>
                <a href="#offres" class="hover:text-slate-900 transition">Offres</a>
                <button class="bg-slate-900 text-white px-5 py-2 rounded-full font-bold text-xs shadow-xs hover:bg-slate-800 transition">
                    Simuler Tarif
                </button>
            </div>
        </div>
    </header>

    <!-- Ultra Large Minimal Hero -->
    <section class="py-36 text-center max-w-4xl mx-auto px-6 space-y-8">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Insurio Minimal Engine</span>
        <h1 class="text-5xl md:text-7xl font-black text-slate-900 leading-none tracking-tight">
            L'assurance. Redéfinie.
        </h1>
        <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed font-normal">
            Une clarté absolue. Sans paperasse inutile. Tout est pensé pour vous offrir la sérénité parfaite.
        </p>
        <div class="pt-4 flex justify-center gap-4">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-full text-xs transition shadow-md">
                Découvrir les offres ➔
            </button>
        </div>
    </section>

    <!-- Large Visual Grid -->
    <section id="offres" class="py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-8">
            <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-xs space-y-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Auto & Mobilité</span>
                <h3 class="text-3xl font-black text-slate-900">Protection Véhicule Intelligente</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Assistance 0 km et véhicule de remplacement livré à domicile sous 45 minutes.</p>
            </div>
            <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-xs space-y-6">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Habitation & Espace</span>
                <h3 class="text-3xl font-black text-slate-900">Sérénité Domicile Globale</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Couverture dégâts des eaux, vol et bris de glace sans franchise dissimulée.</p>
            </div>
        </div>
    </section>

    <!-- Minimal Footer -->
    <footer class="py-12 border-t border-slate-100 text-center text-xs text-slate-400 font-medium">
        <span>© {{ date('Y') }} {{ $agencyName ?? 'Apple Insurance' }}. Tous droits réservés.</span>
    </footer>

</body>
</html>
