<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $agencyName = (function_exists('tenant') && tenant() && tenant('name')) 
                ? tenant('name') 
                : (\App\Models\Setting::get('agency_name') ?: 'Espace Agence Agréée');
            
            $agencyLogo = (function_exists('tenant') && tenant() && tenant('logo_path')) 
                ? asset('storage/' . tenant('logo_path')) 
                : null;
        @endphp

        <title>{{ $agencyName }} | Espace d'Administration Agence</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />

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
    <body class="h-full font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500 selection:text-white">

        <div class="min-h-screen grid lg:grid-cols-12 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100">
            
            <!-- LEFT PANEL: 50% Agency Workspace Showcase (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:col-span-6 relative bg-slate-950 p-12 lg:p-16 flex-col justify-between overflow-hidden border-r border-slate-800">
                
                <!-- High-Resolution Stock Background Image with Subtle Dark Overlay -->
                <div class="absolute inset-0 z-0">
                    <img src="{{ \App\Helpers\StockImageHelper::url('happy_family_advisor') }}" 
                         alt="{{ $agencyName }} Workspace" 
                         class="w-full h-full object-cover opacity-20 filter saturate-125 transform scale-105 transition-all duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/85 to-slate-950/50"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-indigo-600/20 via-transparent to-transparent"></div>
                </div>

                <!-- TOP LEFT: PRIMARY BRANDING = AGENCY IDENTITY -->
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center gap-3.5">
                        @if($agencyLogo)
                            <div class="p-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xl">
                                <img src="{{ $agencyLogo }}" alt="{{ $agencyName }}" class="h-10 w-auto object-contain">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-xl shadow-indigo-600/30 border border-indigo-400/30 text-white font-black text-xl">
                                {{ strtoupper(substr($agencyName, 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <span class="text-2xl font-black text-white tracking-tight block">{{ $agencyName }}</span>
                            <span class="text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest block -mt-0.5">
                                Espace de Gestion d'Agence Agréée
                            </span>
                        </div>
                    </div>

                    <!-- SECONDARY BRANDING -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900/90 border border-slate-800 text-xs font-mono text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Propulsé par Insurio SaaS</span>
                    </div>
                </div>

                <!-- MIDDLE CONTENT: AGENCY DASHBOARD & WORKSPACE PREVIEW -->
                <div class="relative z-10 my-auto py-8 space-y-8 max-w-xl">
                    
                    <div class="space-y-3">
                        <h1 class="text-3xl lg:text-4xl font-black text-white leading-tight tracking-tight">
                            Bienvenue sur Votre Portail <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-300 to-cyan-400">
                                d'Administration Agence.
                            </span>
                        </h1>
                        <p class="text-slate-300 text-sm leading-relaxed">
                            Accédez à la gestion centralisée de vos polices d'assurance, suivi du portefeuille clients, arrêtés de caisse et émission de contrats.
                        </p>
                    </div>

                    <!-- Realistic Agency Workspace Preview Card -->
                    <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3 font-mono text-xs">
                            <div class="flex items-center gap-2 text-slate-300 font-bold">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                <span>Tableau de Bord Agence</span>
                            </div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">Actif • Session Sécurisée</span>
                        </div>

                        <!-- Agency Metrics Grid -->
                        <div class="grid grid-cols-2 gap-3 text-xs font-mono">
                            <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-sans font-bold uppercase">PORTEFEUILLE CLIENTS</span>
                                <span class="text-base font-black text-white block">Gestion & Suivi CRM</span>
                                <span class="text-[9px] text-emerald-400 block">✓ Fiches & Historique Sinistres</span>
                            </div>
                            <div class="p-3.5 bg-slate-950 rounded-2xl border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-sans font-bold uppercase">CONTRATS & PRÉFECTURE</span>
                                <span class="text-base font-black text-indigo-400 block">Auto, Habitation, Santé</span>
                                <span class="text-[9px] text-slate-500 block">✓ Émission & Attestations</span>
                            </div>
                        </div>

                        <!-- Agency Website & Services Preview Bar -->
                        <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800/80 flex items-center justify-between text-xs font-mono">
                            <div class="flex items-center gap-2.5">
                                <span class="text-lg">🏢</span>
                                <div>
                                    <span class="text-slate-200 font-bold block text-xs">Site Web & Devis en Ligne Agence</span>
                                    <span class="text-[10px] text-slate-400 block">Vitrine digitale personnalisée & simulateurs</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-indigo-400">En Ligne ➔</span>
                        </div>
                    </div>

                </div>

                <!-- BOTTOM OF LEFT PANEL: STRICTLY SPECIFIED FOOTER -->
                <div class="relative z-10 pt-6 border-t border-slate-800/80 grid grid-cols-2 gap-4 text-xs font-mono text-slate-400">
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-400 font-bold">⚡</span>
                        <span class="font-bold text-slate-300">Powered by Insurio</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 font-bold">🏢</span>
                        <span>Secure Multi-Tenant SaaS</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-400 font-bold">🔒</span>
                        <span>Enterprise Security</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-400 font-bold">🛡️</span>
                        <span>2FA Protected</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT PANEL: 50% Enterprise Authentication Form Container -->
            <div class="col-span-12 lg:col-span-6 flex flex-col justify-between p-6 sm:p-10 lg:p-12 xl:p-16 bg-slate-950 text-slate-100 overflow-y-auto min-h-screen">
                
                <!-- Mobile Brand Header (Hidden on LG screens) -->
                <div class="lg:hidden mb-6 text-center space-y-2">
                    <div class="inline-flex items-center gap-3">
                        @if($agencyLogo)
                            <img src="{{ $agencyLogo }}" alt="{{ $agencyName }}" class="h-9 w-auto object-contain">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg text-white font-black">
                                {{ strtoupper(substr($agencyName, 0, 1)) }}
                            </div>
                        @endif
                        <div class="text-left">
                            <span class="text-lg font-black text-white tracking-tight block">{{ $agencyName }}</span>
                            <span class="text-[9px] font-mono text-indigo-400 block -mt-1">Powered by Insurio</span>
                        </div>
                    </div>
                </div>

                <!-- Form Content Slot Container -->
                <div class="w-full max-w-xl mx-auto my-auto py-6">
                    {{ $slot }}
                </div>

                <!-- Right Footer Disclaimers -->
                <div class="pt-6 text-center text-xs text-slate-500 font-medium space-y-1">
                    <p>© {{ date('Y') }} {{ $agencyName }}. Tous droits réservés.</p>
                    <p class="text-[11px] text-slate-400 font-mono">Plateforme d'Assurance Sécurisée • Propulsée par Insurio SaaS</p>
                </div>
            </div>

        </div>

    </body>
</html>
