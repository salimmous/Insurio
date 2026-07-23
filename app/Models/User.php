<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name', 'email', 'password', 'branch_id', 'status',
    'invitation_token', 'invitation_expires_at', 'invitation_sent_at',
    'failed_login_attempts', 'locked_until', 'last_login_at', 'last_login_ip',
    'password_changed_at', 'two_factor_secret', 'two_factor_confirmed_at',
    'two_factor_recovery_codes', 'two_factor_code', 'two_factor_expires_at',
])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'password'                 => 'hashed',
            'locked_until'             => 'datetime',
            'last_login_at'            => 'datetime',
            'password_changed_at'      => 'datetime',
            'two_factor_confirmed_at'  => 'datetime',
            'two_factor_expires_at'    => 'datetime',
            'invitation_expires_at'    => 'datetime',
            'invitation_sent_at'       => 'datetime',
            'failed_login_attempts'    => 'integer',
        ];
    }

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------

    public function branch(): BelongsTo
    {
        return $this->belongsTo(AgenceBranch::class, 'branch_id');
    }

    public function employe(): HasOne
    {
        return $this->hasOne(Employe::class, 'user_id');
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class)->latest();
    }

    public function passwordHistories(): HasMany
    {
        return $this->hasMany(PasswordHistory::class)->latest();
    }

    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }

    // ------------------------------------------------------------------
    // Account Lockout Helpers
    // ------------------------------------------------------------------

    public function isLocked(): bool
    {
        if (!$this->locked_until) {
            return false;
        }
        if ($this->locked_until->isPast()) {
            // Auto-unlock if lock period has passed
            $this->update(['locked_until' => null, 'failed_login_attempts' => 0]);
            return false;
        }
        return true;
    }

    public function lockAccount(int $minutes = 30): void
    {
        $this->update(['locked_until' => now()->addMinutes($minutes)]);
    }

    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        $this->refresh();

        // Lock after 5 failed attempts
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount(30);
        }
    }

    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
        ]);
    }

    // ------------------------------------------------------------------
    // Two-Factor Auth Helpers
    // ------------------------------------------------------------------

    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_confirmed_at);
    }

    public function generateTwoFactorCode(): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'two_factor_code'       => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);
        return $code;
    }

    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->two_factor_code || !$this->two_factor_expires_at) {
            return false;
        }
        if ($this->two_factor_expires_at->isPast()) {
            return false;
        }
        return hash_equals($this->two_factor_code, $code);
    }

    public function clearTwoFactorCode(): void
    {
        $this->update(['two_factor_code' => null, 'two_factor_expires_at' => null]);
    }

    // ------------------------------------------------------------------
    // Password Policy Helpers
    // ------------------------------------------------------------------

    public function isPasswordExpired(int $days = 90): bool
    {
        if (!$this->password_changed_at) {
            return false; // Grandfathered users: don't force change immediately
        }
        return $this->password_changed_at->addDays($days)->isPast();
    }

    public function hasUsedPassword(string $newPassword): bool
    {
        return $this->passwordHistories()
            ->latest()
            ->take(5)
            ->get()
            ->contains(fn($history) => Hash::check($newPassword, $history->password));
    }

    public function recordPasswordHistory(): void
    {
        $this->passwordHistories()->create(['password' => $this->password]);
        // Keep only last 5
        $ids = $this->passwordHistories()->skip(5)->pluck('id');
        if ($ids->isNotEmpty()) {
            PasswordHistory::whereIn('id', $ids)->delete();
        }
    }

    // ------------------------------------------------------------------
    // Device Trust Helpers
    // ------------------------------------------------------------------

    public function isDeviceTrusted(string $fingerprint): bool
    {
        return $this->trustedDevices()
            ->where('device_fingerprint', $fingerprint)
            ->where('expires_at', '>', now())
            ->exists();
    }
}
