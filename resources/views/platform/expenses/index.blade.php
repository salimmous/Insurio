@extends('layouts.platform')

@section('header_title', 'Gestion de Comptabilité & Charges')

@section('content')
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
@endsection
