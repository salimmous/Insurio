@extends('themes.tesla-insurance.layouts.app')

@section('content')
<section class="py-24 max-w-6xl mx-auto px-6 space-y-12">
    <div class="text-center space-y-3">
        <span class="text-xs font-bold text-red-600 uppercase tracking-widest">Nos Services</span>
        <h2 class="text-3xl md:text-5xl font-black">Services Télématiques</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
        <div class="p-8 border border-slate-200 rounded-2xl bg-white shadow-xs space-y-4">
            <h3 class="font-bold text-xl">Safety Score</h3>
            <p class="text-xs text-slate-500">Calcul en temps réel des risques de freinage brusque et virages serrés.</p>
        </div>
        <div class="p-8 border border-slate-200 rounded-2xl bg-white shadow-xs space-y-4">
            <h3 class="font-bold text-xl">Assistance Autopilot</h3>
            <p class="text-xs text-slate-500">Dépannage d'urgence 24/7 en cas de collision ou batterie déchargée.</p>
        </div>
        <div class="p-8 border border-slate-200 rounded-2xl bg-white shadow-xs space-y-4">
            <h3 class="font-bold text-xl">Remplacement Tesla</h3>
            <p class="text-xs text-slate-500">Véhicule de remplacement prêté directement dans le réseau Tesla Service.</p>
        </div>
    </div>
</section>
@endsection
