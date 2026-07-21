<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Gestion & Paramètres de l'Agence</h1>
            <p class="text-sm text-slate-500">Configurez l'identité de l'agence, le serveur SMTP d'envoi d'e-mails, et suivez les limites de votre licence.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-250/60 rounded-xl text-emerald-800 text-sm font-medium">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <nav class="space-y-1 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm">
                    <button wire:click="setTab('general')" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ $activeTab === 'general' ? 'bg-teal-50 text-teal-700' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-5 w-5 mr-3 {{ $activeTab === 'general' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Identité Agence
                    </button>
                    <button wire:click="setTab('smtp')" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ $activeTab === 'smtp' ? 'bg-teal-50 text-teal-700' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-5 w-5 mr-3 {{ $activeTab === 'smtp' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Configuration SMTP
                    </button>
                    <button wire:click="setTab('whitelabel')" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ $activeTab === 'whitelabel' ? 'bg-teal-50 text-teal-700' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-5 w-5 mr-3 {{ $activeTab === 'whitelabel' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Identité Visuelle
                    </button>
                    <button wire:click="setTab('subscription')" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ $activeTab === 'subscription' ? 'bg-teal-50 text-teal-700' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-5 w-5 mr-3 {{ $activeTab === 'subscription' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Abonnement & Licence
                    </button>
                    <button wire:click="setTab('security')" class="w-full flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all {{ $activeTab === 'security' ? 'bg-teal-50 text-teal-700' : 'text-slate-650 hover:bg-slate-50 hover:text-slate-800' }}">
                        <svg class="h-5 w-5 mr-3 {{ $activeTab === 'security' ? 'text-teal-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Rôles & Permissions
                    </button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                    
                    <!-- TAB 1: General Settings -->
                    @if ($activeTab === 'general')
                        <form wire:submit.prevent="saveGeneral" class="space-y-6">
                            <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Identité & Fonctionnement de l'Agence</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Nom de l'Agence</label>
                                    <input wire:model="agency_name" type="text" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('agency_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Téléphone</label>
                                    <input wire:model="agency_phone" type="text" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('agency_phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Général</label>
                                    <input wire:model="agency_email" type="email" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('agency_email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Déclencheur de Commission</label>
                                    <select wire:model="commission_trigger" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                        <option value="vente">À la production (Vente validée)</option>
                                        <option value="encaissement">À l'encaissement (Règlement client)</option>
                                    </select>
                                    @error('commission_trigger') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Taux commission par défaut (Apporteur)</label>
                                    <div class="relative">
                                        <input wire:model="default_apporteur_rate" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                        <span class="absolute right-4 top-2.5 text-slate-400 text-sm font-bold">%</span>
                                    </div>
                                    @error('default_apporteur_rate') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Taux commission par défaut (Agent)</label>
                                    <div class="relative">
                                        <input wire:model="default_agent_rate" type="number" step="0.01" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                        <span class="absolute right-4 top-2.5 text-slate-400 text-sm font-bold">%</span>
                                    </div>
                                    @error('default_agent_rate') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Adresse Siège Social</label>
                                <textarea wire:model="agency_address" rows="3" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all"></textarea>
                                @error('agency_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- TAB 2: SMTP Settings -->
                    @if ($activeTab === 'smtp')
                        <form wire:submit.prevent="saveSMTP" class="space-y-6">
                            <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Configuration du Serveur E-mail SMTP</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Serveur SMTP (Host)</label>
                                    <input wire:model="mail_host" type="text" placeholder="smtp.mailtrap.io" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_host') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Port SMTP</label>
                                    <input wire:model="mail_port" type="text" placeholder="2525" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_port') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Chiffrement</label>
                                    <select wire:model="mail_encryption" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                        <option value="none">Aucun chiffrement</option>
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                    </select>
                                    @error('mail_encryption') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Identifiant de connexion</label>
                                    <input wire:model="mail_username" type="text" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_username') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Mot de passe</label>
                                    <input wire:model="mail_password" type="password" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-2 border-t border-slate-100 pt-4">
                                    <h3 class="text-sm font-bold text-slate-700 mb-4">Informations d'Expéditeur (From)</h3>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">E-mail Expéditeur</label>
                                    <input wire:model="mail_from_address" type="email" placeholder="no-reply@insurio.com" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_from_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Nom Expéditeur</label>
                                    <input wire:model="mail_from_name" type="text" placeholder="Insurio Assurance" class="w-full bg-slate-50 border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2.5 outline-none text-sm transition-all">
                                    @error('mail_from_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-100">
                                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-2.5 rounded-xl text-sm transition-colors shadow-sm">
                                    Enregistrer SMTP
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- TAB 3: Subscription & Licensing -->
                    @if ($activeTab === 'subscription')
                        <div class="space-y-6">
                            <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3">Détails de Licence & Abonnement</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-slate-50 border border-slate-200/60 p-5 rounded-2xl space-y-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Plan d'Abonnement Actif</span>
                                    <div class="text-xl font-extrabold text-slate-800">{{ $subscription['plan'] }}</div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-250/60">
                                        {{ $subscription['status'] }}
                                    </span>
                                </div>

                                <div class="bg-slate-50 border border-slate-200/60 p-5 rounded-2xl space-y-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Prochaine Échéance de Facturation</span>
                                    <div class="text-xl font-extrabold text-slate-800 font-mono">{{ Carbon\Carbon::parse($subscription['expires_at'])->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500 font-semibold">{{ $subscription['price'] }}</div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 pt-4 space-y-3">
                                <h3 class="text-sm font-bold text-slate-700">Limites d'utilisation de l'agence</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs font-semibold text-slate-600">
                                        <span>Utilisateurs actifs autorisés</span>
                                        <span>{{ App\Models\User::count() }} / {{ $subscription['max_users'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        <div class="bg-teal-600 h-2 rounded-full" style="width: {{ (App\Models\User::count() / (int)filter_var($subscription['max_users'], FILTER_SANITIZE_NUMBER_INT)) * 100 }}%"></div>
                                    </div>
                                    <p class="text-[11px] text-slate-400 italic">Pour modifier ou revaloriser votre abonnement, contactez le support Insurio Central.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                       <!-- TAB 4: Security and Roles -->
                    @if ($activeTab === 'security')
                        <div class="space-y-8">
                            <div class="border-b border-slate-100 pb-4">
                                <h2 class="text-lg font-bold text-slate-800">Contrôle d'Accès & Rôles</h2>
                                <p class="text-xs text-slate-500 mt-1">Gérez la visibilité des modules de l'application et restreignez les rôles autorisés à se connecter.</p>
                            </div>

                            <form wire:submit.prevent="saveAccessControl" class="space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    
                                    <!-- Left Column: Pages / Modules Checklist -->
                                    <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-450 border-b border-slate-100 pb-2">📂 Modules & Pages Actifs</h3>
                                        <p class="text-[11px] text-slate-500 mb-4">Décochez un module pour le masquer de la barre de navigation et en bloquer l'accès pour tous les collaborateurs.</p>

                                        <div class="space-y-3">
                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="dashboard" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Tableau de Bord</span>
                                                    <span class="text-[10px] text-slate-400">Statistiques globales et KPIs d'activité</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="automobile" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Registre Automobile</span>
                                                    <span class="text-[10px] text-slate-400">Gestion des contrats, avenants et clients</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="succursales" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Succursales & Branches</span>
                                                    <span class="text-[10px] text-slate-400">Gestion des bureaux physiques locaux</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="employes" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Collaborateurs & Employés</span>
                                                    <span class="text-[10px] text-slate-400">Comptes utilisateurs et affectations</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="commissions" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Validation des Commissions</span>
                                                    <span class="text-[10px] text-slate-400">Validation, règlements et barèmes de commissions</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_pages" value="charges" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Gestion des Dépenses & Charges</span>
                                                    <span class="text-[10px] text-slate-400">Suivi des loyers, factures, salaires</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Right Column: Roles Active Status -->
                                    <div class="bg-slate-50/50 border border-slate-200/80 rounded-2xl p-6 space-y-4">
                                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-450 border-b border-slate-100 pb-2">👥 Rôles Autorisés</h3>
                                        <p class="text-[11px] text-slate-500 mb-4">Décochez un rôle pour bloquer la connexion et déconnecter immédiatement tous les utilisateurs ayant ce rôle.</p>

                                        <div class="space-y-3">
                                            <div class="flex items-center gap-3 p-3 bg-slate-100/80 border border-slate-200 rounded-xl opacity-75">
                                                <input type="checkbox" checked disabled class="rounded border-slate-300 text-teal-600 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Administrateur (agency-admin)</span>
                                                    <span class="text-[10px] text-slate-400">Rôle requis obligatoire. Toujours actif.</span>
                                                </div>
                                            </div>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_roles" value="responsable-succursale" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Directeur de Succursale (responsable-succursale)</span>
                                                    <span class="text-[10px] text-slate-400">Gère sa succursale et valide les commissions</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_roles" value="agent-commercial" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Agent Commercial (agent-commercial)</span>
                                                    <span class="text-[10px] text-slate-400">Saisie des contrats et historique personnel</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_roles" value="comptable" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Comptable (comptable)</span>
                                                    <span class="text-[10px] text-slate-400">Valide les décaissements et factures</span>
                                                </div>
                                            </label>

                                            <label class="flex items-center gap-3 p-3 bg-white border border-slate-150 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                <input type="checkbox" wire:model="enabled_roles" value="consultation" class="rounded border-slate-300 text-teal-650 focus:ring-teal-500 h-4 w-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-slate-800 block">Lecteur seul (consultation)</span>
                                                    <span class="text-[10px] text-slate-400">Accès en lecture seule pour audit</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-150">
                                    <button type="submit" class="bg-teal-650 hover:bg-teal-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                                        Enregistrer les autorisations d'accès
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if ($activeTab === 'whitelabel')
                        <div class="space-y-8">
                            <!-- Header -->
                            <div class="border-b border-slate-100 pb-4">
                                <h2 class="text-lg font-bold text-slate-800">Personnalisation Visuelle (White-Label)</h2>
                                <p class="text-xs text-slate-500 mt-1">Configurez le logo et la couleur d'accentuation pour personnaliser l'identité visuelle de votre espace.</p>
                            </div>

                            <form wire:submit.prevent="saveWhiteLabel" class="space-y-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-150 shadow-sm">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-450 border-b border-slate-100 pb-2">🎨 Identité Visuelle de l'Agence</h3>

                                <!-- Upload Logo -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Logo de l'application</label>
                                        <div class="flex items-center gap-4">
                                            @if ($logo)
                                                <img src="{{ $logo->temporaryUrl() }}" class="h-12 w-32 object-contain bg-white border border-slate-200 rounded-xl p-2">
                                            @elseif (tenant('logo_path'))
                                                <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-12 w-32 object-contain bg-white border border-slate-200 rounded-xl p-2">
                                            @else
                                                <div class="h-12 w-32 flex items-center justify-center border border-dashed border-slate-300 rounded-xl bg-slate-100 text-[10px] text-slate-450 font-bold uppercase">Aucun logo</div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <input type="file" wire:model="logo" class="hidden" id="logo_input">
                                                <label for="logo_input" class="inline-flex items-center px-4 py-2 border border-slate-250/80 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 cursor-pointer transition-all">
                                                    Sélectionner logo
                                                </label>
                                                <p class="text-[9px] text-slate-400 mt-1.5">PNG, JPG ou SVG (Max: 2Mo).</p>
                                            </div>
                                        </div>
                                        @error('logo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Upload Favicon -->
                                    <div>
                                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Favicon du navigateur</label>
                                        <div class="flex items-center gap-4">
                                            @if ($favicon)
                                                <img src="{{ $favicon->temporaryUrl() }}" class="h-10 w-10 object-contain bg-white border border-slate-200 rounded-xl p-1">
                                            @elseif (tenant('favicon_path'))
                                                <img src="{{ asset('storage/' . tenant('favicon_path')) }}" class="h-10 w-10 object-contain bg-white border border-slate-200 rounded-xl p-1">
                                            @else
                                                <div class="h-10 w-10 flex items-center justify-center border border-dashed border-slate-300 rounded-xl bg-slate-100 text-[10px] text-slate-450 font-bold">ICO</div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <input type="file" wire:model="favicon" class="hidden" id="fav_input">
                                                <label for="fav_input" class="inline-flex items-center px-4 py-2 border border-slate-250/80 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 cursor-pointer transition-all">
                                                    Sélectionner favicon
                                                </label>
                                                <p class="text-[9px] text-slate-400 mt-1.5">ICO, PNG ou JPG (Max: 512Ko).</p>
                                            </div>
                                        </div>
                                        @error('favicon') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Theme Color Pickers -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-150">
                                    <div>
                                        <label for="color_primary" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Couleur Principale (Accent)</label>
                                        <div class="flex gap-2">
                                            <input type="color" id="color_primary" wire:model="couleur_primaire" class="h-10 w-14 border border-slate-250/80 rounded-xl cursor-pointer p-0">
                                            <input type="text" wire:model="couleur_primaire" class="flex-1 bg-white border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2 outline-none text-xs font-mono transition-all">
                                        </div>
                                        @error('couleur_primaire') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="color_secondary" class="block text-xs font-bold uppercase tracking-wider text-slate-455 mb-2">Couleur Secondaire (Hover)</label>
                                        <div class="flex gap-2">
                                            <input type="color" id="color_secondary" wire:model="couleur_secondaire" class="h-10 w-14 border border-slate-250/80 rounded-xl cursor-pointer p-0">
                                            <input type="text" wire:model="couleur_secondaire" class="flex-1 bg-white border border-slate-250 focus:border-teal-500 focus:ring-teal-500 rounded-xl px-4 py-2 outline-none text-xs font-mono transition-all">
                                        </div>
                                        @error('couleur_secondaire') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-150">
                                    <button type="submit" class="bg-teal-650 hover:bg-teal-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                                        Enregistrer les paramètres visuels
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
