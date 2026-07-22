<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bento UI Insurance | Asymmetrical Grid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-zinc-100 text-zinc-900 p-4 lg:p-8 selection:bg-indigo-600 selection:text-white">

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Top Bento Navigation -->
        <header class="bg-white border border-zinc-200 p-4 rounded-3xl flex justify-between items-center shadow-xs">
            <span class="font-black text-xl tracking-tight text-zinc-900">BENTO • INSURE</span>
            <button class="bg-zinc-900 text-white font-bold text-xs px-6 py-3 rounded-2xl shadow-sm hover:bg-zinc-800 transition">
                Simuler Tarif Bento ➔
            </button>
        </header>

        <!-- Asymmetrical Bento Grid Hero -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <!-- Main Hero Box (Col Span 8) -->
            <div class="md:col-span-8 bg-white border border-zinc-200 p-10 lg:p-14 rounded-3xl shadow-xs flex flex-col justify-between space-y-8">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-600">Bento Grid Layout Architecture</span>
                <h1 class="text-4xl lg:text-6xl font-black text-zinc-900 tracking-tight leading-tight">
                    L'Assurance Structurée en Boîtes Bento Modulaires.
                </h1>
                <p class="text-zinc-500 text-sm max-w-xl leading-relaxed font-medium">
                    Chaque couverture est un bloc autonome. Visualisez vos garanties en un coup d'œil grâce au système bento.
                </p>
                <div class="pt-4 flex gap-4">
                    <button class="bg-indigo-600 text-white font-bold text-xs px-8 py-4 rounded-2xl shadow-md hover:bg-indigo-700 transition">
                        Composer ma Bento Box ➔
                    </button>
                </div>
            </div>

            <!-- Side Card 1 (Col Span 4) -->
            <div class="md:col-span-4 bg-gradient-to-br from-indigo-600 to-indigo-800 text-white p-8 rounded-3xl flex flex-col justify-between space-y-6 shadow-md">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-200">Score de Risque</span>
                <div class="text-6xl font-black">98.4<span class="text-2xl text-indigo-300">%</span></div>
                <p class="text-xs text-indigo-100 font-medium">Taux d'approbation instantané des dossiers de remboursement.</p>
            </div>

            <!-- Row 2 Bento Items -->
            <div class="md:col-span-4 bg-white border border-zinc-200 p-8 rounded-3xl space-y-4 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 flex items-center justify-center font-bold text-xl">🚗</div>
                <h3 class="font-extrabold text-lg">Auto Connectée</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Télématique et suivi des trajets via capteur smartphone.</p>
            </div>

            <div class="md:col-span-4 bg-white border border-zinc-200 p-8 rounded-3xl space-y-4 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 flex items-center justify-center font-bold text-xl">🏡</div>
                <h3 class="font-extrabold text-lg">Habitation IoT</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Capteurs anti-fuite d'eau intelligents et alerte incendie.</p>
            </div>

            <div class="md:col-span-4 bg-white border border-zinc-200 p-8 rounded-3xl space-y-4 shadow-xs">
                <div class="w-12 h-12 rounded-2xl bg-zinc-100 flex items-center justify-center font-bold text-xl">🩺</div>
                <h3 class="font-extrabold text-lg">Santé Tiers-Payant</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Remboursements instantanés sur votre compte bancaire.</p>
            </div>
        </div>

        <footer class="bg-white border border-zinc-200 p-6 rounded-3xl text-center text-xs font-medium text-zinc-400">
            © {{ date('Y') }} {{ $agencyName ?? 'Bento Insure' }}. Tous droits réservés.
        </footer>
    </div>

</body>
</html>
