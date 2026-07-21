<div class="p-6 space-y-6 font-sans">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">Importation de données</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Importez vos Clients ou vos Contrats directement depuis des fichiers Excel (.xlsx).</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-800 dark:text-rose-300 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Import Workspace -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Nouvel Import</h3>
                
                <div class="space-y-4">
                    <!-- Type Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Type de données</label>
                        <select wire:model.live="importType" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                            <option value="clients">Clients CRM</option>
                            <option value="contracts">Contrats d'Assurance</option>
                        </select>
                    </div>

                    <!-- File Drop Area -->
                    <div class="border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-xl p-8 text-center bg-slate-50 dark:bg-slate-900/20 hover:bg-slate-100/50 dark:hover:bg-slate-900/30 transition-all cursor-pointer relative">
                        <input type="file" wire:model="importFile" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            @if($importFile)
                                Fichier prêt : {{ $importFile->getClientOriginalName() }}
                            @else
                                Glissez-déposez un fichier Excel ici, ou cliquez pour parcourir
                            @endif
                        </p>
                        <p class="text-xs text-slate-400 mt-1">XLSX, XLS ou CSV jusqu'à 10 Mo</p>
                    </div>
                </div>
            </div>

            <!-- Preview Rows Section -->
            @if($showPreview)
                <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Aperçu du Fichier</h3>
                    <p class="text-xs text-slate-400 mb-4">Voici un aperçu des 5 premières lignes détectées. Vérifiez la correspondance des en-têtes.</p>

                    <div class="overflow-x-auto border border-slate-100 dark:border-slate-700 rounded-lg">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-700/50 text-slate-550 dark:text-slate-300 border-b border-slate-100 dark:border-slate-700">
                                    @foreach($headers as $header)
                                        <th class="px-4 py-3 font-semibold">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($previewRows as $row)
                                    <tr class="border-b border-slate-100 dark:border-slate-750 text-slate-600 dark:text-slate-300 hover:bg-slate-50/50 dark:hover:bg-slate-800/50">
                                        @foreach($row as $cell)
                                            <td class="px-4 py-3">{{ $cell }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" wire:click="$reset" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-700">
                            Annuler
                        </button>
                        <button type="button" wire:click="runImport" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Lancer l'importation
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Import History -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 h-fit">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Historique des Imports</h3>

            <div class="space-y-4">
                @if($history->isEmpty())
                    <p class="text-sm text-slate-450 dark:text-slate-400 text-center py-6">Aucun import dans l'historique.</p>
                @else
                    @foreach($history as $item)
                        <div class="p-3 border border-slate-100 dark:border-slate-700 rounded-lg hover:border-slate-200 dark:hover:border-slate-600 transition-all">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold text-sm text-slate-850 dark:text-white truncate max-w-[150px]" title="{{ $item->file }}">{{ $item->file }}</span>
                                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded
                                    {{ $item->type === 'clients' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' }}">
                                    {{ $item->type }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-450 dark:text-slate-400 mt-2">
                                <div>
                                    <span class="text-emerald-500 font-bold">{{ $item->success_rows }}</span> ok
                                    @if($item->failed_rows > 0)
                                        / <span class="text-rose-500 font-bold">{{ $item->failed_rows }}</span> error
                                    @endif
                                </div>
                                <span class="text-[10px]">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            
                            @if(!empty($item->errors))
                                <div class="mt-2 text-[10px] text-rose-500 max-h-[100px] overflow-y-auto font-mono leading-tight">
                                    @foreach(array_slice($item->errors, 0, 3) as $err)
                                        • {{ $err }}<br>
                                    @endforeach
                                    @if(count($item->errors) > 3)
                                        • ... et {{ count($item->errors) - 3 }} autres erreurs.
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{ $history->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
