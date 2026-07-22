<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Wafa Inspire' }} | Assurance Famille & Sérénité</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-emerald-50/30 text-emerald-950 selection:bg-emerald-600 selection:text-white">

    <!-- Wafa Header -->
    <header class="bg-white border-b border-emerald-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 flex items-center justify-center font-black text-white text-lg shadow-md">W</div>
                <div>
                    <span class="font-extrabold text-xl text-emerald-950 tracking-tight block">{{ $agencyName ?? 'Wafa Inspire' }}</span>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-600 block -mt-0.5">Votre Partenaire Sérénité au Maroc</span>
                </div>
            </div>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-3 rounded-2xl shadow-md transition">
                Espace Famille ➔
            </button>
        </div>
    </header>

    <!-- Wafa Hero -->
    <section class="py-24 bg-gradient-to-b from-emerald-50 via-white to-white border-b border-emerald-100">
        <div class="max-w-6xl mx-auto px-6 text-center space-y-6">
            <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                Assurance & Prévoyance Famille Maroc
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-emerald-950 leading-tight tracking-tight">
                Protéger Ceux Que Vous Aimez, Chaque Jour
            </h1>
            <p class="text-emerald-800 text-base max-w-xl mx-auto font-medium leading-relaxed">
                Offres d'assurance automobile, habitation et santé spécialement conçues pour les familles marocaines avec assistance immédiate.
            </p>
            <div class="pt-2 flex justify-center gap-4">
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-8 py-4 rounded-2xl shadow-lg transition">
                    Simuler mon tarif famille ➔
                </button>
            </div>
        </div>
    </section>

    <!-- Wafa Family Products -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            <div class="bg-emerald-50/50 p-8 rounded-3xl border border-emerald-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl">🚗</div>
                <h3 class="font-extrabold text-lg text-emerald-950">Auto Famille</h3>
                <p class="text-xs text-emerald-800 leading-relaxed font-medium">Assistance panne 0 km et véhicule de prêt grand format.</p>
            </div>
            <div class="bg-emerald-50/50 p-8 rounded-3xl border border-emerald-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl">🏡</div>
                <h3 class="font-extrabold text-lg text-emerald-950">Foyer Protégé</h3>
                <p class="text-xs text-emerald-800 leading-relaxed font-medium">Couverture contre le vol, l'incendie et les dégâts des eaux.</p>
            </div>
            <div class="bg-emerald-50/50 p-8 rounded-3xl border border-emerald-100 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xl">❤️</div>
                <h3 class="font-extrabold text-lg text-emerald-950">Santé Enfants</h3>
                <p class="text-xs text-emerald-800 leading-relaxed font-medium">Prise en charge des soins dentaires, optiques et consultations.</p>
            </div>
        </div>
    </section>

    <footer class="py-12 bg-emerald-950 text-emerald-300 text-xs text-center border-t border-emerald-900">
        <span>© {{ date('Y') }} {{ $agencyName ?? 'Wafa Inspire' }}. Tous droits réservés.</span>
    </footer>

</body>
</html>
