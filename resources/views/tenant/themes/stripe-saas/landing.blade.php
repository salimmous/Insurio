<!-- THEME: STRIPE SAAS (MODERN TECH SAAS STYLE) -->
<!DOCTYPE html>
<html lang="fr" x-data="{ lang: 'fr', quoteModal: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName }} | SaaS Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-md">S</div>
                <span class="font-black text-xl text-slate-900 tracking-tight">{{ $agencyName }}</span>
            </div>
            <button @click="quoteModal = true" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md">API & Subscriptions ⚡</button>
        </div>
    </header>

    <section class="py-24 max-w-5xl mx-auto px-8 text-center space-y-8">
        <span class="px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-xs font-extrabold">Insurtech API Platform v2.0</span>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">L'infrastructure financière d'assurance pour entreprises modernisées</h1>
        <p class="text-slate-600 text-base max-w-2xl mx-auto leading-relaxed">Souscription automatisée par API, attestations numériques instantanées et gestion multi-tenant.</p>
        <button @click="quoteModal = true" class="bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg">Créer un compte de test ➔</button>
    </section>
</body>
</html>
