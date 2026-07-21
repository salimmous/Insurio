<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#0F172A]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accès Suspendu - Insurio</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;650;700;800&display=swap" rel="stylesheet">
    <!-- Styles & Local Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at 50% 50%, #1e1b4b 0%, #0f172a 100%);
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 text-white overflow-hidden">
    <div class="relative w-full max-w-lg">
        <!-- Background decorative glows -->
        <div class="absolute -top-24 -left-20 w-72 h-72 bg-indigo-600 rounded-full blur-[100px] opacity-30"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-rose-600 rounded-full blur-[100px] opacity-20"></div>

        <!-- Glassmorphism Card -->
        <div class="relative backdrop-blur-xl bg-slate-900/60 border border-slate-700/50 rounded-3xl p-8 md:p-10 shadow-2xl text-center space-y-6">
            <!-- Warning / Lock Icon Wrapper -->
            <div class="mx-auto h-20 w-20 rounded-2xl bg-amber-500/10 border border-amber-500/25 flex items-center justify-center text-amber-500 shadow-inner">
                <svg class="h-10 w-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <!-- Content Header -->
            <div class="space-y-2">
                <span class="text-[11px] font-extrabold uppercase tracking-widest text-amber-500 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20">Cabinet Temporairement Suspendu</span>
                <h1 class="text-2xl font-extrabold tracking-tight text-white mt-4">Accès Restreint</h1>
                <p class="text-sm text-slate-400 mt-2">
                    L'accès aux services de l'agence <span class="font-bold text-slate-200">{{ $tenant->name }}</span> est indisponible.
                </p>
            </div>

            <!-- Details Block -->
            <div class="bg-slate-950/40 border border-slate-800 rounded-2xl p-4 text-left text-xs space-y-3 font-mono">
                <div class="flex justify-between border-b border-slate-800/60 pb-2">
                    <span class="text-slate-500">Identifiant Unique :</span>
                    <span class="text-slate-350">{{ $tenant->id }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/60 pb-2">
                    <span class="text-slate-500">Statut d'abonnement :</span>
                    @if($tenant->status === 'suspended')
                        <span class="text-rose-400 font-bold uppercase">Suspendu Manuellement</span>
                    @else
                        <span class="text-amber-400 font-bold uppercase">Abonnement Expiré</span>
                    @endif
                </div>
                @if($tenant->subscription_end_date)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Date d'échéance :</span>
                        <span class="text-slate-350">{{ \Carbon\Carbon::parse($tenant->subscription_end_date)->format('d/m/Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Instructions / Actions -->
            <p class="text-xs text-slate-450 leading-relaxed max-w-sm mx-auto">
                Veuillez régulariser votre abonnement ou contacter votre administrateur système central pour restaurer l'accès à vos données.
            </p>

            <!-- Support Contact Link -->
            <div class="pt-4 border-t border-slate-800/60">
                <a href="mailto:support@insurio.com" class="inline-flex items-center justify-center w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm px-6 py-3 rounded-xl shadow-lg shadow-indigo-950/50 transition-all hover:scale-[1.02]">
                    Contacter le support client
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] text-slate-600 mt-6 font-mono">
            &copy; 2026 Insurio. Tous droits réservés.
        </p>
    </div>
</body>
</html>
