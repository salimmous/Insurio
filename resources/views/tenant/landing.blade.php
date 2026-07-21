<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ tenant('name') }} | Assurances & Conseils</title>
    <!-- Styles & Local Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen selection:bg-teal-500 selection:text-slate-950 overflow-x-hidden">
    <!-- Background Gradients -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[600px] pointer-events-none overflow-hidden opacity-30">
        <div class="absolute top-[-10%] left-[-20%] w-[600px] h-[600px] rounded-full bg-teal-500/30 blur-[120px]"></div>
        <div class="absolute top-[20%] right-[-20%] w-[500px] h-[500px] rounded-full bg-indigo-500/20 blur-[100px]"></div>
    </div>

    <!-- Navigation Header -->
    <header class="border-b border-slate-900/80 backdrop-blur-md sticky top-0 z-50 bg-slate-950/80">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-indigo-600 flex items-center justify-center font-bold text-white shadow-lg shadow-teal-500/20">
                    {{ substr(tenant('name'), 0, 1) }}
                </div>
                <div>
                    <span class="font-extrabold text-xl bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                        {{ tenant('name') }}
                    </span>
                    <span class="block text-[10px] text-teal-400 font-bold uppercase tracking-widest -mt-1">Partenaire Assurance</span>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="#services" class="text-sm font-medium text-slate-400 hover:text-white transition-colors hidden md:block">Nos Services</a>
                <a href="#about" class="text-sm font-medium text-slate-400 hover:text-white transition-colors hidden md:block">À Propos</a>
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-semibold text-xs px-5 py-2.5 rounded-xl transition-all shadow-lg shadow-teal-500/10 hover:shadow-teal-500/20 flex items-center gap-2">
                    Espace Agent 🔑
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 pt-20 pb-32 grid md:grid-cols-12 gap-12 items-center relative">
        <div class="md:col-span-7 space-y-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-semibold">
                <span>🛡️ Assurances professionnelles au Maroc</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight">
                Protégez ce qui compte le plus avec <span class="bg-gradient-to-r from-teal-400 to-indigo-400 bg-clip-text text-transparent">{{ tenant('name') }}</span>
            </h1>
            
            <p class="text-slate-400 text-lg max-w-xl">
                Une agence moderne, réactive et à votre écoute pour vous proposer les meilleures solutions d'assurance adaptées à votre vie et votre entreprise.
            </p>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <a href="#services" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-white font-semibold text-sm px-8 py-4 rounded-2xl text-center transition-all">
                    Nos Services
                </a>
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-teal-500 to-indigo-600 hover:from-teal-600 hover:to-indigo-700 text-white font-semibold text-sm px-8 py-4 rounded-2xl text-center transition-all shadow-lg shadow-teal-500/15">
                    Accéder à mon Espace 🔑
                </a>
            </div>
        </div>
        
        <div class="md:col-span-5 relative">
            <div class="absolute inset-0 bg-gradient-to-tr from-teal-500/10 to-indigo-500/10 rounded-3xl blur-2xl"></div>
            <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/80 p-8 rounded-3xl relative space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 font-bold text-xl">
                    ✓
                </div>
                <h3 class="text-xl font-bold text-white">Simulation Instantanée</h3>
                <p class="text-slate-400 text-sm">
                    Nos conseillers étudient votre dossier sous 2 heures et vous proposent le meilleur tarif du marché pour votre véhicule ou entreprise.
                </p>
                <div class="border-t border-slate-800 pt-4 flex justify-between items-center text-xs text-slate-500">
                    <span>Traitement Rapide</span>
                    <span>100% Sécurisé</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="services" class="border-t border-slate-900/60 bg-slate-950 py-24">
        <div class="max-w-7xl mx-auto px-6 space-y-16">
            <div class="text-center space-y-4 max-w-2xl mx-auto">
                <h2 class="text-3xl font-extrabold text-white">Nos Solutions d'Assurance</h2>
                <p class="text-slate-400 text-sm">
                    Des formules complètes et flexibles pour couvrir l'ensemble de vos besoins quotidiens et professionnels.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-slate-900/40 border border-slate-900 hover:border-teal-500/20 p-8 rounded-3xl transition-all group space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center text-teal-400 group-hover:scale-110 transition-transform">
                        🚗
                    </div>
                    <h3 class="text-xl font-bold text-white">Assurance Automobile</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Tiers, Collision ou Tous Risques. Bénéficiez d'une assistance routière 24h/24 et 7j/7 en cas d'accident ou de panne partout au Maroc.
                    </p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-slate-900/40 border border-slate-900 hover:border-indigo-500/20 p-8 rounded-3xl transition-all group space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                        🏠
                    </div>
                    <h3 class="text-xl font-bold text-white">Multirisque Habitation</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Protégez votre foyer et vos biens contre les dégâts des eaux, incendies, vols et catastrophes naturelles en toute sérénité.
                    </p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-slate-900/40 border border-slate-900 hover:border-purple-500/20 p-8 rounded-3xl transition-all group space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                        💼
                    </div>
                    <h3 class="text-xl font-bold text-white">Assurance Entreprise</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        RC Professionnelle, couverture des locaux, flotte automobile et prévoyance collective pour pérenniser l'activité de vos équipes.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-900/80 bg-slate-950 py-12 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-6 space-y-4">
            <p>&copy; {{ date('Y') }} {{ tenant('name') }}. Tous droits réservés.</p>
            <p>Propulsé par la plateforme centralisée <a href="http://sc7mosa1422.universe.wf" class="text-teal-400 hover:underline">Insurio</a>.</p>
        </div>
    </footer>
</body>
</html>
