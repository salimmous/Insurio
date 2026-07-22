<!-- THEME: WAFA INSPIRE (EMERALD GREEN & FAMILY FOCUS) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $agencyName ?? 'Wafa Inspire' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-emerald-900 text-emerald-950 font-['Plus_Jakarta_Sans']">

    <header class="bg-white border-b border-emerald-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-600 flex items-center justify-center font-bold text-white">W</div>
                <span class="font-extrabold text-xl text-emerald-950 tracking-tight">{{ $agencyName ?? 'Wafa Assurance' }}</span>
            </div>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-5 py-2.5 rounded-2xl shadow-md">Espace Famille</button>
        </div>
    </header>

    <section class="py-24 bg-gradient-to-b from-emerald-50 to-white border-b border-emerald-100">
        <div class="max-w-6xl mx-auto px-6 text-center space-y-6">
            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">Assurance Sérénité Famille</span>
            <h1 class="text-4xl md:text-6xl font-black text-emerald-950 leading-tight">Protéger Ceux Que Vous Aimez, Chaque Jour</h1>
            <p class="text-emerald-800 text-base max-w-xl mx-auto font-medium">Offres auto, habitation et santé adaptées aux familles marocaines avec assistance immédiate.</p>
            <button class="bg-emerald-600 text-white font-bold text-xs px-8 py-4 rounded-2xl shadow-lg">Simuler mon tarif famille ➔</button>
        </div>
    </section>

</body>
</html>
