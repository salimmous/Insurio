<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session()->has('message'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Page Header & Action Controls -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                <span>GESTION DES ÉQUIPES & SÉCURITÉ 2FA</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Gestion des Employés & Invitations
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Créez des profils employés et envoyez des invitations sécurisées (2FA Obligatoire).
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Test SMTP Button -->
            <button wire:click="testSmtp" title="Tester la configuration de messagerie SMTP" 
                    class="inline-flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs px-4 py-3.5 rounded-2xl transition-all border border-slate-200 dark:border-slate-700">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
                <span>Tester SMTP</span>
            </button>

            <!-- New Employee Button -->
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs px-6 py-3.5 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                <span>Inviter un Nouvel Employé</span>
            </button>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row gap-4 items-center justify-between">
        <div class="w-full sm:w-80">
            <input wire:model.live.debounce.300ms="searchQuery" type="text" placeholder="Rechercher par nom, email, CIN..." 
                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-600 outline-none">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <span class="text-xs font-bold text-slate-500">Statut:</span>
            <select wire:model.live="filterStatut" class="px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-slate-100 outline-none">
                <option value="">Tous les statuts</option>
                <option value="invitation_sent">Invitation Envoyée</option>
                <option value="actif">Actif (2FA Activé)</option>
                <option value="suspended">Suspendu</option>
                <option value="disabled">Désactivé</option>
            </select>
        </div>
    </div>

    <!-- Table of Employees with Modern Lucide Action Suite -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-950/80 border-b border-slate-200 dark:border-slate-800 text-[10px] uppercase font-mono font-bold text-slate-400 tracking-wider">
                    <tr>
                        <th class="py-4 px-6">Employé & Matricule</th>
                        <th class="py-4 px-6">Email Pro</th>
                        <th class="py-4 px-6">Succursale & Poste</th>
                        <th class="py-4 px-6">Commission</th>
                        <th class="py-4 px-6">Statut Compte & 2FA</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-medium">
                    @forelse($employes as $emp)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                            <!-- Employee & Matricule -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm border border-indigo-500/20 shrink-0">
                                        {{ strtoupper(substr($emp->prenom, 0, 1)) }}{{ strtoupper(substr($emp->nom, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 dark:text-white block text-sm">{{ $emp->nom_complet }}</span>
                                        <span class="font-mono text-[10px] text-slate-400 block">{{ $emp->matricule_employe }} • CIN: {{ $emp->cin ?: 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-4 px-6 font-mono text-slate-700 dark:text-slate-300">
                                {{ $emp->email }}
                                @if($emp->telephone)
                                    <span class="block text-[10px] text-slate-400 font-sans">{{ $emp->telephone }}</span>
                                @endif
                            </td>

                            <!-- Succursale & Poste -->
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ optional($emp->succursale)->nom ?? 'Siège Agence' }}</span>
                                <span class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 font-bold block">{{ $emp->poste }}</span>
                            </td>

                            <!-- Commission -->
                            <td class="py-4 px-6 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($emp->taux_commission_defaut, 2) }}%
                            </td>

                            <!-- Statut Badge -->
                            <td class="py-4 px-6">
                                @php
                                    $user = $emp->user;
                                    $statusKey = $emp->statut;
                                    if ($user && $user->status) {
                                        $statusKey = $user->status;
                                    }
                                @endphp

                                @if($statusKey === 'invitation_sent' || $statusKey === 'invitation_pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-mono font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>Invitation Envoyée (48h)</span>
                                    </span>
                                @elseif($statusKey === 'actif' || $statusKey === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-[10px] font-mono font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Actif • 2FA Activé</span>
                                    </span>
                                @elseif($statusKey === 'suspended')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-500/20 text-[10px] font-mono font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        <span>Suspendu</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-mono font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>Désactivé</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Lucide Action Buttons Suite (40x40px, rounded-[12px], smooth hover animations) -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    <!-- 1. Eye → View Profile -->
                                    <button wire:click="edit({{ $emp->id }})" 
                                            title="Voir le profil"
                                            class="w-10 h-10 rounded-[12px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                        <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>

                                    <!-- 2. SquarePen → Edit Employee -->
                                    <button wire:click="edit({{ $emp->id }})" 
                                            title="Modifier l'employé"
                                            class="w-10 h-10 rounded-[12px] bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                        <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
                                        </svg>
                                    </button>

                                    <!-- 3. Mail / MailCheck → Send Invitation / Invitation Sent -->
                                    @if($emp->statut === 'invitation_sent' || $emp->statut === 'invitation_pending')
                                        <button wire:click="resendInvitation({{ $emp->id }})" 
                                                title="Envoyer l'invitation"
                                                class="w-10 h-10 rounded-[12px] bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="resendInvitation({{ $emp->id }})" 
                                                title="Invitation envoyée"
                                                class="w-10 h-10 rounded-[12px] bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/>
                                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                                <path d="m16 19 2 2 4-4"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- 4. KeyRound → Reset Password -->
                                    <button wire:click="resetPassword({{ $emp->id }})" 
                                            onclick="confirm('Confirmer l\'envoi d\'un email de réinitialisation de mot de passe à cet employé ?') || event.stopImmediatePropagation()"
                                            title="Réinitialiser le mot de passe"
                                            class="w-10 h-10 rounded-[12px] bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                        <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2 18v3c0 .6.4 1 1 1h4v-3h3v-3h2l1.4-1.4a6.5 6.5 0 1 0-4-4Z"/>
                                            <circle cx="16.5" cy="7.5" r=".5" fill="currentColor"/>
                                        </svg>
                                    </button>

                                    <!-- 5. ShieldCheck → Reset 2FA -->
                                    <button wire:click="resetTwoFactor({{ $emp->id }})" 
                                            onclick="confirm('Confirmer la réinitialisation de la clé 2FA TOTP pour cet employé ?') || event.stopImmediatePropagation()"
                                            title="Réinitialiser le 2FA"
                                            class="w-10 h-10 rounded-[12px] bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                        <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                            <path d="m9 12 2 2 4-4"/>
                                        </svg>
                                    </button>

                                    <!-- 6. UserX → Suspend / RotateCcw → Reactivate / UserCheck → Activate -->
                                    @if($emp->statut === 'actif' || $emp->statut === 'active')
                                        <button wire:click="toggleStatut({{ $emp->id }}, 'suspended')" 
                                                onclick="confirm('Confirmer la suspension de cet employé ?') || event.stopImmediatePropagation()"
                                                title="Suspendre l'employé"
                                                class="w-10 h-10 rounded-[12px] bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <line x1="17" x2="22" y1="11" y2="16"/>
                                                <line x1="22" x2="17" y1="11" y2="16"/>
                                            </svg>
                                        </button>
                                    @elseif($emp->statut === 'suspended')
                                        <button wire:click="toggleStatut({{ $emp->id }}, 'actif')" 
                                                title="Réactiver l'employé"
                                                class="w-10 h-10 rounded-[12px] bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 hover:bg-teal-100 dark:hover:bg-teal-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                                <path d="M3 3v5h5"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="toggleStatut({{ $emp->id }}, 'actif')" 
                                                title="Activer l'employé"
                                                class="w-10 h-10 rounded-[12px] bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <polyline points="16 11 18 13 22 9"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- 7. Trash2 → Delete Employee -->
                                    <button wire:click="confirmDelete({{ $emp->id }})" 
                                            title="Supprimer l'employé"
                                            class="w-10 h-10 rounded-[12px] bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900 transition-all duration-200 hover:scale-105 active:scale-95 flex items-center justify-center shadow-2xs">
                                        <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                            <line x1="10" x2="10" y1="11" y2="17"/>
                                            <line x1="14" x2="14" y1="11" y2="17"/>
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Aucun employé trouvé. Cliquez sur <strong class="text-indigo-600 dark:text-indigo-400">+ Inviter un Nouvel Employé</strong> pour commencer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- CREATE / EDIT MODAL -->
    @if($showModal)
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-xl w-full p-8 border border-slate-200 dark:border-slate-800 shadow-2xl space-y-6 text-slate-900 dark:text-slate-100 max-h-[90vh] overflow-y-auto">
                
                <div class="flex justify-between items-center border-b border-slate-200 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="font-black text-xl text-slate-900 dark:text-white">
                            {{ $isEditing ? 'Modifier le Profil Employé' : 'Inviter un Nouvel Employé' }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            {{ $isEditing ? 'Mettre à jour les informations professionnelles.' : 'Création du profil et envoi de l\'invitation d\'activation.' }}
                        </p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white font-bold">✕</button>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/80 text-xs text-indigo-900 dark:text-indigo-200 space-y-1">
                    <strong class="font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <span>Sécurité Enterprise Insurio:</span>
                    </strong>
                    <p class="text-[11px] leading-relaxed text-indigo-700 dark:text-indigo-300">
                        L'administrateur ne saisit et ne connaît JAMAIS le mot de passe de l'employé. Un lien d'activation sécurisé valable 48h sera transmis par email. L'employé créera son propre mot de passe et configurera 2FA lors de son premier accès.
                    </p>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs font-medium">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="prenom" value="Prénom *" />
                            <x-text-input wire:model="prenom" id="prenom" required placeholder="ex: Youssef" />
                            <x-input-error :messages="$errors->get('prenom')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="nom" value="Nom *" />
                            <x-text-input wire:model="nom" id="nom" required placeholder="ex: Benali" />
                            <x-input-error :messages="$errors->get('nom')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="email" value="Adresse Email Professionnelle *" />
                            <x-text-input wire:model="email" id="email" type="email" required placeholder="y.benali@agence-assurance.ma" />
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="telephone" value="Téléphone GSM" />
                            <x-text-input wire:model="telephone" id="telephone" placeholder="06 61 00 00 00" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="cin" value="N° CIN / Carte d'Identité" />
                            <x-text-input wire:model="cin" id="cin" placeholder="AB123456" />
                        </div>
                        <div>
                            <x-input-label for="succursale_id" value="Succursale / Rattachement *" />
                            <select wire:model="succursale_id" id="succursale_id" required 
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-slate-100 outline-none">
                                @foreach($succursales as $succ)
                                    <option value="{{ $succ->id }}">{{ $succ->nom }} ({{ $succ->ville }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('succursale_id')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="poste" value="Poste / Rôle Système *" />
                            <select wire:model="poste" id="poste" required 
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-900 dark:text-slate-100 outline-none">
                                <option value="Agent commercial">Agent commercial</option>
                                <option value="Responsable succursale">Responsable succursale</option>
                                <option value="Comptable">Comptable</option>
                                <option value="Administrateur">Administrateur Agence</option>
                                <option value="Consultation">Consultation uniquement</option>
                            </select>
                            <x-input-error :messages="$errors->get('poste')" class="mt-1" />
                        </div>

                        <div>
                            <x-input-label for="taux_commission_defaut" value="Taux Commission Défaut (%) *" />
                            <x-text-input wire:model="taux_commission_defaut" id="taux_commission_defaut" type="number" step="0.01" min="0" max="100" required placeholder="5.00" />
                            <x-input-error :messages="$errors->get('taux_commission_defaut')" class="mt-1" />
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                        <button type="button" wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 transition">
                            Annuler
                        </button>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold shadow-lg shadow-indigo-600/30 transition">
                            {{ $isEditing ? 'Mettre à Jour le Profil ➔' : 'Créer & Envoyer l\'Invitation ➔' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- SECURE DELETE GUARDRAILS MODAL -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 border border-slate-200 dark:border-slate-800 shadow-2xl space-y-5 text-slate-900 dark:text-slate-100">
                
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center font-bold text-xl border border-rose-500/20 shrink-0">
                        <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            <line x1="10" x2="10" y1="11" y2="17"/>
                            <line x1="14" x2="14" y1="11" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-slate-900 dark:text-white">Confirmation de Suppression</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Vérification des garde-fous de suppression d'employé.</p>
                    </div>
                </div>

                @if($deleteError)
                    <!-- Deletion Blocked Alert -->
                    <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/80 text-xs text-amber-900 dark:text-amber-200 space-y-2">
                        <strong class="font-bold flex items-center gap-1.5 text-amber-700 dark:text-amber-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Suppression Définitive Impossible</span>
                        </strong>
                        <p class="text-xs leading-relaxed text-amber-800 dark:text-amber-300">
                            This employee has related business records and cannot be permanently deleted. Suspend the employee instead.
                        </p>
                        <div class="p-2.5 bg-amber-100/60 dark:bg-amber-900/40 rounded-xl font-mono text-[11px]">
                            {{ $deleteError }}
                        </div>
                    </div>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 transition text-xs">
                            Fermer
                        </button>
                        <button type="button" wire:click="toggleStatut({{ $deletingEmployeId }}, 'suspended'); $set('showDeleteModal', false)" class="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-500 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <line x1="17" x2="22" y1="11" y2="16"/>
                                <line x1="22" x2="17" y1="11" y2="16"/>
                            </svg>
                            <span>Suspendre l'Employé à la Place</span>
                        </button>
                    </div>
                @else
                    <!-- Deletion Permitted -->
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Êtes-vous sûr de vouloir supprimer définitivement cet employé ? Aucun enregistrement commercial ou comptable n'y est actuellement lié. Cette action est irréversible.
                    </p>

                    <div class="pt-2 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 transition text-xs">
                            Annuler
                        </button>
                        <button type="button" wire:click="deleteEmployee" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-md transition inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            </svg>
                            <span>Confirmer la Suppression Définitive</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
