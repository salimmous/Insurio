<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight dark:text-white">Compagnies d'Assurance</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Gérez les assureurs partenaires et leurs contacts de liaison.</p>
        </div>
        <button wire:click="create" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Ajouter un Assureur
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search -->
    <div class="mb-6 bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700">
        <input type="text" wire:model.live="search" placeholder="Rechercher par nom ou code..." 
               class="w-full max-w-md px-4 py-2.5 border border-slate-200 dark:border-slate-650 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
    </div>

    <!-- Insurers List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($insurers as $item)
            <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
                <div>
                    <!-- Logo / Badge -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="h-12 w-12 rounded-xl bg-slate-50 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-400 text-lg uppercase border border-slate-100 dark:border-slate-600">
                            {{ substr($item->name, 0, 2) }}
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $item->active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                            {{ $item->active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>

                    <!-- Insurer details -->
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ $item->name }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mb-4">CODE: {{ $item->code ?? 'N/A' }}</p>

                    <div class="space-y-2 text-xs text-slate-550 dark:text-slate-400 mb-6">
                        <div class="flex items-center">
                            <span class="font-semibold w-16">Contact:</span>
                            <span>{{ $item->contact ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold w-16">Tél:</span>
                            <span>{{ $item->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="font-semibold w-16">Email:</span>
                            <span class="truncate">{{ $item->email ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-2 border-t border-slate-50 dark:border-slate-750 pt-4">
                    <button wire:click="edit({{ $item->id }})" class="px-3 py-1.5 border border-slate-200 dark:border-slate-650 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-350 rounded-lg text-xs font-semibold">
                        Modifier
                    </button>
                    <button wire:click="delete({{ $item->id }})" class="px-3 py-1.5 border border-transparent hover:bg-rose-50 dark:hover:bg-rose-950/20 text-rose-600 dark:text-rose-450 rounded-lg text-xs font-semibold">
                        Supprimer
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Form -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 w-full max-w-lg overflow-hidden transition-all transform scale-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                        {{ $editingInsurerId ? 'Modifier l\'Assureur' : 'Nouvel Assureur' }}
                    </h3>
                    <button wire:click="$set('isOpen', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Nom de la Compagnie</label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Code</label>
                            <input type="text" wire:model="code" placeholder="ex: SANLAM" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Logo URL</label>
                            <input type="text" wire:model="logo" placeholder="ex: /images/logos/sanlam.png" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Adresse</label>
                            <input type="text" wire:model="address" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Téléphone</label>
                            <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Nom du Contact</label>
                            <input type="text" wire:model="contact" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-750 dark:text-slate-300 mb-1">Email de contact</label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-650 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        </div>
                        <div class="col-span-2 flex items-center space-x-2 pt-2">
                            <input type="checkbox" wire:model="active" id="activeToggle" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                            <label for="activeToggle" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Activer cette compagnie</label>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('isOpen', false)" class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-700">
                        Annuler
                    </button>
                    <button type="button" wire:click="save" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
