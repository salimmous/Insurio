<div class="p-6 space-y-6 font-sans">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">Journal d'activité</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tracez l'historique de toutes les opérations de l'agence.</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Recherche</label>
            <input type="text" wire:model.live="search" placeholder="Rechercher par utilisateur, IP, modèle..." 
                   class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
        </div>
        <div>
            <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Action</label>
            <select wire:model.live="actionFilter" 
                    class="w-full px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                <option value="">Toutes les actions</option>
                <option value="created">Création (created)</option>
                <option value="updated">Modification (updated)</option>
                <option value="deleted">Suppression (deleted)</option>
                <option value="approved">Approbation (approved)</option>
                <option value="exported">Exportation (exported)</option>
            </select>
        </div>
    </div>

    <!-- Timeline Wrapper -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
        @if($logs->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">Aucune activité trouvée</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Essayez de modifier vos critères de filtrage.</p>
            </div>
        @else
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($logs as $index => $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-content-center text-white
                                            @if($log->action === 'created') bg-emerald-500
                                            @elseif($log->action === 'updated') bg-blue-500
                                            @elseif($log->action === 'deleted') bg-rose-500
                                            @elseif($log->action === 'approved') bg-violet-500
                                            @else bg-amber-500
                                            @endif">
                                            @if($log->action === 'created')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            @elseif($log->action === 'updated')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            @elseif($log->action === 'deleted')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-slate-800 dark:text-slate-200">
                                                <span class="font-semibold text-slate-900 dark:text-white">{{ $log->user ? $log->user->name : 'Système' }}</span>
                                                a effectué l'action <span class="font-mono bg-slate-100 dark:bg-slate-700 px-1 py-0.5 rounded text-xs text-slate-700 dark:text-slate-300 font-bold">{{ $log->action }}</span> sur 
                                                <span class="font-medium">{{ class_basename($log->model_type) }}</span> (ID: {{ $log->model_id }})
                                            </p>
                                            
                                            <!-- Detailed values comparison -->
                                            @if($log->new_values || $log->old_values)
                                                <div class="mt-2 text-xs bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 rounded-lg p-3">
                                                    @if($log->old_values)
                                                        <div class="mb-1 font-semibold text-rose-500">Avant :</div>
                                                        <pre class="font-mono text-[10px] overflow-x-auto whitespace-pre-wrap text-slate-500 mb-2">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                    @if($log->new_values)
                                                        <div class="font-semibold text-emerald-500">Après :</div>
                                                        <pre class="font-mono text-[10px] overflow-x-auto whitespace-pre-wrap text-slate-600 dark:text-slate-400">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="mt-1 flex items-center space-x-2 text-[10px] text-slate-400">
                                                <span>IP: {{ $log->ip_address }}</span>
                                                <span>•</span>
                                                <span class="truncate max-w-[300px]" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span>
                                            </div>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-slate-500 pt-0.5">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
