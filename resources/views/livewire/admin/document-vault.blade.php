<div class="py-6 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Coffre-fort Documents</h1>
                <p class="text-xs text-slate-450 mt-1">Consulter et gérer l'ensemble des documents téléversés pour tous les clients.</p>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex-1 w-full">
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom de fichier..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs focus:bg-white focus:outline-none transition-all">
            </div>
            <div class="w-full sm:w-[200px]">
                <select wire:model.live="selectedType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 outline-none">
                    <option value="">Tous les types</option>
                    <option value="cin">CIN</option>
                    <option value="passport">Passeport</option>
                    <option value="driving_license">Permis de conduire</option>
                    <option value="vehicle_registration">Carte Grise</option>
                    <option value="contract">Contrat</option>
                    <option value="invoice">Facture</option>
                    <option value="other">Autre</option>
                </select>
            </div>
        </div>

        <!-- Documents Grid -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($documents as $doc)
                    <div class="flex flex-col justify-between p-4 bg-slate-50 border border-slate-250 rounded-2xl shadow-xs hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-700 rounded-xl flex items-center justify-center font-bold text-xs uppercase border border-indigo-100">
                                    {{ $doc->type }}
                                </div>
                                <div class="overflow-hidden max-w-[150px]">
                                    <span class="block font-bold text-slate-800 truncate text-sm" title="{{ $doc->file_name }}">{{ $doc->file_name }}</span>
                                    <span class="text-[10px] text-slate-550 block truncate">Client: {{ $doc->client->nom_complet ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-[9px] text-slate-400 font-mono">TYPE: {{ strtoupper($doc->type) }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" class="px-2 py-1 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-700">Prévisualiser</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-400">
                        Aucun document trouvé dans le coffre-fort.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
