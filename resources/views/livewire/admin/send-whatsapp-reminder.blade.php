<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 w-full max-w-lg overflow-hidden transition-all transform scale-100">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                        <svg class="w-6 h-6 text-emerald-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984a9.96 9.96 0 001.37 5.054L2 22l5.106-1.341a9.9 9.9 0 004.912 1.3c5.517 0 9.997-4.487 9.999-10C22.02 6.477 17.528 2 12.012 2zm5.79 14.214c-.247.697-1.428 1.365-1.968 1.455-.49.083-.984.341-3.14-.515-2.73-1.085-4.49-3.864-4.626-4.045-.137-.18-1.11-1.477-1.11-2.817 0-1.34.698-1.998.948-2.26.247-.26.547-.327.73-.327.18 0 .363.003.52.01.164.007.387-.063.606.463.224.54.767 1.868.832 2.003.066.13.11.285.02.463-.086.18-.13.282-.26.435-.126.15-.264.33-.377.443-.127.126-.26.263-.11.52.15.25.666 1.096 1.428 1.77.982.87 1.81 1.14 2.07 1.27.26.13.41.11.56-.06.152-.18.652-.76.828-1.02.176-.26.353-.22.593-.13.24.09 1.527.72 1.79.852.26.13.435.195.5.308.066.113.066.657-.18 1.354z" />
                        </svg>
                        Rappel de Renouvellement WhatsApp
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Destinataire (N° de téléphone)</label>
                        <input type="text" wire:model="phoneNumber" 
                               class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-650 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350 mb-1">Message</label>
                        <textarea wire:model="message" rows="6" 
                                  class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-650 rounded-xl bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-sans"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700 flex justify-end space-x-3">
                    <button type="button" wire:click="$set('showModal', false)" 
                            class="px-4 py-2 border border-slate-200 dark:border-slate-600 text-slate-750 dark:text-slate-300 rounded-xl text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-700">
                        Annuler
                    </button>
                    <button type="button" wire:click="send" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        Envoyer le message
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
