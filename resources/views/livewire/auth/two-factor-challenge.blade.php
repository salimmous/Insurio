<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-4 font-sans">
    <div class="w-full max-w-md">

        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-slate-800">Insurio</span>
            </div>
            <p class="text-slate-500 text-sm">Enterprise Insurance Platform</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Two-Factor Verification</h1>
                        <p class="text-blue-100 text-sm">Enter the code sent to your email</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">

                @if($codeSent)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-blue-700">
                        A 6-digit verification code has been sent to <strong>{{ auth()->user()->email }}</strong>. It expires in 10 minutes.
                    </p>
                </div>
                @endif

                @if($errorMessage)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Verification Code</label>
                    <input
                        wire:model="code"
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="000000"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-center text-2xl font-bold tracking-[0.5em] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        autofocus
                    >
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="rememberDevice" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <div>
                        <span class="text-sm font-medium text-slate-700">Trust this device for 30 days</span>
                        <p class="text-xs text-slate-500">Skip this step on your next login from this device</p>
                    </div>
                </label>

                <button
                    wire:click="verify"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <svg wire:loading wire:target="verify" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="verify">Verify & Continue</span>
                    <span wire:loading wire:target="verify">Verifying...</span>
                </button>

                <div class="text-center">
                    <button wire:click="sendCode" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        Didn't receive the code? Resend
                    </button>
                </div>

                <div class="border-t border-slate-100 pt-4 text-center">
                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-mfa').submit();" class="text-sm text-slate-500 hover:text-slate-700 transition-colors">
                        ← Back to login
                    </a>
                    <form id="logout-mfa" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            Insurio Enterprise · Secured with 256-bit encryption
        </p>
    </div>
</div>
