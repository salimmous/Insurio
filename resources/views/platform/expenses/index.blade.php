<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestion des Charges & Comptabilité - Insurio Central</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F6F8] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- LEFT SIDEBAR: Premium Dark Slate Theme -->
            <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-[#0F172A] border-r border-[#1E293B] flex-shrink-0 text-slate-300">
                <!-- Logo Block -->
                <div class="h-16 flex items-center px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-lg font-bold text-white font-sans tracking-wide">Insurio Central</span>
                    </div>
                </div>

                <!-- User Profile Block -->
                <div class="p-4 mx-4 my-4 bg-[#1E293B]/50 rounded-xl border border-[#334155]/40 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center font-bold text-white text-sm">
                        S
                    </div>
                    <div class="overflow-hidden">
                        <div class="font-bold text-xs text-white truncate">{{ Auth::guard('platform')->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">Super Admin</div>
                    </div>
                </div>

                <!-- Navigation List -->
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-400 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                        </svg>
                        Console Centrale
                    </a>

                    <!-- Nouvelle Agence Link -->
                    <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white transition-all">
                        <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Nouvelle Agence
                    </a>

                    <!-- Comptabilité Link (active) -->
                    <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl bg-[#1E293B] text-white">
                        <svg class="h-5 w-5 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Comptabilité / Charges
                    </a>
                </nav>

                <!-- Logout Block -->
                <div class="p-4 border-t border-[#1E293B] mt-auto">
                    <form method="POST" action="{{ route('platform.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-semibold rounded-xl text-rose-450 hover:bg-rose-950/20 transition-all">
                            <svg class="h-5 w-5 mr-3 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </aside>

            <!-- MOBILE NAV OVERLAY & DRAWER -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-[#0F172A]/70 z-40 lg:hidden" @click="sidebarOpen = false"></div>
            
            <aside class="fixed top-0 bottom-0 left-0 w-64 bg-[#0F172A] border-r border-[#1E293B] z-50 transform -translate-x-full transition-transform duration-300 lg:hidden" 
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B]">
                    <div class="flex items-center gap-2">
                        <svg class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                        </svg>
                        <span class="text-lg font-bold text-white font-sans tracking-wide">Insurio Central</span>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="px-4 py-4 space-y-1">
                    <a href="{{ route('platform.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Console Centrale</a>
                    <a href="{{ route('platform.tenants.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Nouvelle Agence</a>
                    <a href="{{ route('platform.expenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl text-slate-450 hover:bg-[#1E293B]/40 hover:text-white">Comptabilité / Charges</a>
                </nav>
            </aside>

            <!-- MAIN CONTENT AREA -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header bar -->
                <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-6 z-10">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-800 lg:hidden">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="font-bold text-slate-800 text-sm">Gestion de Comptabilité & Charges</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200/60 rounded-full px-3 py-1 text-xs font-semibold">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Suivi Comptable Landlord
                        </div>
                    </div>
                </header>

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F5F6F8]">
                    <div class="py-8">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                            <!-- Flash Messages -->
                            @if(session('success'))
                                <div class="bg-emerald-50 border border-emerald-250/60 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2">
                                    <span>🎉</span>
                                    <span>{{ session('success') }}</span>
                                </div>
                            @endif

                            <!-- accounting METRICS GRID -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                                <!-- Metric 1: Expected Revenue -->
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Revenus mensuels attendus</span>
                                        <div class="text-xl font-extrabold text-slate-900">{{ number_format($monthlyRevenue, 2) }} DH</div>
                                        <p class="text-[10px] text-slate-400">Somme des loyers des agences actives</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg">💰</div>
                                </div>

                                <!-- Metric 2: Expenses this Month -->
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Charges (Ce mois)</span>
                                        <div class="text-xl font-extrabold text-rose-650">{{ number_format($monthlyExpenses, 2) }} DH</div>
                                        <p class="text-[10px] text-slate-400">Dépenses effectuées f {{ now()->translatedFormat('F Y') }}</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg">📉</div>
                                </div>

                                <!-- Metric 3: Net income projection -->
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Bénéfice Net attendu</span>
                                        <div class="text-xl font-extrabold {{ $netIncome >= 0 ? 'text-emerald-650' : 'text-rose-650' }}">
                                            {{ number_format($netIncome, 2) }} DH
                                        </div>
                                        <p class="text-[10px] text-slate-400">Revenus attendus moins les charges</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-xl {{ $netIncome >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center font-bold text-lg">📈</div>
                                </div>

                                <!-- Metric 4: All time expenses -->
                                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm flex items-center justify-between">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Charges globales (Total)</span>
                                        <div class="text-xl font-extrabold text-slate-800">{{ number_format($totalExpensesAllTime, 2) }} DH</div>
                                        <p class="text-[10px] text-slate-400">Cumul de toutes les charges enregistrées</p>
                                    </div>
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-lg">📁</div>
                                </div>
                            </div>

                            <!-- MAIN CONTENT ROW -->
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Expenses List (2 Cols) -->
                                <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm">
                                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                        <h2 class="font-bold text-slate-800 text-sm">Registre des Charges / Dépenses</h2>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider font-mono">Total: {{ $expenses->count() }}</span>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs text-slate-650 min-w-[700px]">
                                            <thead class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                <tr>
                                                    <th class="px-5 py-4">Titre & Catégorie</th>
                                                    <th class="px-5 py-4">Description</th>
                                                    <th class="px-5 py-4">Date de Dépense</th>
                                                    <th class="px-5 py-4 text-right">Montant</th>
                                                    <th class="px-5 py-4 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 font-medium text-xs">
                                                @forelse($expenses as $expense)
                                                    <tr class="hover:bg-slate-50 transition-colors">
                                                        <!-- Title & Category -->
                                                        <td class="px-5 py-4">
                                                            <div class="font-bold text-slate-800 text-sm">{{ $expense->title }}</div>
                                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold uppercase mt-1 bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                                {{ $expense->category }}
                                                            </span>
                                                        </td>
                                                        
                                                        <!-- Description -->
                                                        <td class="px-5 py-4 text-slate-500 max-w-xs truncate" title="{{ $expense->description }}">
                                                            {{ $expense->description ?? '-' }}
                                                        </td>

                                                        <!-- Date -->
                                                        <td class="px-5 py-4 font-mono text-slate-500">
                                                            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}
                                                        </td>

                                                        <!-- Amount -->
                                                        <td class="px-5 py-4 text-right font-bold text-rose-650 font-mono text-sm whitespace-nowrap">
                                                            - {{ number_format($expense->amount, 2) }} DH
                                                        </td>

                                                        <!-- Actions -->
                                                        <td class="px-5 py-4 text-right">
                                                            <form action="{{ route('platform.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette charge ?');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200/60 font-bold px-3 py-1.5 rounded-xl transition-all text-[11px] shadow-sm">
                                                                    🗑️ Supprimer
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="px-5 py-8 text-center text-slate-400">Aucune dépense enregistrée.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Add Expense Card (1 Col) -->
                                <div class="lg:col-span-1">
                                    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                                        <div class="border-b border-slate-100 pb-3 flex justify-between items-center bg-slate-50/50 -m-5 p-5 rounded-t-2xl">
                                            <h3 class="font-bold text-slate-800 text-sm">Ajouter une charge</h3>
                                            <span class="text-[9px] bg-rose-50 text-rose-750 border border-rose-200/60 font-bold px-2 py-0.5 rounded-lg uppercase tracking-wide">NOUVEAU</span>
                                        </div>

                                        <form action="{{ route('platform.expenses.store') }}" method="POST" class="space-y-4 pt-4">
                                            @csrf

                                            <div>
                                                <label for="title" class="block text-xs font-semibold uppercase tracking-wider text-slate-450 mb-2">Libellé / Titre de la charge</label>
                                                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex: Hébergement Serveur VPS"
                                                       class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                            </div>

                                            <div>
                                                <label for="category" class="block text-xs font-semibold uppercase tracking-wider text-slate-450 mb-2">Catégorie</label>
                                                <select id="category" name="category" required
                                                        class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                                    <option value="Hébergement">Hébergement & Serveurs</option>
                                                    <option value="Nom de domaine">Noms de domaine</option>
                                                    <option value="Marketing">Marketing & Publicité</option>
                                                    <option value="Logiciels/SaaS">Logiciels / Outils SaaS</option>
                                                    <option value="Frais de gestion">Frais de gestion & Taxes</option>
                                                    <option value="Autre">Autre dépense</option>
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label for="amount" class="block text-xs font-semibold uppercase tracking-wider text-slate-450 mb-2">Montant (DH)</label>
                                                    <input type="number" step="0.01" min="0.01" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="Ex: 150.00"
                                                           class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm font-mono transition-all">
                                                </div>

                                                <div>
                                                    <label for="expense_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-450 mb-2">Date</label>
                                                    <input type="date" id="expense_date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}" required
                                                           class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="description" class="block text-xs font-semibold uppercase tracking-wider text-slate-450 mb-2">Description / Notes (Optionnel)</label>
                                                <textarea id="description" name="description" rows="3" placeholder="Notes complémentaires sur cette dépense..."
                                                          class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">{{ old('description') }}</textarea>
                                            </div>

                                            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-colors shadow-sm shadow-rose-900/10">
                                                📉 Enregistrer la dépense
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </main>
            </div>

        </div>
    </body>
</html>
