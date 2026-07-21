<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordPolicyService
{
    /**
     * Minimum password length.
     */
    private const MIN_LENGTH = 12;

    /**
     * Validate a password against the Insurio password policy.
     *
     * @return array<string> List of validation errors (empty = valid)
     */
    public function validate(string $password, User $user): array
    {
        $errors = [];

        if (strlen($password) < self::MIN_LENGTH) {
            $errors[] = __('Password must be at least :min characters.', ['min' => self::MIN_LENGTH]);
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = __('Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = __('Password must contain at least one lowercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = __('Password must contain at least one number.');
        }

        if (!preg_match('/[\W_]/', $password)) {
            $errors[] = __('Password must contain at least one special character (!@#$%^&*...).');
        }

        // Must not contain the user's email prefix
        $emailPrefix = explode('@', $user->email)[0];
        if (str_contains(strtolower($password), strtolower($emailPrefix))) {
            $errors[] = __('Password must not contain your email address.');
        }

        // Must not contain the user's name
        if (strlen($user->name) >= 4 && str_contains(strtolower($password), strtolower($user->name))) {
            $errors[] = __('Password must not contain your name.');
        }

        return $errors;
    }

    /**
     * Check if password has been used before (last 5).
     */
    public function isReused(string $password, User $user): bool
    {
        return $user->hasUsedPassword($password);
    }

    /**
     * Validate and return first error or null.
     */
    public function firstError(string $password, User $user): ?string
    {
        $errors = $this->validate($password, $user);
        return $errors[0] ?? null;
    }

    /**
     * Fully validates password and checks reuse history.
     */
    public function passes(string $password, User $user): bool
    {
        return empty($this->validate($password, $user)) && !$this->isReused($password, $user);
    }
}
