@extends('layouts.platform')

@section('title', 'Nouvelle Agence - Insurio Central')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Form Card: Rounded 3xl, shadow, modern border -->
        <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] space-y-8">
            <div class="border-b border-slate-100 pb-5 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-600 bg-indigo-50/60 px-2.5 py-1 rounded-md border border-indigo-100">Insurio</span>
                    <h1 class="text-xl font-extrabold text-slate-900 mt-2.5 tracking-tight">Nouvelle Agence Cliente</h1>
                </div>
                <a href="{{ route('platform.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <span>←</span> Retour console
                </a>
            </div>

            @if($errors->any())
                <div class="bg-rose-50 border border-rose-150 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('platform.tenants.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Informations de Base -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2 border-b border-slate-50 pb-2">
                        <span class="text-sm">🔑</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Informations Agence</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Identifiant Unique (Tenant ID)</label>
                            <input type="text" id="id" name="id" value="{{ old('id') }}" required placeholder="Ex: axamaarif"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm font-mono transition-all placeholder-slate-400">
                            <p class="text-[10px] text-slate-400 mt-2 font-medium">Lettres et chiffres uniquement. Définit le sous-domaine d'accès.</p>
                        </div>

                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Nom du Cabinet / Agence</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Ex: AXA Assurance Maarif"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all placeholder-slate-400">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email Administrateur Agence</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Ex: admin@axamaarif.ma"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all placeholder-slate-400">
                            <p class="text-[10px] text-slate-400 mt-2 font-medium">Un compte Administrateur Cabinet sera configuré avec cet email (mot de passe initial : password).</p>
                        </div>

                        <div>
                            <label for="plan_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Plan d'Abonnement SaaS</label>
                            <select id="plan_id" name="plan_id" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold outline-none transition-all text-slate-700">
                                @foreach($plans as $p)
                                    <option value="{{ $p->id }}" {{ old('plan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ number_format($p->price, 0) }} DH/mois)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Facturation & Paramètres Financiers -->
                <div class="space-y-6 pt-4">
                    <div class="flex items-center gap-2 border-b border-slate-50 pb-2">
                        <span class="text-sm">💰</span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Abonnement, Facturation & Domaine</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subscription_start_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Date de début d'abonnement</label>
                            <input type="date" id="subscription_start_date" name="subscription_start_date" value="{{ old('subscription_start_date', now()->format('Y-m-d')) }}"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all text-slate-700">
                        </div>

                        <div>
                            <label for="subscription_end_date" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Date de fin / Échéance</label>
                            <input type="date" id="subscription_end_date" name="subscription_end_date" value="{{ old('subscription_end_date') }}"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm transition-all text-slate-700">
                            <p class="text-[10px] text-slate-400 mt-2 font-medium">Laisser vide pour un abonnement illimité.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="rent_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Montant du Loyer Mensuel (DH)</label>
                            <input type="number" step="0.01" min="0" id="rent_amount" name="rent_amount" value="{{ old('rent_amount') }}" placeholder="Ex: 500"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm font-mono transition-all placeholder-slate-400">
                            <p class="text-[10px] text-slate-400 mt-2 font-medium">Prix de la location de l'application SaaS par mois.</p>
                        </div>

                        <div>
                            <label for="custom_domain" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Domaine Personnalisé (Optionnel)</label>
                            <input type="text" id="custom_domain" name="custom_domain" value="{{ old('custom_domain') }}" placeholder="Ex: moncabinet.ma"
                                   class="w-full bg-slate-50/50 border border-slate-200 focus:bg-white focus:border-indigo-600 focus:ring-4 focus:ring-indigo-50 rounded-xl px-4 py-3 outline-none text-sm font-mono transition-all placeholder-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('platform.dashboard') }}" class="bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold px-6 py-3 rounded-xl text-sm transition-all border border-slate-200/80 shadow-sm">
                        Annuler
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold px-8 py-3 rounded-xl text-sm transition-all shadow-md shadow-indigo-600/10 hover:shadow-indigo-600/20 hover:-translate-y-0.5">
                        Déployer l'Agence
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
