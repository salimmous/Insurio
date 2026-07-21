<div>
    @if($isSuperAdmin)
    <!-- Floating Platform Support Badge Trigger -->
    <div class="fixed bottom-5 right-5 z-50">
        <button wire:click="togglePanel" 
                class="bg-slate-900 hover:bg-slate-850 text-white font-bold px-4 py-2.5 rounded-2xl shadow-2xl border border-slate-700/80 flex items-center gap-2.5 transition-all transform hover:scale-105 group text-xs">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $tenantStatus === 'Actif' ? 'bg-teal-400' : 'bg-rose-500' }}"></span>
            </span>
            <span class="tracking-wide">CONSOLE SUPPORT TECHNIQUE</span>
            <span class="bg-slate-800 text-teal-400 font-mono text-[9px] px-2 py-0.5 rounded-md border border-slate-700 group-hover:border-teal-500/50">
                {{ $tenantStatus }}
            </span>
        </button>
    </div>

    <!-- Expanded Enterprise Support Command Panel -->
    @if($isOpen)
    <div class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-md flex justify-end animate-fade-in" wire:keydown.window.escape="togglePanel">
        <div class="w-full max-w-xl bg-slate-900 border-l border-slate-800 text-slate-200 h-full flex flex-col shadow-2xl overflow-hidden">
            
            <!-- Panel Header -->
            <div class="p-5 border-b border-slate-800 bg-slate-950 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center font-black text-lg">
                        🛡️
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white tracking-wide">Console Support Technique & Assistance Agence</h2>
                        <p class="text-[10px] text-slate-400 mt-0.5">Diagnostics & Interventions en direct pour {{ tenant('name') ?? 'Insurio Agency' }}</p>
                    </div>
                </div>
                <button wire:click="togglePanel" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Flash Notifications inside Panel -->
            @if(session()->has('super_msg'))
                <div class="mx-5 mt-4 p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-300 text-xs font-semibold flex items-center justify-between">
                    <span>{{ session('super_msg') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white text-xs font-bold">✕</button>
                </div>
            @endif
            @if(session()->has('super_error'))
                <div class="mx-5 mt-4 p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-semibold flex items-center justify-between">
                    <span>{{ session('super_error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white text-xs font-bold">✕</button>
                </div>
            @endif

            <!-- Live Monitoring Cards -->
            <div class="p-5 space-y-4 border-b border-slate-800 bg-slate-900/50">
                <div class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 flex items-center justify-between">
                    <span>Santé Serveur & Base de Données (30s)</span>
                    <button wire:click="refreshStats" class="text-teal-400 hover:underline font-bold text-[9px] flex items-center gap-1">
                        🔄 Rafraîchir
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/60">
                        <span class="text-[9px] font-bold text-slate-400 block uppercase">Latence DB</span>
                        <span class="text-sm font-mono font-bold text-emerald-400 mt-1 block">{{ $dbLatency }}</span>
                    </div>

                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/60">
                        <span class="text-[9px] font-bold text-slate-400 block uppercase">File d'Attente</span>
                        <span class="text-sm font-mono font-bold text-teal-400 mt-1 block">{{ $pendingJobsCount }} Jobs</span>
                    </div>

                    <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/60">
                        <span class="text-[9px] font-bold text-slate-400 block uppercase">Échecs Jobs</span>
                        <span class="text-sm font-mono font-bold text-rose-400 mt-1 block">{{ $failedJobsCount }} Échecs</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Command Matrix -->
            <div class="p-5 flex-1 overflow-y-auto space-y-5 sidebar-scrollbar">
                
                <!-- 1. Support Actions -->
                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">Actions de Support Rapide</span>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button wire:click="resetCache" class="p-3 bg-slate-800 hover:bg-slate-750 text-slate-200 hover:text-white rounded-xl border border-slate-700/80 text-left transition-all group">
                            <div class="flex items-center gap-2">
                                <span class="text-base">⚡</span>
                                <span class="text-xs font-bold block">Réinitialiser Cache</span>
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">Vide config, routes & vues</span>
                        </button>

                        <button wire:click="runScheduler" class="p-3 bg-slate-800 hover:bg-slate-750 text-slate-200 hover:text-white rounded-xl border border-slate-700/80 text-left transition-all group">
                            <div class="flex items-center gap-2">
                                <span class="text-base">⏱️</span>
                                <span class="text-xs font-bold block">Exécuter Scheduler</span>
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">Lance les relances & cron</span>
                        </button>

                        <button wire:click="generateBackup" class="p-3 bg-slate-800 hover:bg-slate-750 text-slate-200 hover:text-white rounded-xl border border-slate-700/80 text-left transition-all group">
                            <div class="flex items-center gap-2">
                                <span class="text-base">📦</span>
                                <span class="text-xs font-bold block">Sauvegarde Express</span>
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">Dump DB & Fichiers tenant</span>
                        </button>

                        <button wire:click="resetAdminPassword" class="p-3 bg-slate-800 hover:bg-slate-750 text-slate-200 hover:text-white rounded-xl border border-slate-700/80 text-left transition-all group">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🔑</span>
                                <span class="text-xs font-bold block">Reset Mot de Passe</span>
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">Génère mot de passe admin</span>
                        </button>
                    </div>
                </div>

                <!-- 2. Security & Emergency Operations -->
                <div class="space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block">Opérations d'Urgence & Maintenance</span>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button wire:click="toggleMaintenanceMode" class="p-3 bg-amber-500/10 hover:bg-amber-500/20 text-amber-300 rounded-xl border border-amber-500/30 text-left transition-all">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🛠️</span>
                                <span class="text-xs font-bold block">Mode Maintenance</span>
                            </div>
                            <span class="text-[9px] text-amber-400/80 block mt-1">Activer / Désactiver</span>
                        </button>

                        @if($tenantStatus === 'Actif')
                        <button wire:click="emergencyLock" onclick="confirm('Êtes-vous sûr de vouloir VERROUILLER l\'accès à cette agence ?') || event.stopImmediatePropagation()" class="p-3 bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 rounded-xl border border-rose-500/30 text-left transition-all">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🚨</span>
                                <span class="text-xs font-bold block">Verrouillage d'Urgence</span>
                            </div>
                            <span class="text-[9px] text-rose-400/80 block mt-1">Suspendre immédiatement</span>
                        </button>
                        @else
                        <button wire:click="emergencyUnlock" class="p-3 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 rounded-xl border border-emerald-500/30 text-left transition-all">
                            <div class="flex items-center gap-2">
                                <span class="text-base">🔓</span>
                                <span class="text-xs font-bold block">Déverrouiller l'Agence</span>
                            </div>
                            <span class="text-[9px] text-emerald-400/80 block mt-1">Rétablir les accès</span>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Tenant Metadata Summary -->
                <div class="bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-400">
                        <span>Identifiant Agence:</span>
                        <span class="font-mono text-slate-200 font-bold">{{ tenant('id') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Domaine Officiel:</span>
                        <span class="font-mono text-teal-400 font-bold">{{ request()->getHost() }}</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Version Insurio Core:</span>
                        <span class="font-mono text-slate-200 font-bold">v4.8 Enterprise</span>
                    </div>
                </div>

            </div>

            <!-- Panel Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950 flex items-center justify-between text-[11px] text-slate-400">
                <span>Console Support Technique — Confidentialité Plateforme</span>
                <button wire:click="togglePanel" class="text-teal-400 font-bold hover:underline">Fermer Panel</button>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
