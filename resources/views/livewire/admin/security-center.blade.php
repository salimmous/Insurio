<div class="py-6 font-sans w-full">
    <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center shadow">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Security Center</h1>
                <p class="text-slate-500 text-sm">Manage your account security, sessions, and two-factor authentication</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="flex border-b border-slate-200">
                @foreach([
                    ['sessions', 'Active Sessions', 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['history', 'Login History', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['devices', 'Trusted Devices', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['mfa', 'Two-Factor Auth', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ['password', 'Password', 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
                ] as [$key, $label, $icon])
                <button
                    wire:click="setTab('{{ $key }}')"
                    class="flex-1 flex items-center justify-center gap-2 py-4 text-sm font-medium transition-all duration-200 {{ $activeTab === $key ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                    </svg>
                    <span class="hidden md:block">{{ $label }}</span>
                </button>
                @endforeach
            </div>

            <div class="p-6">

                {{-- ============================================================ --}}
                {{-- TAB: ACTIVE SESSIONS --}}
                {{-- ============================================================ --}}
                @if($activeTab === 'sessions')
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-slate-800">Active Sessions</h2>
                        <button wire:click="revokeAllSessions" class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                            Revoke all other sessions
                        </button>
                    </div>
                    <div class="space-y-3">
                        @forelse($sessions as $session)
                        <div class="flex items-center justify-between p-4 rounded-xl border {{ $session->is_current ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-slate-50/50' }}">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 text-sm">{{ $session->browser }} on {{ $session->os }}</p>
                                    <p class="text-xs text-slate-500">{{ $session->ip_address }} · Last active {{ $session->last_activity->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($session->is_current)
                                <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded-full">Current</span>
                                @else
                                <button wire:click="revokeSession('{{ $session->id }}')" class="text-xs text-red-600 hover:text-red-800 font-medium transition-colors">
                                    Revoke
                                </button>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-slate-400 text-sm text-center py-8">No active sessions found.</p>
                        @endforelse
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- TAB: LOGIN HISTORY --}}
                {{-- ============================================================ --}}
                @elseif($activeTab === 'history')
                <div class="space-y-4">
                    <h2 class="font-semibold text-slate-800">Login History <span class="text-sm font-normal text-slate-400">(Last 30 events)</span></h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                    <th class="pb-3 pr-4">Date</th>
                                    <th class="pb-3 pr-4">IP Address</th>
                                    <th class="pb-3 pr-4">Device</th>
                                    <th class="pb-3 pr-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($loginHistories as $history)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 pr-4 text-slate-600">{{ $history->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-3 pr-4 text-slate-600 font-mono text-xs">{{ $history->ip_address ?? '—' }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $history->browser }} on {{ $history->os }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $history->status_badge }}">
                                            {{ ucfirst($history->status) }}
                                            @if($history->is_suspicious) ⚠️ @endif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="py-8 text-center text-slate-400">No login history yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- TAB: TRUSTED DEVICES --}}
                {{-- ============================================================ --}}
                @elseif($activeTab === 'devices')
                <div class="space-y-4">
                    <h2 class="font-semibold text-slate-800">Trusted Devices</h2>
                    <p class="text-sm text-slate-500">These devices skip two-factor authentication for 30 days after being trusted.</p>
                    <div class="space-y-3">
                        @forelse($trustedDevices as $device)
                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-lg border border-slate-200 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700 text-sm">{{ $device->device_name ?? 'Unknown Device' }}</p>
                                    <p class="text-xs text-slate-500">{{ $device->ip_address }} · Expires {{ $device->expires_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <button wire:click="revokeDevice({{ $device->id }})" class="text-xs text-red-600 hover:text-red-800 font-medium transition-colors">
                                Remove
                            </button>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <p class="text-slate-400 text-sm">No trusted devices.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- TAB: TWO-FACTOR AUTH --}}
                {{-- ============================================================ --}}
                @elseif($activeTab === 'mfa')
                <div class="space-y-6 max-w-lg">
                    <div>
                        <h2 class="font-semibold text-slate-800">Two-Factor Authentication</h2>
                        <p class="text-sm text-slate-500 mt-1">Add an extra layer of security to your account using email verification codes.</p>
                    </div>

                    @if(auth()->user()->hasTwoFactorEnabled())
                    <!-- MFA Enabled State -->
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-emerald-800 text-sm">Two-factor authentication is enabled</p>
                            <p class="text-xs text-emerald-600">Your account is protected with email OTP verification.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm password to disable MFA</label>
                        <input wire:model="current_password" type="password" placeholder="Enter your password"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all text-sm">
                        @error('current_password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button wire:click="disableMfa" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-all">
                        Disable Two-Factor Authentication
                    </button>

                    @else
                    <!-- MFA Disabled State -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm text-amber-700">Two-factor authentication is <strong>disabled</strong>. We strongly recommend enabling it.</p>
                    </div>

                    <button wire:click="enableMfa" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-all">
                        Enable Two-Factor Authentication
                    </button>

                    @if($mfaError)
                    <p class="text-sm text-red-600">{{ $mfaError }}</p>
                    @endif

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Enter the code sent to your email</label>
                        <input wire:model="mfa_code" type="text" inputmode="numeric" maxlength="6" placeholder="000000"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-center text-xl font-bold tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <button wire:click="confirmEnableMfa" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all">
                            Confirm & Enable
                        </button>
                    </div>
                    @endif
                </div>

                {{-- ============================================================ --}}
                {{-- TAB: PASSWORD --}}
                {{-- ============================================================ --}}
                @elseif($activeTab === 'password')
                <div class="space-y-6 max-w-lg">
                    <div>
                        <h2 class="font-semibold text-slate-800">Change Password</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Last changed:
                            @if(auth()->user()->password_changed_at)
                                {{ auth()->user()->password_changed_at->diffForHumans() }}
                            @else
                                Never recorded
                            @endif
                        </p>
                    </div>

                    @if($passwordSuccess)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-sm text-emerald-700">
                        {{ $passwordSuccess }}
                    </div>
                    @endif

                    @if(!empty($passwordErrors))
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-1">
                        @foreach($passwordErrors as $error)
                        <p class="text-sm text-red-700">{{ $error }}</p>
                        @endforeach
                    </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Current Password</label>
                            <input wire:model="current_password" type="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                            <input wire:model="new_password" type="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                            <input wire:model="new_password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <p class="text-xs font-semibold text-slate-600 mb-2">Requirements</p>
                        <ul class="space-y-1 text-xs text-slate-500">
                            <li>✓ Minimum 12 characters</li>
                            <li>✓ Uppercase + lowercase + number + special character</li>
                            <li>✓ Cannot contain your name or email</li>
                            <li>✓ Cannot match last 5 passwords</li>
                        </ul>
                    </div>

                    <button wire:click="changePassword" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl text-sm transition-all">
                        Update Password
                    </button>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
