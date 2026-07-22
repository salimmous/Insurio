<?php
    if (isset($previewTheme) && $previewTheme) {
        $theme = $previewTheme;
        $agencyName = 'Agence Exemple - ' . $theme->name;
    } else {
        $config = \App\Models\TenantWebsiteConfig::first();
        $theme = null;
        if ($config && !empty($config->theme_id)) {
            $theme = \App\Models\WebsiteTheme::find($config->theme_id);
        }
        if (!$theme) {
            $theme = \App\Models\WebsiteTheme::first();
        }
        $agencyName = tenant('name') ?? 'Insurio Agency';
    }

    $slug = $theme->slug ?? 'corporate-blue';
    $viewPath = "tenant.themes.{$slug}.landing";
?>

@if(view()->exists($viewPath))
    @include($viewPath)
@else
    <!-- Fallback Handcrafted Light-First Insurance View -->
    <!DOCTYPE html>
    <html lang="fr" x-data="{ lang: 'fr', quoteModal: false }">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $agencyName }} | Assurances au Maroc</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body class="bg-white text-slate-900 min-h-screen font-sans">
        <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <span class="font-extrabold text-xl text-slate-900">{{ $agencyName }}</span>
                <button @click="quoteModal = true" class="bg-indigo-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl">Demander Devis</button>
            </div>
        </header>
        <section class="py-24 max-w-5xl mx-auto px-6 text-center space-y-6">
            <h1 class="text-4xl font-extrabold text-slate-900">Assurance & Conseil avec {{ $agencyName }}</h1>
            <p class="text-slate-600 text-sm max-w-xl mx-auto">Solutions complètes pour Automobile, Habitation, Santé et Entreprises au Maroc.</p>
            <button @click="quoteModal = true" class="bg-indigo-600 text-white font-bold text-xs px-8 py-3.5 rounded-xl">Lancer simulation ➔</button>
        </section>
    </body>
    </html>
@endif
