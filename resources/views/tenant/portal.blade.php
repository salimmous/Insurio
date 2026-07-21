<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Client - {{ tenant('name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen p-4 md:p-10 flex items-center justify-center">

    <div class="max-w-2xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl space-y-6">
        <!-- Agency Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-500 to-indigo-600 flex items-center justify-center font-bold text-white shadow-lg">
                    {{ substr(tenant('name'), 0, 1) }}
                </div>
                <div>
                    <h1 class="font-extrabold text-lg text-white">{{ tenant('name') }}</h1>
                    <span class="text-xs text-teal-400 font-semibold">Attestation d'Assurance Officielle</span>
                </div>
            </div>
            <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold rounded-full">
                {{ strtoupper($contract->statut ?? 'ACTIF') }}
            </span>
        </div>

        <!-- Contract Overview Grid -->
        <div class="grid grid-cols-2 gap-4 text-xs">
            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                <span class="text-slate-500 font-bold block uppercase text-[9px]">Souscripteur</span>
                <span class="text-sm font-bold text-slate-200 mt-1 block">{{ $client ? $client->nom_complet : 'N/A' }}</span>
                <span class="text-slate-400 block text-[10px]">{{ $client->cin ?? '' }}</span>
            </div>

            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                <span class="text-slate-500 font-bold block uppercase text-[9px]">N° Police / Contrat</span>
                <span class="text-sm font-bold text-teal-400 mt-1 block font-mono">{{ $contract->contract_number }}</span>
                <span class="text-slate-400 block text-[10px]">{{ $compagnie ? $compagnie->nom : 'Compagnie d\'Assurance' }}</span>
            </div>

            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                <span class="text-slate-500 font-bold block uppercase text-[9px]">Date Effet</span>
                <span class="text-sm font-bold text-slate-200 mt-1 block font-mono">{{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '-' }}</span>
            </div>

            <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800">
                <span class="text-slate-500 font-bold block uppercase text-[9px]">Date Échéance</span>
                <span class="text-sm font-bold text-indigo-400 mt-1 block font-mono">{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') : '-' }}</span>
            </div>
        </div>

        <!-- Premium Financial Summary -->
        <div class="bg-gradient-to-r from-teal-950/40 to-slate-900 p-4 rounded-2xl border border-teal-500/20 flex items-center justify-between">
            <div>
                <span class="text-xs text-slate-400 block">Prime Totale TTC</span>
                <span class="text-xl font-extrabold text-white font-mono">{{ number_format($contract->premium_amount ?? $contract->prime_totale, 2) }} DH</span>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider block text-right {{ $contract->solde <= 0 ? 'text-emerald-400' : 'text-amber-400' }}">
                    {{ $contract->solde <= 0 ? 'Réglé Totalement' : 'Solde : ' . number_format($contract->solde, 2) . ' DH' }}
                </span>
            </div>
        </div>

        <!-- PDF Downloads Actions -->
        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('automobile.pdf', ['contratId' => $contract->id, 'type' => 'attestation']) }}" target="_blank" class="flex-1 bg-teal-500 hover:bg-teal-600 text-slate-950 font-bold text-xs py-3 px-4 rounded-xl text-center transition shadow-lg">
                📄 Télécharger Attestation PDF
            </a>
            <a href="{{ route('automobile.pdf', ['contratId' => $contract->id, 'type' => 'quittance']) }}" target="_blank" class="flex-1 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs py-3 px-4 rounded-xl text-center transition border border-slate-700">
                🧾 Télécharger Quittance PDF
            </a>
        </div>

        <div class="text-center pt-2 text-[10px] text-slate-500">
            Propulsé par la plateforme centralisée Insurio ERP. Vérification d'authenticité active.
        </div>
    </div>

</body>
</html>
