<!-- THEME 02: APPLE INSURANCE (PURE WHITE, MINIMAL) -->
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | Pure Minimal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white text-slate-900 min-h-screen">
    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="font-extrabold text-xl text-slate-900 tracking-tight">{{ $agencyName }}</span>
            <button @click="quoteModal = true" class="bg-slate-900 text-white font-medium text-xs px-5 py-2 rounded-full">Obtenir Devis</button>
        </div>
    </header>

    <section class="py-36 text-center max-w-4xl mx-auto px-6 space-y-8">
        <span class="text-xs font-semibold uppercase tracking-widest text-slate-400">Pure Protection</span>
        <h1 class="text-6xl md:text-8xl font-black tracking-tighter text-slate-900 leading-none">L'assurance. Épurée.</h1>
        <p class="text-slate-500 text-xl font-normal max-w-xl mx-auto leading-relaxed">Tout ce dont vous avez besoin. Rien d'inutile. Une expérience conçue pour votre sérénité.</p>
        <div class="pt-6">
            <button @click="quoteModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-9 py-4 rounded-full text-sm shadow-xl transition">Simuler mon tarif ➔</button>
        </div>
    </section>
</body>
</html>
