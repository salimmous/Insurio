<div class="p-6 space-y-6 font-sans">
    <div>
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestion des Clients</h1>
                <p class="text-sm text-gray-500">Gérez les clients particuliers de votre portefeuille d'assurances.</p>
            </div>
            <button wire:click="openModal" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouveau Client
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">{{ $clients->total() }}</span>
                    <span class="text-sm text-gray-500">Clients particuliers</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ \App\Models\Client::where('client_type', 'individual')->whereNotNull('phone')->where('phone', '!=', '')->count() }}
                    </span>
                    <span class="text-sm text-gray-500">Avec Téléphone renseigné</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg mr-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-gray-900">
                        {{ \App\Models\Client::where('client_type', 'individual')->whereNotNull('email')->where('email', '!=', '')->count() }}
                    </span>
                    <span class="text-sm text-gray-500">Avec E-mail renseigné</span>
                </div>
            </div>
        </div>

        <!-- Filter / Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6 flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Rechercher par Référence (ex: CL-00001), Nom, CIN, Téléphone, Email..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>

        <!-- Clients List -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
            <!-- Desktop View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Réf. Client</th>
                            <th class="px-6 py-3">Nom Complet</th>
                            <th class="px-6 py-3">CIN</th>
                            <th class="px-6 py-3">Téléphone</th>
                            <th class="px-6 py-3">E-mail</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-900">
                        @forelse($clients as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-2xs">
                                        {{ $client->formatted_reference }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-indigo-600 hover:text-indigo-900">
                                        <a href="{{ route('admin.clients.profile', $client->id) }}" class="hover:underline">
                                            {{ $client->first_name }} {{ $client->last_name }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $client->address ?? 'Pas d\'adresse renseignée' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-gray-700 text-xs">
                                    {{ $client->cin ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-600 text-xs">
                                    {{ $client->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $client->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    <a href="{{ route('admin.clients.profile', $client->id) }}" class="text-emerald-600 hover:text-emerald-900 font-medium">Profil CRM</a>
                                    <button wire:click="openModal({{ $client->id }})" class="text-indigo-600 hover:text-indigo-900 font-medium">Modifier</button>
                                    <button onclick="confirm('Supprimer ce client ?') || event.stopImmediatePropagation()" wire:click="delete({{ $client->id }})" class="text-rose-600 hover:text-rose-900 font-medium">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                    Aucun client particulier enregistré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($clients as $client)
                    <div class="p-4 flex flex-col gap-2 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-mono font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 rounded mb-1">
                                    {{ $client->formatted_reference }}
                                </span>
                                <a href="{{ route('admin.clients.profile', $client->id) }}" class="font-bold text-indigo-650 hover:underline block">
                                    {{ $client->first_name }} {{ $client->last_name }}
                                </a>
                                <span class="text-xs text-gray-400 font-mono block">CIN: {{ $client->cin ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 flex justify-between items-center pt-2 border-t border-gray-100">
                            <span>📞 {{ $client->phone ?? '-' }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.clients.profile', $client->id) }}" class="text-emerald-600 font-medium">CRM</a>
                                <button wire:click="openModal({{ $client->id }})" class="text-indigo-600 font-medium">Modifier</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">
                        Aucun client trouvé.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-200">
                {{ $clients->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Client Form (Cleaned & Streamlined) -->
    @if($isModalOpen)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50 overflow-y-auto">
            <div class="bg-white rounded-xl max-w-xl w-full p-6 space-y-4 shadow-xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $clientId ? 'Modifier le Client' : 'Nouveau Client Particulier' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs font-medium">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-bold text-gray-700 mb-1">Référence Client (Auto)</label>
                            <input type="text" wire:model="reference" placeholder="Auto-généré ex: CL-00001" class="w-full border border-gray-300 rounded-lg p-2.5 font-mono bg-gray-50">
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Prénom *</label>
                            <input type="text" wire:model="first_name" placeholder="Prénom" class="w-full border border-gray-300 rounded-lg p-2.5">
                            @error('first_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Nom *</label>
                            <input type="text" wire:model="last_name" placeholder="Nom" class="w-full border border-gray-300 rounded-lg p-2.5">
                            @error('last_name') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1">CIN / Carte Nationale</label>
                            <input type="text" wire:model="cin" placeholder="ex: AB123456" class="w-full border border-gray-300 rounded-lg p-2.5 font-mono">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Téléphone</label>
                            <input type="text" wire:model="phone" placeholder="ex: 0661234567" class="w-full border border-gray-300 rounded-lg p-2.5">
                        </div>

                        <div>
                            <label class="block font-bold text-gray-700 mb-1">WhatsApp Direct</label>
                            <input type="text" wire:model="whatsapp_number" placeholder="ex: 0661234567" class="w-full border border-gray-300 rounded-lg p-2.5">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-1">Adresse E-mail</label>
                            <input type="email" wire:model="email" placeholder="client@domaine.ma" class="w-full border border-gray-300 rounded-lg p-2.5">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Adresse Complète</label>
                        <textarea wire:model="address" rows="2" placeholder="Adresse physique du client" class="w-full border border-gray-300 rounded-lg p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50">Annuler</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-md">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
