<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -----------------------------------------------------------------
        // 1. Add security columns to users table
        // -----------------------------------------------------------------
        Schema::table('users', function (Blueprint $table) {
            $table->integer('failed_login_attempts')->default(0)->after('password');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            $table->timestamp('last_login_at')->nullable()->after('locked_until');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->timestamp('password_changed_at')->nullable()->after('last_login_ip');
            // TOTP / Email OTP
            $table->text('two_factor_secret')->nullable()->after('password_changed_at');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_confirmed_at');
            // Pending email OTP (used for email-based 2FA)
            $table->string('two_factor_code', 6)->nullable()->after('two_factor_recovery_codes');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
        });

        // -----------------------------------------------------------------
        // 2. Password history (last 5 passwords per user)
        // -----------------------------------------------------------------
        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('password');
            $table->timestamp('created_at')->useCurrent();
        });

        // -----------------------------------------------------------------
        // 3. Login history
        // -----------------------------------------------------------------
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_fingerprint', 64)->nullable();
            $table->string('status', 20)->default('success'); // success | failed | locked
            $table->string('failure_reason', 100)->nullable();
            $table->boolean('is_suspicious')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        // -----------------------------------------------------------------
        // 4. Trusted devices
        // -----------------------------------------------------------------
        Schema::create('trusted_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('device_fingerprint', 64);
            $table->string('device_name')->nullable(); // e.g. "Chrome on macOS"
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'device_fingerprint']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'failed_login_attempts',
                'locked_until',
                'last_login_at',
                'last_login_ip',
                'password_changed_at',
                'two_factor_secret',
                'two_factor_confirmed_at',
                'two_factor_recovery_codes',
                'two_factor_code',
                'two_factor_expires_at',
            ]);
        });

        Schema::dropIfExists('trusted_devices');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('password_histories');
    }
};
