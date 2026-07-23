<div class="space-y-6">

    <!-- Flash Messages -->
    @if(session()->has('message'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-xs font-semibold text-rose-700 dark:text-rose-300 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Page Header & Action Controls -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                <span>🛡️ GESTION DES ÉQUIPES & SÉCURITÉ 2FA</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Gestion des Employés & Invitations
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Créez des profils employés et envoyez des liens d'activation sécurisés (2FA Obligatoire).
            </p>
        </div>

        <button wire:click="openCreateModal" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-extrabold text-xs px-6 py-3.5 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all">
            <span>+ Inviter un Nouvel Employé</span>
        </button>
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

    <!-- Table of Employees -->
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
                        <th class="py-4 px-6 text-right">Actions de Sécurité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-medium">
                    @forelse($employes as $emp)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                            <!-- Employee & Matricule -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm border border-indigo-500/20">
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
                                        <span>⏸️ Suspendu</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 text-[10px] font-mono font-bold">
                                        <span>🚫 Désactivé</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right space-x-1">
                                <!-- Resend Invitation -->
                                @if($emp->statut === 'invitation_sent' || $emp->statut === 'invitation_pending')
                                    <button wire:click="resendInvitation({{ $emp->id }})" title="Réenvoyer l'email d'invitation (48h)"
                                            class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition">
                                        📩
                                    </button>
                                    <button wire:click="revokeInvitation({{ $emp->id }})" title="Révoquer l'invitation"
                                            class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 transition">
                                        ❌
                                    </button>
                                @endif

                                <!-- Security Controls for Active Users -->
                                @if($emp->statut === 'actif' || $emp->statut === 'active')
                                    <button wire:click="resetTwoFactor({{ $emp->id }})" title="Réinitialiser 2FA TOTP"
                                            class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 transition">
                                        🛡️
                                    </button>
                                    <button wire:click="forcePasswordReset({{ $emp->id }})" title="Forcer la réinitialisation de mot de passe"
                                            class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 transition">
                                        🔑
                                    </button>
                                    <button wire:click="toggleStatut({{ $emp->id }}, 'suspended')" title="Suspendre l'accès"
                                            class="p-2 rounded-xl bg-orange-50 dark:bg-orange-950/60 text-orange-600 dark:text-orange-400 hover:bg-orange-100 transition">
                                        ⏸️
                                    </button>
                                @endif

                                @if($emp->statut === 'suspended' || $emp->statut === 'disabled')
                                    <button wire:click="toggleStatut({{ $emp->id }}, 'actif')" title="Réactiver l'employé"
                                            class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 transition">
                                        ▶️
                                    </button>
                                @endif

                                <!-- Edit Profile -->
                                <button wire:click="edit({{ $emp->id }})" title="Modifier le profil"
                                        class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                                    ✏️
                                </button>
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

                <!-- Security Disclaimer Banner -->
                <div class="p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/80 text-xs text-indigo-900 dark:text-indigo-200 space-y-1">
                    <strong class="font-bold block">🔒 Sécurité Enterprise Insurio:</strong>
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

</div>
