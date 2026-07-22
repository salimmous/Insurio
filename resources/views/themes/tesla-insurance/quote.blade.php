@extends('themes.tesla-insurance.layouts.app')

@section('content')
<section class="py-24 max-w-xl mx-auto px-6 space-y-8">
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-black">Cotation Télématique Instantanée</h1>
        <p class="text-xs text-slate-500">Entrez votre VIN Tesla pour calculer votre prime de sécurité.</p>
    </div>
    <form class="space-y-4 bg-white p-8 rounded-2xl border border-slate-200 shadow-md text-xs font-medium">
        <div>
            <label class="block font-bold mb-1">Numéro VIN Tesla</label>
            <input type="text" placeholder="5YJ3E1EA0KFXXXXXX" class="w-full border border-slate-200 rounded-xl p-3 font-mono">
        </div>
        <div>
            <label class="block font-bold mb-1">Kilométrage Annuel Estimé</label>
            <select class="w-full border border-slate-200 rounded-xl p-3">
                <option>Moins de 10 000 km</option>
                <option>10 000 - 20 000 km</option>
                <option>Plus de 20 000 km</option>
            </select>
        </div>
        <button type="submit" class="w-full bg-[#E82127] text-white font-bold py-3.5 rounded-full uppercase tracking-wider">
            Calculer mon tarif ➔
        </button>
    </form>
</section>
@endsection
