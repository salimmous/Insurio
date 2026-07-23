<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Insurio') }} | Enterprise Insurance Management Platform</title>

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
            
            <!-- LEFT PANEL: 50% Private Enterprise SaaS Showcase (Hidden on Mobile) -->
            <div class="hidden lg:flex lg:col-span-6 relative bg-slate-950 p-12 lg:p-16 flex-col justify-between overflow-hidden border-r border-slate-800">
                <!-- Ambient Dark Background & Mesh Gradients -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-teal-500/15 rounded-full blur-3xl"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b15_1px,transparent_1px),linear-gradient(to_bottom,#1e293b15_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
                </div>

                <!-- Top Left Insurio Branding -->
                <div class="relative z-10 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-xl shadow-indigo-600/30 border border-indigo-400/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-white tracking-widest block uppercase font-mono">INSURIO</span>
                            <span class="text-[10px] font-mono font-bold text-indigo-400 uppercase tracking-widest block -mt-0.5">Enterprise Insurance Management Platform</span>
                        </div>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-xs font-mono font-semibold text-slate-300">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Secure Multi-Tenant SaaS for Insurance Agencies</span>
                    </div>
                </div>

                <!-- Middle Content: Premium Dashboard Mockup & Features Checklist -->
                <div class="relative z-10 my-auto py-8 space-y-8 max-w-xl">
                    
                    <!-- Dashboard Mockup Card -->
                    <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 shadow-2xl space-y-5">
                        <div class="flex items-center justify-between border-b border-slate-800 pb-3.5 font-mono text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-500/80 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-500/80 inline-block"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                                <span class="ml-2 font-bold text-slate-300">Insurio ERP • Grand Livre & Caisse</span>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-indigo-500/20 text-indigo-300 text-[10px] font-bold">PROD ACTIVE</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs font-mono">
                            <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-sans uppercase font-bold">SOLDE CAISSE DU JOUR</span>
                                <span class="text-lg font-black text-emerald-400 block">45,850.00 DH</span>
                                <span class="text-[9px] text-slate-500 block">✓ Clôture Journalière Validée</span>
                            </div>
                            <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800/80 space-y-1">
                                <span class="text-[10px] text-slate-400 block font-sans uppercase font-bold">CONTRATS DU MOIS</span>
                                <span class="text-lg font-black text-indigo-400 block">128 Actifs</span>
                                <span class="text-[9px] text-slate-500 block">✓ Grand Livre Équilibré</span>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-950 rounded-2xl border border-slate-800/80 flex items-center justify-between font-mono text-xs">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold">🛡️</span>
                                <div>
                                    <span class="text-slate-200 font-bold block text-xs">Sécurité Multi-Tenant & 2FA TOTP</span>
                                    <span class="text-[10px] text-slate-400 block">Isolation complète des données agence</span>
                                </div>
                            </div>
                            <span class="text-emerald-400 font-bold text-[10px] px-2 py-1 bg-emerald-500/10 rounded-lg border border-emerald-500/20">VERIFIED</span>
                        </div>
                    </div>

                    <!-- Enterprise Features Checklist -->
                    <div class="grid grid-cols-2 gap-x-6 gap-y-2.5 text-xs font-medium text-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Multi-Tenant Architecture</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>General Ledger</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Cash Closing</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Claims Management</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>CRM</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Website Builder</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Document Management</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Secure 2FA</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>ACAPS Ready</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Cloud Backup</span>
                        </div>
                        <div class="flex items-center gap-2 col-span-2">
                            <span class="text-emerald-400 font-black">✓</span>
                            <span>Enterprise Security</span>
                        </div>
                    </div>

                </div>

                <!-- Bottom Footer -->
                <div class="relative z-10 pt-6 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-500 font-mono">
                    <span>Accès privé réservé aux agences invitées par le Super Admin.</span>
                    <span>Insurio v2.0</span>
                </div>
            </div>

            <!-- RIGHT PANEL: 50% Enterprise Form Container -->
            <div class="col-span-12 lg:col-span-6 flex flex-col justify-between p-8 sm:p-12 lg:p-16 xl:p-20 bg-slate-50 dark:bg-slate-950 overflow-y-auto">
                
                <!-- Mobile Brand Header (Hidden on LG screens) -->
                <div class="lg:hidden mb-8 text-center space-y-2">
                    <div class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-widest font-mono">INSURIO</span>
                    </div>
                </div>

                <!-- Form Content Slot Container -->
                <div class="w-full max-w-md mx-auto my-auto space-y-6 py-6">
                    {{ $slot }}
                </div>

                <!-- Right Footer Disclaimers -->
                <div class="pt-8 text-center text-xs text-slate-400 dark:text-slate-500 font-medium space-y-2">
                    <p>© {{ date('Y') }} Insurio SaaS. Plateforme d'Assurance Enterprise.</p>
                    <p class="text-[11px] text-slate-500">Pour toute création de compte agence, contactez l'administrateur système.</p>
                </div>
            </div>

        </div>

    </body>
</html>
