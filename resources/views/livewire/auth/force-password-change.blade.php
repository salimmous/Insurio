<div class="min-h-screen bg-gradient-to-br from-slate-50 to-amber-50 flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-slate-800">Insurio</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-8 py-6">
                <h1 class="text-lg font-bold text-white">Password Update Required</h1>
                <p class="text-amber-100 text-sm mt-1">Your password has expired. Please set a new secure password to continue.</p>
            </div>

            <div class="p-8 space-y-6">

                @if(!empty($policyErrors))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-1">
                    @foreach($policyErrors as $error)
                    <p class="text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ $error }}
                    </p>
                    @endforeach
                </div>
                @endif

                <!-- Password Requirements -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Password Requirements</p>
                    <ul class="space-y-1 text-xs text-slate-500">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Minimum 12 characters</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> At least one uppercase letter</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> At least one number</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> At least one special character (!@#$%...)</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span> Cannot reuse last 5 passwords</li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                        <input
                            wire:model="password"
                            type="password"
                            placeholder="Enter new password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                        <input
                            wire:model="password_confirmation"
                            type="password"
                            placeholder="Confirm new password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all"
                        >
                    </div>
                </div>

                <button
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-amber-500/20 transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Update Password</span>
                    <span wire:loading wire:target="save">Updating...</span>
                </button>

            </div>
        </div>
    </div>
</div>
