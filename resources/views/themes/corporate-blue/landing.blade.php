<!-- THEME: CORPORATE BLUE (ALLIANZ INSPIRED) -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $agencyName ?? 'Corporate Insurance' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-slate-900 font-['Plus_Jakarta_Sans']">

    <!-- Topbar -->
    <div class="bg-blue-950 text-slate-300 text-xs py-2.5 px-8 flex justify-between items-center font-medium">
        <span>Corporate Insurance Line • Assistance: +212 5 22 00 00 00</span>
        <span>Casablanca Financial City</span>
    </div>

    <!-- Corporate Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-900 text-white font-extrabold flex items-center justify-center rounded-lg">C</div>
                <span class="font-extrabold text-xl tracking-tight text-blue-950">{{ $agencyName ?? 'Corporate Insurance' }}</span>
            </div>
            <nav class="flex items-center gap-8 text-xs font-bold text-slate-700">
                <a href="#audit" class="hover:text-blue-900">Audit Corporate</a>
                <a href="#flotte" class="hover:text-blue-900">Flottes Auto</a>
                <a href="#at" class="hover:text-blue-900">Accident du Travail</a>
                <button class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-xs font-bold">Devis Institutionnel</button>
            </nav>
        </div>
    </header>

    <!-- Corporate Hero -->
    <section class="py-24 bg-gradient-to-b from-blue-50/60 to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-12 gap-12 items-center">
            <div class="md:col-span-7 space-y-6">
                <span class="text-[11px] font-extrabold uppercase tracking-widest bg-blue-100 text-blue-900 px-3.5 py-1.5 rounded-md">Couverture Institutionnelle & Entreprise</span>
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">Solvabilité & Protection Globale pour vos Risques d'Entreprise</h1>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed">Solution certifiée conformité ACAPS pour les PME et grands groupes industriels au Maroc.</p>
                <button class="bg-blue-900 text-white font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-lg shadow-md">Lancer un Audit d'Assurance</button>
            </div>
            <div class="md:col-span-5 bg-white border border-slate-200 p-8 rounded-xl shadow-lg space-y-4">
                <h3 class="font-bold text-lg text-slate-900">Simulateur Risques Pro</h3>
                <div class="space-y-3 text-xs">
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-md flex justify-between font-bold">
                        <span>RC Professionnelle & Exploitation</span>
                        <span class="text-blue-900">Garantie 100M DH</span>
                    </div>
                </div>
                <button class="w-full bg-slate-900 text-white py-3 rounded-lg font-bold text-xs">Obtenir Offre Entreprise ➔</button>
            </div>
        </div>
    </section>

</body>
</html>
