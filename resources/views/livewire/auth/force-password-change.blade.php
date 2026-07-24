<div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50 space-y-6">
    <!-- Header Title -->
    <div class="space-y-2 text-left">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-100 text-xs font-mono font-bold text-amber-700">
            <svg class="w-4 h-4 text-amber-600 shrink-0 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            <span>RENOUVELLEMENT DE SÉCURITÉ</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Mise à jour du Mot de Passe
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 font-medium">
            Votre mot de passe a expiré. Veuillez définir un nouveau mot de passe fort pour continuer.
        </p>
    </div>

    @if(!empty($policyErrors))
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl space-y-1">
            @foreach($policyErrors as $error)
                <p class="text-xs font-semibold text-rose-700 flex items-start gap-2">
                    <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <!-- Password Requirements Checklist -->
    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 text-xs space-y-2 font-mono">
        <span class="text-slate-500 font-bold uppercase text-[10px] block mb-1">Exigences de Sécurité du Mot de Passe :</span>
        <ul class="space-y-1.5 text-xs text-slate-600">
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span> Au moins 12 caractères</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span> Au moins une lettre majuscule (A-Z)</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span> Au moins un chiffre (0-9)</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span> Au moins un symbole spécial (!@#$%...)</li>
            <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></span> Impossible de réutiliser les 5 derniers mots de passe</li>
        </ul>
    </div>

    <form wire:submit.prevent="save" class="space-y-5">
        <div class="space-y-1.5 text-left">
            <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Nouveau Mot de Passe *
            </label>
            <input wire:model="password" id="password" type="password" placeholder="••••••••••••"
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400">
        </div>

        <div class="space-y-1.5 text-left">
            <label for="password_confirmation" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700">
                Confirmer le Mot de Passe *
            </label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" placeholder="••••••••••••"
                   class="w-full px-4 py-3.5 text-sm font-semibold text-slate-900 bg-white border border-slate-300 rounded-xl shadow-xs focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 outline-none transition-all placeholder:text-slate-400">
        </div>

        <div class="pt-2">
            <button type="submit" wire:loading.attr="disabled"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-all cursor-pointer">
                <span wire:loading.remove wire:target="save">Mettre à Jour le Mot de Passe</span>
                <span wire:loading wire:target="save">Mise à jour en cours...</span>
                <svg wire:loading.remove wire:target="save" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </form>
</div>
