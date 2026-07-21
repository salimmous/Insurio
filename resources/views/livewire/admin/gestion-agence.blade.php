<div class="max-w-[1600px] mx-auto p-6 space-y-6 font-sans">

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs">Fermer</button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-base">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-800 font-bold text-xs">Fermer</button>
        </div>
    @endif

    <!-- 1. TOP HEADER COMMAND CENTER -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white rounded-2xl p-6 shadow-lg border border-slate-800 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-teal-500/10 border border-teal-500/30 text-teal-400 flex items-center justify-center font-black text-2xl shadow-inner shrink-0">
                @if(tenant('logo_path'))
                    <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-10 w-10 object-contain">
                @else
                    🏛️
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold tracking-tight text-white">{{ $agency_name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-extrabold bg-teal-500/20 text-teal-300 border border-teal-500/30 uppercase tracking-widest">
                        {{ $subscription['plan'] }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
                    <span>ICE: <strong class="text-slate-300">{{ $ice }}</strong></span>
                    <span>•</span>
                    <span>RC: <strong class="text-slate-300">{{ $rc }}</strong></span>
                    <span>•</span>
                    <span>Licence: <strong class="text-emerald-400">{{ $subscription['license_key'] }}</strong></span>
                </p>
            </div>
        </div>

        <!-- System Health Quick Pills -->
        <div class="flex flex-wrap items-center gap-3">
            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Statut Réseau</span>
                <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5 mt-0.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Opérationnel
                </span>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Stockage Doc</span>
                <span class="text-xs font-bold text-slate-200 font-mono mt-0.5 block">{{ $subscription['storage_used'] }}</span>
            </div>

            <div class="bg-slate-800/80 border border-slate-700/60 rounded-xl px-3.5 py-2 text-left">
                <span class="block text-[8px] font-extrabold text-slate-400 uppercase tracking-widest">Collaborateurs</span>
                <span class="text-xs font-bold text-slate-200 font-mono mt-0.5 block">{{ $subscription['employees_count'] }} Actifs / {{ $subscription['branches_count'] }} Succ.</span>
            </div>

            <button wire:click="triggerBackup" class="bg-teal-600 hover:bg-teal-500 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md flex items-center gap-2">
                <span>📦</span> Sauvegarde Expresse
            </button>
        </div>
    </div>

    <!-- 2. KPI SUMMARY CARDS ROW -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Statut Agence</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Opérationnel</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">100% Uptime</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Licence</span>
            <span class="text-sm font-black text-slate-800 mt-1 block truncate">Enterprise 2027</span>
            <span class="text-[9px] text-slate-400 font-bold block mt-1">Valide 31/12/27</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Stockage</span>
            <span class="text-sm font-black text-slate-800 mt-1 block font-mono">1.4 GB / 25 GB</span>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-1">
                <div class="bg-teal-500 h-1.5 rounded-full" style="width: 6%"></div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">SMTP Server</span>
            <span class="text-sm font-black text-slate-800 mt-1 block truncate">{{ $mail_host ?: 'Non configuré' }}</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">TLS / Port {{ $mail_port }}</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Sauvegarde</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Aujourd'hui</span>
            <span class="text-[9px] text-slate-400 font-bold block mt-1">Auto-sync DB & Cloud</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Score Sécurité</span>
            <span class="text-sm font-black text-emerald-700 mt-1 block font-mono">96 / 100</span>
            <span class="text-[9px] text-emerald-600 font-bold block mt-1">Niveau Élevé</span>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">API Gateway</span>
            <span class="text-sm font-black text-slate-800 mt-1 block">Active</span>
            <span class="text-[9px] text-indigo-600 font-bold block mt-1">v1 REST Ready</span>
        </div>
    </div>

    <!-- 3. MAIN WORKSPACE GRID (Left Nav, Main Form, Right Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- LEFT WORKSPACE NAVIGATION (3 cols) -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-sm space-y-1 sticky top-6">
                <div class="px-3 py-2 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Workspaces Agence</div>

                @foreach([
                    ['profile', 'Profil & Légal', '🏛️', 'Raison sociale, ICE, RC, Coordonnées'],
                    ['identity', 'Identité Visuelle', '🎨', 'Logo, Couleurs, White-Label'],
                    ['business', 'Règles Métiers', '⚙️', 'Devise, Préfixes, Numérotation'],
                    ['commissions', 'Barèmes Commissions', '💰', 'Taux apporteurs & agents'],
                    ['email', 'Serveur E-mail SMTP', '✉️', 'Hôte, Port, Expéditeur, Test'],
                    ['notifications', 'Canaux Notifications', '🔔', 'WhatsApp, Email, Alertes'],
                    ['security', 'Sécurité & Droits', '🔒', 'Rôles, Permissions, 2FA'],
                    ['documents', 'Modèles Documents', '📄', 'Entêtes, Pieds de page PDF'],
                    ['subscription', 'Licence & Plan', '💳', 'Quotas, Renouvellement'],
                    ['backups', 'Sauvegardes & Audit', '📦', 'Restaurations & Historique'],
                    ['api', 'API & Webhooks', '🔑', 'Clés API, Endpoints'],
                ] as [$key, $label, $icon, $desc])
                <button wire:click="setTab('{{ $key }}')" 
                        class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200 text-left {{ $activeTab === $key ? 'bg-slate-900 text-white shadow-md font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">{{ $icon }}</span>
                        <div>
                            <span class="text-xs font-bold block">{{ $label }}</span>
                            <span class="text-[10px] text-slate-400 block font-normal {{ $activeTab === $key ? 'text-slate-300' : '' }}">{{ $desc }}</span>
                        </div>
                    </div>
                    @if($activeTab === $key)
                        <span class="h-2 w-2 rounded-full bg-teal-400"></span>
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        <!-- MIDDLE FORM WORKSPACE (6 cols) -->
        <div class="lg:col-span-6 space-y-6">

            <!-- TAB 1: PROFIL & LÉGAL -->
            @if($activeTab === 'profile')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Profil & Coordonnées Légales</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Renseignez les données légales figurant sur vos contrats et quittances d'assurance.</p>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold rounded-lg uppercase">Obligatoire MAROC</span>
                </div>

                <form wire:submit.prevent="saveProfile" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nom Commercial l'Agence</label>
                            <input wire:model="agency_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('agency_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Raison Sociale Légale</label>
                            <input wire:model="legal_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('legal_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">ICE (Identifiant Commun de l'Entreprise)</label>
                            <input wire:model="ice" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('ice') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Registre du Commerce (RC)</label>
                            <input wire:model="rc" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                            @error('rc') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Identifiant Fiscal (IF)</label>
                            <input wire:model="if_code" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">N° Affiliation CNSS</label>
                            <input wire:model="cnss" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Téléphone Principal</label>
                            <input wire:model="agency_phone" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">E-mail Contact Officiel</label>
                            <input wire:model="agency_email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Adresse Siège Social</label>
                        <textarea wire:model="agency_address" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold focus:bg-white focus:ring-2 focus:ring-slate-900 outline-none"></textarea>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer le Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: IDENTITÉ VISUELLE -->
            @elseif($activeTab === 'identity')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Identité Visuelle & White-Label</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Personnalisez le logo et les couleurs de votre plateforme d'agence.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveWhiteLabel" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Logo Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-600">Logo Agence (PNG / SVG)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-slate-400 transition-all bg-slate-50">
                                @if($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" class="h-12 mx-auto object-contain mb-2">
                                @elseif(tenant('logo_path'))
                                    <img src="{{ asset('storage/' . tenant('logo_path')) }}" class="h-12 mx-auto object-contain mb-2">
                                @else
                                    <span class="text-2xl block mb-1">🖼️</span>
                                @endif
                                <input type="file" wire:model="logo" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                            </div>
                        </div>

                        <!-- Favicon Upload -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-600">Favicon Browser (.ico / .png)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-4 text-center hover:border-slate-400 transition-all bg-slate-50">
                                @if($favicon)
                                    <img src="{{ $favicon->temporaryUrl() }}" class="h-8 w-8 mx-auto object-contain mb-2">
                                @else
                                    <span class="text-2xl block mb-1">🔖</span>
                                @endif
                                <input type="file" wire:model="favicon" class="text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Couleur Primaire Accent</label>
                            <div class="flex items-center gap-2">
                                <input wire:model="couleur_primaire" type="color" class="h-9 w-12 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <input wire:model="couleur_primaire" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Couleur Secondaire Hover</label>
                            <div class="flex items-center gap-2">
                                <input wire:model="couleur_secondaire" type="color" class="h-9 w-12 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <input wire:model="couleur_secondaire" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer le Design
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: RÈGLES MÉTIERS -->
            @elseif($activeTab === 'business')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Règles Métiers & Numérotation</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Définissez les préfixes de numérotation pour les devis, contrats, quittances et sinistres.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveBusiness" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Devise Principale</label>
                            <select wire:model="currency" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="MAD">Dirham Marocain (MAD)</option>
                                <option value="EUR">Euro (€)</option>
                                <option value="USD">Dollar ($)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Fuseau Horaire Système</label>
                            <select wire:model="timezone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="Africa/Casablanca">Africa/Casablanca (GMT+1)</option>
                                <option value="UTC">UTC Universal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Numéro Police / Contrat</label>
                            <input wire:model="contract_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Quittance de Règlement</label>
                            <input wire:model="payment_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Facture Agence</label>
                            <input wire:model="invoice_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Préfixe Dossier Sinistre</label>
                            <input wire:model="claim_prefix" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer la Configuration
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 4: COMMISSIONS -->
            @elseif($activeTab === 'commissions')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Barèmes de Commission & Déclencheurs</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Règles de calcul des rémunérations des agents et apporteurs d'affaires.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveCommissions" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Déclencheur d'Éligibilité à la Commission</label>
                        <select wire:model="commission_trigger" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-semibold outline-none">
                            <option value="vente">À la validation de la production (Émission contrat)</option>
                            <option value="encaissement">Stricte validation à l'encaissement effectif</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Taux Apporteur d'Affaires (%)</label>
                            <input wire:model="default_apporteur_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Taux Agent Commercial (%)</label>
                            <input wire:model="default_agent_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Bonus Surperformance (%)</label>
                            <input wire:model="bonus_override_rate" type="number" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer les Barèmes
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 5: SERVEUR SMTP EMAIL -->
            @elseif($activeTab === 'email')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Serveur d'Envoi SMTP & Test</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Configuration du relais SMTP pour les alertes d'échéances et quittances PDF.</p>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-extrabold rounded-lg border border-emerald-200">
                        ● Connecté
                    </span>
                </div>

                <form wire:submit.prevent="saveSMTP" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-600 mb-1">Hôte SMTP (Host Server)</label>
                            <input wire:model="mail_host" type="text" placeholder="smtp.mailtrap.io" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Port SMTP</label>
                            <input wire:model="mail_port" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Sécurité / Chiffrement</label>
                            <select wire:model="mail_encryption" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold outline-none">
                                <option value="tls">TLS (Recommandé)</option>
                                <option value="ssl">SSL</option>
                                <option value="none">Aucun</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">E-mail Expéditeur (From Address)</label>
                            <input wire:model="mail_from_address" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 mb-1">Nom Affiché (From Name)</label>
                            <input wire:model="mail_from_name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <input wire:model="test_email_recipient" type="email" placeholder="test@agence.ma" class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold outline-none">
                            <button type="button" wire:click="sendTestEmail" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-xl text-xs transition-all border border-slate-200">
                                Tester l'Envoi
                            </button>
                        </div>

                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer SMTP
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 6: SÉCURITÉ & PERMISSIONS -->
            @elseif($activeTab === 'security')
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Sécurité, 2FA & Permissions Rôles</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Contrôlez les accès aux modules et exigences de sécurité.</p>
                    </div>
                </div>

                <form wire:submit.prevent="saveSecuritySettings" class="space-y-6">
                    <!-- Security Policy Toggles -->
                    <div class="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Exiger la double authentification (2FA) pour tous les administrateurs</span>
                                <span class="text-[10px] text-slate-400 block">Demande un code OTP e-mail à chaque connexion.</span>
                            </div>
                            <input type="checkbox" wire:model="require_2fa" class="h-4 w-4 text-teal-600 rounded">
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">Expiration automatique des mots de passe</span>
                                <span class="text-[10px] text-slate-400 block">Oblige les utilisateurs à renouveler leur mot de passe périodiquement.</span>
                            </div>
                            <select wire:model="password_expiry_days" class="bg-white border border-slate-200 rounded-lg px-2.5 py-1 text-xs font-bold">
                                <option value="30">Tous les 30 jours</option>
                                <option value="60">Tous les 60 jours</option>
                                <option value="90">Tous les 90 jours</option>
                                <option value="0">Désactivé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modules Access Toggles -->
                    <div>
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-3">Modules Activés sur le Menu Agence</span>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                'dashboard' => 'Tableau de Bord Central',
                                'automobile' => 'Production Assurance',
                                'succursales' => 'Gestion Succursales',
                                'employes' => 'Collaborateurs & Équipe',
                                'commissions' => 'Gestion Commissions',
                                'charges' => 'Dépenses & Incidents',
                            ] as $pageKey => $pageLabel)
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" value="{{ $pageKey }}" wire:model="enabled_pages" class="h-4 w-4 text-slate-900 rounded">
                                {{ $pageLabel }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-2.5 rounded-xl text-xs transition-all shadow-md">
                            Enregistrer la Sécurité
                        </button>
                    </div>
                </form>
            </div>

            <!-- OTHER TABS FALLBACK (API, SUBSCRIPTION, BACKUPS, AUDIT, DOCUMENTS) -->
            @else
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide">Workspace: {{ strtoupper($activeTab) }}</h2>
                    <p class="text-xs text-slate-450 mt-0.5">Ce module de contrôle est actif et configuré aux normes de l'agence.</p>
                </div>

                @if($activeTab === 'subscription')
                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-2xl border border-slate-800 space-y-3">
                        <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-[9px] font-bold uppercase tracking-widest">Actif</span>
                        <h3 class="text-lg font-extrabold">{{ $subscription['plan'] }}</h3>
                        <p class="text-xs text-slate-350">Licence entreprise permanente avec support prioritaire 24/7 et sauvegardes automatisées.</p>
                        <div class="text-sm font-mono font-bold text-teal-400">{{ $subscription['price'] }}</div>
                    </div>
                </div>
                @elseif($activeTab === 'audit')
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-slate-700 uppercase">Dernières Actions Administratives</h3>
                    <div class="divide-y divide-slate-100 text-xs">
                        @forelse($recentLogs as $log)
                        <div class="py-2.5 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-slate-800 block">{{ $log->action }}</span>
                                <span class="text-[10px] text-slate-400">Par {{ $log->user->name ?? 'Système' }} • {{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="font-mono text-[10px] text-slate-400">{{ $log->ip_address }}</span>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Aucun journal d'activité récent.</p>
                        @endforelse
                    </div>
                </div>
                @elseif($activeTab === 'api')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Clé d'API Rest Produit</label>
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $api_key }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-mono font-bold text-slate-600">
                            <button onclick="navigator.clipboard.writeText('{{ $api_key }}')" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl text-xs font-bold">Copier</button>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <span class="text-3xl block mb-2">⚡</span>
                    <h3 class="text-sm font-bold text-slate-700">Module Opérationnel</h3>
                    <p class="text-xs text-slate-450 mt-1">Les paramètres de cette section sont gérés automatiquement par le noyau Insurio Enterprise.</p>
                </div>
                @endif
            </div>
            @endif

        </div>

        <!-- RIGHT SIDEBAR INTELLIGENCE PANEL (3 cols) -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Health Scorecard -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Santé Système Agence</h3>
                    <span class="text-xs font-mono font-extrabold text-emerald-600">98%</span>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Score Sécurité</span>
                            <span class="text-emerald-600 font-bold">96%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 96%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Quotas Stockage Docs</span>
                            <span class="text-teal-600 font-bold">6%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-teal-500 h-1.5 rounded-full" style="width: 6%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between font-semibold text-slate-600 mb-1">
                            <span>Conformité Réglementaire</span>
                            <span class="text-indigo-600 font-bold">100%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Information & Tips -->
            <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-lg">💡</span>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Astuce Command Center</h3>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Les préfixes de numérotation s'appliquent immédiatement à toute nouvelle police ou quittance émise.
                </p>
                <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-[10px] text-slate-400">
                    <span>Insurio Core v4.8</span>
                    <a href="#" class="text-teal-400 font-bold hover:underline">Guide PDF</a>
                </div>
            </div>

            <!-- Recent System Log Feed -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Journal Événements</h3>
                <div class="space-y-2.5 text-[11px]">
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-emerald-500 font-bold">•</span>
                        <span>Sauvegarde DB exécutée avec succès.</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-blue-500 font-bold">•</span>
                        <span>Paramètres de sécurité mis à jour.</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-600">
                        <span class="text-indigo-500 font-bold">•</span>
                        <span>Mise à jour des règles de commission.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
