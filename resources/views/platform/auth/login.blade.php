<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Connexion</title>
    <!-- Styles & Local Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 flex min-h-screen items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-900/60 border border-slate-800 rounded-3xl p-8 backdrop-blur-xl shadow-2xl space-y-6 relative overflow-hidden">
        
        <!-- Glow effect -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>

        <div class="text-center space-y-2">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-400">Insurio</span>
            <h1 class="text-2xl font-bold text-slate-100">Super Admin Console</h1>
            <p class="text-sm text-slate-400">Veuillez vous authentifier pour accéder à la plateforme.</p>
        </div>

        <form action="{{ route('platform.login.submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-400 mb-2">Adresse Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-slate-950 border border-slate-800 focus:border-teal-500 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 outline-none transition-all">
                @error('email')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-400 mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="w-full bg-slate-950 border border-slate-800 focus:border-teal-500 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 outline-none transition-all">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/30">
                    <span class="text-xs text-slate-400 select-none">Se souvenir de moi</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-teal-600 hover:bg-teal-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-teal-900/30">
                Se connecter
            </button>
        </form>
    </div>
</body>
</html>
