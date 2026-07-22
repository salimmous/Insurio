<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Startup Insurance' }} | SaaS Tech Protection</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white selection:bg-indigo-500 selection:text-white">

    <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center font-black text-white">⚡</div>
                <span class="font-black text-xl text-white">{{ $agencyName ?? 'Startup Insurance' }}</span>
            </div>
            <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-lg">API & Devis Instantané</button>
        </div>
    </header>

    <section class="py-28 max-w-5xl mx-auto px-6 text-center space-y-6">
        <span class="px-4 py-1.5 bg-indigo-500/20 text-indigo-400 border border-indigo-500/40 text-xs font-bold rounded-full">Insurtech 2.0 • Digital Only</span>
        <h1 class="text-4xl md:text-6xl font-black text-white leading-tight">L'Assurance Digitale Conçue Pour les Startups & Scaleups</h1>
        <p class="text-slate-400 text-base max-w-xl mx-auto">RC Pro Tech, Cyber-Risques et D&O pour dirigeants souscrits en 5 minutes via API.</p>
        <button class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-xl shadow-xl">Simuler mon tarif Tech ➔</button>
    </section>

</body>
</html>
