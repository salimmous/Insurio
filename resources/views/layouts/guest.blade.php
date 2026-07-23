<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Insurio') }} | Portail d'Authentification Enterprise</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

        <!-- Favicon & Dynamic White-Label Theme Styling -->
        @if(function_exists('tenant') && tenant())
            <link rel="icon" type="image/x-icon" href="{{ tenant('favicon_path') ? asset('storage/' . tenant('favicon_path')) : asset('favicon.ico') }}">
            <style>
                :root {
                    --color-accent: {{ tenant('couleur_primaire') ?: '#4F46E5' }};
                    --color-accent-hover: {{ tenant('couleur_secondaire') ?: '#4338CA' }};
                }
            </style>
        @else
            <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
            <style>
                :root {
                    --color-accent: #4F46E5;
                    --color-accent-hover: #4338CA;
                }
            </style>
        @endif

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full font-sans antialiased bg-slate-900 text-slate-100 selection:bg-indigo-500 selection:text-white">

        <div class="min-h-screen grid lg:grid-cols-12 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100">
            
            <!-- LEFT SIDE: 50% Enterprise Split Showcase (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:col-span-6 relative bg-slate-950 p-12 lg:p-16 flex-col justify-between overflow-hidden border-r border-slate-800">
                <!-- Background Lifestyle Image with Gradient Overlay -->
                <div class="absolute inset-0 z-0">
                    <img src="https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1600&q=80" 
                         alt="Insurio Enterprise Insurance Platform" 
                         class="w-full h-full object-cover opacity-20 filter saturate-150 transform scale-105 transition-all duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-indigo-950/50"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-600/25 via-transparent to-transparent"></div>
                </div>

                <!-- Top Left Branding & Badges -->
                <div class="relative z-10 space-y-6">
                    <div class="flex items-center gap-3">
                        <a href="/" class="flex items-center gap-3.5 group">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-xl shadow-indigo-600/30 border border-indigo-400/30 group-hover:scale-105 transition-all">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-2xl font-black text-white tracking-tight block">Insurio</span>
                                <span class="text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest block -mt-1">Enterprise SaaS Platform</span>
                            </div>
                        </a>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Agrée ACAPS • Multi-Tenant Ledger Engine</span>
                    </div>
                </div>

                <!-- Middle Marketing Content -->
                <div class="relative z-10 my-auto py-12 space-y-8 max-w-xl">
                    <h1 class="text-3xl lg:text-4xl xl:text-5xl font-black text-white leading-tight tracking-tight">
                        La Plateforme Intelligente <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-cyan-400">De Gestion d'Assurance au Maroc.</span>
                    </h1>

                    <p class="text-slate-300 text-sm lg:text-base leading-relaxed font-normal">
                        Pilotez votre portefeuille de contrats, votre comptabilité générale (Grand Livre), vos clôtures de caisse journalières et votre sécurité 2FA TOTP hardened en toute simplicité.
                    </p>

                    <!-- Statistics Grid -->
                    <div class="grid grid-cols-3 gap-6 pt-4 border-t border-slate-800/80 font-mono">
                        <div>
                            <span class="text-2xl lg:text-3xl font-black text-white block">50,000+</span>
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Assurés Actifs</span>
                        </div>
                        <div>
                            <span class="text-2xl lg:text-3xl font-black text-emerald-400 block">150M DH</span>
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Primes Traitées</span>
                        </div>
                        <div>
                            <span class="text-2xl lg:text-3xl font-black text-indigo-400 block">99.98%</span>
                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Uptime Garanti</span>
                        </div>
                    </div>

                    <!-- Trust Card -->
                    <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 p-6 rounded-3xl space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-amber-400 text-sm">★★★★★</span>
                                <span class="text-xs font-bold text-slate-200">Recommandé par 500+ Agences</span>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">ISO 27001</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed font-medium italic">
                            « Insurio a transformé la traçabilité financière de notre réseau. Zéro écart de caisse et clôture en 5 minutes. »
                        </p>
                        <div class="text-[11px] font-bold text-slate-300">
                            — Direction du Réseau de Courtage, Casablanca
                        </div>
                    </div>
                </div>

                <!-- Bottom Security & Support Footer -->
                <div class="relative z-10 pt-6 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Sécurité Chiffrée 256-bit AES</span>
                    </div>
                    <div>
                        <span>Assistance 24/7: <strong class="text-white">+212 5 22 00 00 00</strong></span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: 50% Enterprise Form Container -->
            <div class="col-span-12 lg:col-span-6 flex flex-col justify-between p-8 sm:p-12 lg:p-16 xl:p-20 bg-slate-50 dark:bg-slate-950 overflow-y-auto">
                
                <!-- Mobile Brand Header (Hidden on LG screens) -->
                <div class="lg:hidden mb-8 text-center space-y-2">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Insurio</span>
                    </a>
                </div>

                <!-- Form Content Slot Container -->
                <div class="w-full max-w-md mx-auto my-auto space-y-6 py-6">
                    {{ $slot }}
                </div>

                <!-- Right Footer Disclaimers -->
                <div class="pt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-medium">
                    <p>© {{ date('Y') }} Insurio SaaS. Tous droits réservés. Agréé ACAPS Maroc.</p>
                    <div class="flex justify-center gap-4 mt-2">
                        <a href="#" class="hover:underline">Conditions d'utilisation</a>
                        <span>•</span>
                        <a href="#" class="hover:underline">Politique de confidentialité</a>
                        <span>•</span>
                        <a href="#" class="hover:underline">Sécurité 2FA</a>
                    </div>
                </div>
            </div>

        </div>

    </body>
</html>
