<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insurio Super Admin Console | Portail d'Authentification System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full font-sans antialiased bg-slate-950 text-slate-100 selection:bg-teal-500 selection:text-white">

    <div class="min-h-screen grid lg:grid-cols-12 bg-slate-950 text-slate-100">
        
        <!-- LEFT SIDE: 50% Super Admin Enterprise Showcase -->
        <div class="hidden lg:flex lg:col-span-6 relative bg-slate-950 p-12 lg:p-16 flex-col justify-between overflow-hidden border-r border-slate-800">
            <!-- Background Image with Dark Teal Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1600&q=80" 
                     alt="Insurio Super Admin Platform Infrastructure" 
                     class="w-full h-full object-cover opacity-15 filter saturate-150 transform scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/90 to-teal-950/40"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-teal-500/20 via-transparent to-transparent"></div>
            </div>

            <!-- Top Left Branding & Super Admin Status -->
            <div class="relative z-10 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center shadow-xl shadow-teal-600/30 border border-teal-400/30">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-white tracking-tight block">Insurio System</span>
                        <span class="text-[10px] font-mono font-bold text-teal-400 uppercase tracking-widest block -mt-1">Super Admin Console</span>
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-mono font-bold text-teal-300">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span>Multi-Tenant Infrastructure Control Plane</span>
                </div>
            </div>

            <!-- Middle Super Admin Content -->
            <div class="relative z-10 my-auto py-12 space-y-8 max-w-xl">
                <h1 class="text-3xl lg:text-4xl xl:text-5xl font-black text-white leading-tight tracking-tight">
                    Contrôle Central & Orchestration <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-emerald-300 to-cyan-400">Des Tenanted Agences.</span>
                </h1>

                <p class="text-slate-300 text-sm lg:text-base leading-relaxed font-normal">
                    Gestion des agences d'assurance, surveillance de l'infrastructure multi-bases, supervision du Grand Livre comptable et sécurité globale de la plateforme Insurio.
                </p>

                <!-- Statistics Grid -->
                <div class="grid grid-cols-3 gap-6 pt-4 border-t border-slate-800/80 font-mono">
                    <div>
                        <span class="text-2xl lg:text-3xl font-black text-white block">21</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">Thèmes Web</span>
                    </div>
                    <div>
                        <span class="text-2xl lg:text-3xl font-black text-teal-400 block">100%</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">TOTP Hardened</span>
                    </div>
                    <div>
                        <span class="text-2xl lg:text-3xl font-black text-emerald-400 block">Isolated</span>
                        <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider block mt-0.5">DB Per Tenant</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Security Footer -->
            <div class="relative z-10 pt-6 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400 font-medium">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Restreint aux Administrateurs Système</span>
                </div>
                <div>
                    <span>Insurio v2.0.0</span>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: 50% Super Admin Form Container -->
        <div class="col-span-12 lg:col-span-6 flex flex-col justify-between p-8 sm:p-12 lg:p-16 xl:p-20 bg-slate-950 overflow-y-auto">
            
            <div class="lg:hidden mb-8 text-center space-y-2">
                <div class="inline-flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center shadow-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tight">Super Admin Console</span>
                </div>
            </div>

            <!-- Form Content -->
            <div class="w-full max-w-md mx-auto my-auto space-y-6 py-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/10 border border-teal-500/20 text-xs font-mono font-bold text-teal-400">
                        <span>🔐 SECURE SYSTEM ACCESS</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        Connexion Super Admin
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-400">
                        Veuillez vous authentifier avec vos privilèges d'administrateur système.
                    </p>
                </div>

                <form action="{{ route('platform.login.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Adresse Email Administrateur</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@insurio.ma"
                               class="w-full px-4 py-3 text-sm font-medium border border-slate-800 bg-slate-900 text-slate-100 placeholder-slate-500 rounded-xl shadow-xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                        @error('email')
                            <span class="text-xs font-semibold text-rose-500 mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Mot de Passe Système</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••••••"
                               class="w-full px-4 py-3 text-sm font-medium border border-slate-800 bg-slate-900 text-slate-100 placeholder-slate-500 rounded-xl shadow-xs focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-800 bg-slate-900 text-teal-500 focus:ring-teal-500">
                            <span class="text-xs font-medium text-slate-400 select-none">Maintenir ma session active</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center px-6 py-3.5 bg-teal-600 hover:bg-teal-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-teal-600/30 transition-all cursor-pointer">
                        Se connecter à la Console ➔
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="pt-8 text-center text-xs text-slate-500 font-medium">
                <p>© {{ date('Y') }} Insurio System Control. Restricted Access.</p>
            </div>
        </div>

    </div>

</body>
</html>
